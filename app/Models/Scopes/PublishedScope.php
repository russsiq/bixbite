<?php

namespace BBCMS\Models\Scopes;

trait PublishedScope
{
    public function scopePublished($query)
    {
        return $query->where('state', 'published');
    }
}
