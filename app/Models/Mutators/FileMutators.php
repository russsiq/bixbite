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
        if ($thumbSize and 'image' == $this->attributes['type']) {
            if ($this->storageDisk()->exists(
                $path = $this->attributes['type'].DS.$this->attributes['category']
                .DS.$thumbSize
                .DS.$this->attributes['name'].'.'.$this->attributes['extension']
            )) {
                return $path;
            }

            // To $image->picture_box
            return null;
        }

        return $this->attributes['type'].DS.$this->attributes['category']
            .DS.$this->attributes['name'].'.'.$this->attributes['extension'];
    }

    public function getAbsolutePathAttribute($thumbSize = null) // {{ $file->absolute_path }} // $image->getAbsolutePathAttribute('thumb')
    {
        if ($path = $this->getPathAttribute($thumbSize)) {
            return $this->storageDisk()->path($path);
        }

        return null;
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

        $srcset .= '<img src="'.$this->url.'" alt="'.$this->title.'" class="figure-img img-fluid">'; // rounded

        return html_raw('<picture>'.$srcset.'</picture>');
    }
}
