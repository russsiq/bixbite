<?php

namespace BBCMS\Models\Relations;

use BBCMS\Models\File;

trait Fileable
{
    /**
     * Get all of the post's comments.
     */
    public function files()
    {
        return $this->morphMany(File::class, 'attachment', 'attachment_type', 'attachment_id', 'id');
    }
}
