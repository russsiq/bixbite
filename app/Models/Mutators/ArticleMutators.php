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
        return action('ArticlesController@article', [$this->categories->pluck('slug')->implode('_'), $this->id, $this->slug]);
    }

    public function getTeaserAttribute()
    {
        return $this->attributes['teaser'] ?? teaser($this->content, setting('articles.teaser_length', 150));
    }

    /**
     * Generate content with `shortcode` and `x_fields`.
     *
     * @return string
     */
    public function getContentAttribute()
    {
        if (empty($this->attributes['content']) or is_null($content = $this->attributes['content'])) {
            return null;
        }

        // Give shortcode from app settings. static::$shortcode ???
        $shortcode = [
            'phone' => '+7 (888) 888-88-88',
            'address' => 'г.Негород, ул.Неулица, д.2019',
        ];

        // Give shortcode from extra fields. static::$x_shortcode ???
        // $x_shortcode = $this->x_fields->keyBy('name')->toArray();
        $x_shortcode = $this->x_fields->pluck('name')->toArray();

        preg_match_all("/\[\[(.*?)\]\]/u", $content, $matches);

        foreach ($matches[1] as $row) {
            if (array_key_exists($row, $shortcode)) {
                $content = str_ireplace('[[' . $row . ']]', $shortcode[$row], $content);
            } elseif (in_array($row, $x_shortcode)) {
                $content = str_ireplace('[[' . $row . ']]', $this->{$row}, $content);
            } elseif (starts_with($row, 'picture_box_')) {
                $id = (int) str_replace('picture_box_', '', $row);
                if ($image = $this->images->where('id', $id)->first()) {
                    $image = '<figure class="single_article__image">'
                                .$image->getPictureBoxAttribute()
                                .'<figcaption class="single_article_image__caption">'.$image->title.'</figcaption>'
                            .'</figure>';
                    $content = str_ireplace("[[picture_box_$id]]", $image, $content);
                } else {
                    $content = str_ireplace("[[picture_box_$id]]", '<code>IMG WITH ID #'.(int) $id.' IN THIS ARTICLE NOT FOUND.</code>', $content);
                }
            } elseif (starts_with($row, 'postfile_') or starts_with($row, 'postimage_')) {
                $id = (int) str_ireplace(['postfile_', 'postimage_'], '', $row);
                if ($file = $this->files->where('id', $id)->first()) {
                    $content = str_ireplace([
                            "[[postfile_$id]]",
                            "[[postimage_$id]]"
                        ], $file->url, $content);
                } else {
                    $content = str_ireplace([
                        "[[postfile_$id]]",
                        "[[postimage_$id]]"
                    ], '<code>FILE WITH ID #'.(int) $id.' IN THIS ARTICLE NOT FOUND.</code>', $content);
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
     * @return mixed
     */
    public function getDatePublishedAttribute() // ToDo: create column published_at.
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
