<?php

namespace BBCMS\Models\Mutators;

trait FileMutators
{
    /**
     * Url variations.
     */
    public function getUrlAttribute($thumbSize = null) // {{ $file->url }} // $image->getUrlAttribute('thumb')
    {
        $path = $this->getPathAttribute($thumbSize);

        return $path ? $this->storageDisk()->url(str_replace('\\', '/', $path)) : null;
    }

    /**
     * Path variations.
     */
    public function getPathAttribute($thumbSize = null) // {{ $file->path }} // $image->getPathAttribute('thumb')
    {
        $path = $this->path($thumbSize);

        return $this->storageDisk()->exists($path) ? $path : null;
    }

    public function getAbsolutePathAttribute($thumbSize = null) // {{ $file->absolute_path }} // $image->getAbsolutePathAttribute('thumb')
    {
        $path = $this->getPathAttribute($thumbSize);

        return $path ? $this->storageDisk()->path($path) : null;
    }

    /**
     * {{ $image->picture_box }}
     *
     * @param  [type] $thumbSize [description]
     * @return [type]            [description]
     */
    public function getPictureBoxAttribute()
    {
        if ('image' != $this->attributes['type']) {
            return null;
        }

        $srcset = '';
        foreach ($this->thumbSizes as $size => $value) {
            if ($url = $this->getUrlAttribute($size)) {
                $srcset .= '<source media="(max-width: '.$value.'px)" srcset="'.$url.'" type="'.$this->attributes['mime_type'].'">';
            }
        }

        $srcset .= '<img src="'.$this->url.'" alt="'.$this->title.'" class="single_article_image__img">'; // rounded

        return html_raw('<picture class="single_article_image__inner">'.$srcset.'</picture>');
    }
}
