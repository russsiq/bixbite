<?php

namespace BBCMS\Models\Relations;

use BBCMS\Models\File;

trait Fileable
{
    public function files()
    {
        return $this->morphMany(File::class, 'attachment', 'attachment_type', 'attachment_id', 'id');
    }

    public function getImageAttribute()
    {
        return $this->images->where('id', $this->image_id)->first();
    }

    public function getImagesAttribute()
    {
        return $this->files->where('type', 'image');
    }
}
