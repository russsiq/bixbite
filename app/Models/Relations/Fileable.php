<?php

namespace BBCMS\Models\Relations;

use BBCMS\Models\File;

trait Fileable
{
    public function files()
    {
        return $this->morphMany(File::class, 'attachment', 'attachment_type', 'attachment_id', 'id');
    }

    // public function image() {
    //     return $this->hasOne(File::class, 'attachment_id', 'id')
    //         ->where('attachment_type', $this->getMorphClass())
    //         ->where('type', 'image');
    // }

    public function getImageAttribute()
    {
        return $this->images->where('id', $this->image_id)->first();

        // ->with([
        //     'files' => function ($query) {
        //         $query->select([
        //             'files.id', 'files.disk', 'files.type',
        //             'files.category', 'files.name', 'files.extension',
        //             'files.attachment_type','files.attachment_id'
        //         ])
        //         ->join('categories', function ($join) {
        //             $join->on('files.id', '=', 'categories.image_id');
        //         })
        //         ->where('type', 'image');
        //     },
        // ])
    }

    public function getImagesAttribute()
    {
        return $this->files->where('type', 'image');
    }
}
