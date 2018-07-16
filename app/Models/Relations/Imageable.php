<?php

namespace BBCMS\Models\Relations;

use BBCMS\Models\File;

trait Imageable
{
    public function image()
    {
        return $this->belongsTo(File::class, 'image_id', 'id');
    }

    public function images()
    {
        return $this->morphMany(File::class, 'attachment', 'attachment_type', 'attachment_id', 'id')
            ->where('type', 'image');
    }
}
