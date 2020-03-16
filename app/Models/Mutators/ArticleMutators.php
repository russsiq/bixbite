<?php

namespace BBCMS\Models\Mutators;

/**
 * https://schema.org/Article
 * https://github.com/russsiq/art-schema-markup
 */

trait ArticleMutators
{
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = string_slug($value ?? $this->title);
    }

    public function getUrlAttribute()
    {
        return ($this->id and $this->categories->count() > 0  and 'published' === $this->state)
            ? action('ArticlesController@article', [
                $this->categories->pluck('slug')->implode('_'),
                $this->id,
                $this->slug
            ]) : null;
    }

    public function getEditPageAttribute()
    {
        return $this->id ? route('panel')."/$this->table/$this->id/edit" : null;
    }

    /**
     * Generate content with `shortcode` and `x_fields`.
     * @return string
     */
    public function getContentAttribute()
    {
        if (empty($content = $this->attributes['content'])) {
            // Возвращаем пустую строку, чтобы `quill-editor`
            // не ругался при валидации входящих данных.
            return '';
        }

        // Give shortcodes from app settings. static::$shortcodes ???
        $shortcodes = [
            'app_url' => setting('system.app_url'),
            'organization' => setting('system.organization'),
            'contact_telephone' => setting('system.contact_telephone'),
            'contact_email' => setting('system.contact_email'),
            'address' => setting('system.address_locality').', '.setting('system.address_street'),
        ];

        // Give shortcode from extra fields. static::$x_shortcode ???
        // $x_shortcode = $this->x_fields->keyBy('name')->toArray();
        $x_shortcode = $this->x_fields->pluck('name')->toArray();

        preg_match_all("/\[\[(.*?)\]\]/u", $content, $matches);

        foreach ($matches[1] as $row) {
            if (array_key_exists($row, $shortcodes)) {
                $content = str_ireplace('[[' . $row . ']]', $shortcodes[$row], $content);
            } elseif (in_array($row, $x_shortcode)) {
                $content = str_ireplace('[[' . $row . ']]', $this->{$row}, $content);
            } elseif (starts_with($row, 'picture_box_')) {
                $id = (int) str_ireplace('picture_box_', '', $row);
                if ($image = $this->images->where('id', $id)->first()) {
                    $content = str_ireplace("[[picture_box_$id]]", $image->picture_box, $content);
                } else {
                    $content = str_ireplace("[[picture_box_$id]]", '<code>IMG WITH ID #'.$id.' IN THIS ARTICLE NOT FOUND.</code>', $content);
                }
            } elseif (starts_with($row, 'media_player_')) {
                $id = (int) str_ireplace('media_player_', '', $row);
                if ($file = $this->files->where('id', $id)->first()) {
                    $content = str_ireplace("[[media_player_$id]]", $file->media_player, $content);
                } else {
                    $content = str_ireplace("[[media_player_$id]]", '<code>FILE WITH ID #'.$id.' IN THIS ARTICLE NOT FOUND.</code>', $content);
                }
            } elseif (starts_with($row, 'download_button_')) {
                $id = (int) str_ireplace('download_button_', '', $row);
                if ($file = $this->files->where('id', $id)->first()) {
                    $content = str_ireplace("[[download_button_$id]]", $file->download_button, $content);
                } else {
                    $content = str_ireplace("[[download_button_$id]]", '<code>FILE WITH ID #'.$id.' IN THIS ARTICLE NOT FOUND.</code>', $content);
                }
            } elseif (starts_with($row, 'file_')) {
                $id = (int) str_ireplace('file_', '', $row);
                if ($file = $this->files->where('id', $id)->first()) {
                    $content = str_ireplace("[[file_$id]]", $file->url, $content);
                } else {
                    $content = str_ireplace("[[file_$id]]", '<code>FILE WITH ID #'.$id.' IN THIS ARTICLE NOT FOUND.</code>', $content);
                }
            }
        }

        return $content;
    }

    public function getViewsAttribute()
    {
        return setting('articles.views_used', true) ? $this->attributes['views'] : null;
    }

    /**
     * Get `created` in humans date format.
     * @return mixed
     */
    public function getCreatedAttribute()
    {
        return empty($this->attributes['created_at']) ? null : $this->created_at->diffForHumans();
    }

    /**
     * Get `updated` in humans date format.
     * @return mixed
     */
    public function getUpdatedAttribute()
    {
        return empty($this->attributes['updated_at']) ? null : $this->updated_at->diffForHumans();
    }

    /**
     * Get `date_created` in ISO 8601 date format.
     * @return mixed
     */
    public function getDateCreatedAttribute()
    {
        return empty($this->attributes['created_at']) ? null : $this->created_at->toIso8601String();
    }

    /**
     * Get `date_published` in ISO 8601 date format.
     * @todo: create column published_at
     * @return mixed
     */
    public function getDatePublishedAttribute()
    {
        return empty($this->attributes['created_at']) ? null : $this->created_at->toIso8601String();
    }

    /**
     * Get `date_modified` in ISO 8601 date format.
     * @return mixed
     */
    public function getDateModifiedAttribute()
    {
        return empty($this->attributes['updated_at']) ? null : $this->updated_at->toIso8601String();
    }
}
