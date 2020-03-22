<?php

namespace App\Models\Relations;

use App\Models\Tag;

trait Taggable
{
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable', 'taggables', 'taggable_id', 'tag_id');
    }

    public function getTags()
    {
        return $this->tags()->get(['tags.id','tags.title']);
    }
}
