<?php

namespace App\Models\Mutators;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

trait AtachmentMutators
{
    /**
     * Path variations.
     * Used as `{{ $attachment->path }}` or `{{ $image->getPathAttribute('thumb') }}`.
     *
     * @param  string|null $thumbSize Thumbnail size for image file.
     * @return string|null
     */
    public function getPathAttribute($thumbSize = null)
    {
        $path = $this->path($thumbSize);

        return $this->storageDisk()->exists($path) ? $path : null;
    }

    /**
     * Absolute path variations.
     * Used as `{{ $attachment->absolute_path }}` or `{{ $image->getAbsolutePathAttribute('thumb') }}`.
     *
     * @param  string|null $thumbSize Thumbnail size for image file.
     * @return string|null
     */
    public function getAbsolutePathAttribute($thumbSize = null)
    {
        $path = $this->getPathAttribute($thumbSize);

        return $path ? $this->storageDisk()->path($path) : null;
    }

    /**
     * Url variations.
     * Used as `{{ $attachment->url }}` or `{{ $image->getUrlAttribute('thumb') }}`.
     *
     * @param  string|null $thumbSize Thumbnail size for image file.
     * @return string|null
     */
    public function getUrlAttribute($thumbSize = null)
    {
        $path = $this->getPathAttribute($thumbSize);

        return $path ? $this->storageDisk()->url(str_replace('\\', '/', $path)) : null;
    }

    /**
     * Shows the size of a file in human readable format in bytes to kb, mb, gb, tb.
     * Used as `{{ $attachment->filesize }}`.
     *
     * @return string|null
     */
    public function getFilesizeAttribute()
    {
        return Str::humanFilesize($this->attributes['filesize']);
    }

    /**
     * Used as `{{ $image->picture_box }}`.
     *
     * @return null|string
     */
    public function getPictureBoxAttribute()
    {
        // Load theme view.
        View::addLocation(theme_path('views'));

        if ('image' != $this->attributes['type']) {
            return null;
        }

        $id = $this->id;
        $url = $this->url;
        $alt = $this->title;
        $title = $this->title;
        $description = $this->description;

        $srcsets = [];

        foreach ($this->thumbSizes() as $size => $value) {
            if ($thumb_url = $this->getUrlAttribute($size)) {
                $srcsets[] = (object) [
                    'size' => $value.'px',
                    'url' => $thumb_url,
                    'mime_type' => $this->attributes['mime_type'],
                ];
            }
        }

        return html_raw(view(
            'shortcodes.picture_box',
            compact('id', 'url', 'alt', 'title', 'description', 'srcsets')
        )->render());
    }

    /**
     * Used as `{{ $attachment->media_player }}`.
     *
     * @return null|string
     */
    public function getMediaPlayerAttribute()
    {
        // Load theme view.
        View::addLocation(theme_path('views'));

        if (!in_array($this->attributes['type'], ['video', 'audio'])) {
            return null;
        }

        return html_raw(view('shortcodes.media_player', [
            'media' => (object) $this->toArray()
        ])->render());
    }

    /**
     * Download button to file.
     * Used as `{{ $attachment->download_button }}`.
     *
     * @return string|null
     */
    public function getDownloadButtonAttribute()
    {
        // Load theme view.
        View::addLocation(theme_path('views'));

        return html_raw(view('shortcodes.download_button', [
            'file' => (object) $this->toArray()
        ])->render());
    }
}
