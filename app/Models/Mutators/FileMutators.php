<?php

namespace BBCMS\Models\Mutators;

trait FileMutators
{
    /**
     * Path variations.
     * Used as `{{ $file->path }}` or `{{ $image->getPathAttribute('thumb') }}`.
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
     * Used as `{{ $file->absolute_path }}` or `{{ $image->getAbsolutePathAttribute('thumb') }}`.
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
     * Used as `{{ $file->url }}` or `{{ $image->getUrlAttribute('thumb') }}`.
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
     * Used as `{{ $image->picture_box }}`.
     *
     * @return null|string
     */
    public function getPictureBoxAttribute()
    {
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

        return html_raw(view('components.partials.picture_box',
            compact('id', 'url', 'alt', 'title', 'description', 'srcsets')
        ));
    }
}
