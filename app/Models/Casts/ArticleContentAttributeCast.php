<?php

namespace App\Models\Casts;

use App\Models\Article;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Str;

class ArticleContentAttributeCast implements CastsAttributes
{
    /**
     * Transform the attribute from the underlying model values.
     *
     * @param  Article  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return string
     */
    public function get($model, string $key, $value, array $attributes): string
    {
        $content = $attributes['content'];

        if (empty($content)) {
            return (string) $content;
        }

        // Give shortcodes from app settings. static::$shortcodes ???
        $shortcodes = [
            'app_url' => setting('system.app_url') ?? config('app.url'),
            'organization' => setting('system.org_name'),
            'contact_email' => setting('system.org_contact_email'),
            'address' => setting('system.org_address_locality').', '.setting('system.org_address_street'),
        ];

        $telephone = setting('system.org_contact_telephone');

        $shortcodes['contact_telephone'] = $telephone ? sprintf(
            '<a href="tel:+%d" rel="nofollow" class="font-weight-bold">%s</a>',
            preg_replace('/\D/', '', $telephone),
            $telephone
        ) : null;

        // Give shortcode from extra fields. static::$x_shortcode ???
        // $x_shortcode = $model->x_fields->keyBy('name')->toArray();
        $x_shortcode = $model->x_fields->pluck('name')->toArray();

        preg_match_all("/\[\[(.*?)\]\]/u", $content, $matches);

        foreach ($matches[1] as $row) {
            if (array_key_exists($row, $shortcodes)) {
                $content = str_ireplace('[[' . $row . ']]', $shortcodes[$row], $content);
            } elseif (in_array($row, $x_shortcode)) {
                $content = str_ireplace('[[' . $row . ']]', $model->{$row}, $content);
            } elseif (Str::startsWith($row, 'picture_box_')) {
                $id = (int) str_ireplace('picture_box_', '', $row);
                if ($image = $model->images->where('id', $id)->first()) {
                    $content = str_ireplace("[[picture_box_$id]]", $image->picture_box, $content);
                } else {
                    $content = str_ireplace("[[picture_box_$id]]", '<code>IMG WITH ID #'.$id.' IN THIS ARTICLE NOT FOUND.</code>', $content);
                }
            } elseif (Str::startsWith($row, 'media_player_')) {
                $id = (int) str_ireplace('media_player_', '', $row);
                if ($attachment = $model->attachments->where('id', $id)->first()) {
                    $content = str_ireplace("[[media_player_$id]]", $attachment->media_player, $content);
                } else {
                    $content = str_ireplace("[[media_player_$id]]", '<code>ATTACHMENT WITH ID #'.$id.' IN THIS ARTICLE NOT FOUND.</code>', $content);
                }
            } elseif (Str::startsWith($row, 'download_button_')) {
                $id = (int) str_ireplace('download_button_', '', $row);
                if ($attachment = $model->attachments->where('id', $id)->first()) {
                    $content = str_ireplace("[[download_button_$id]]", $attachment->download_button, $content);
                } else {
                    $content = str_ireplace("[[download_button_$id]]", '<code>ATTACHMENT WITH ID #'.$id.' IN THIS ARTICLE NOT FOUND.</code>', $content);
                }
            } elseif (Str::startsWith($row, 'attachment_')) {
                $id = (int) str_ireplace('attachment_', '', $row);
                if ($attachment = $model->attachments->where('id', $id)->first()) {
                    $content = str_ireplace("[[attachment_$id]]", $attachment->url, $content);
                } else {
                    $content = str_ireplace("[[attachment_$id]]", '<code>ATTACHMENT WITH ID #'.$id.' IN THIS ARTICLE NOT FOUND.</code>', $content);
                }
            }
        }

        return $content;
    }

    /**
     * Transform the attribute to its underlying model values.
     *
     * @param  Article  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return $value;
    }
}
