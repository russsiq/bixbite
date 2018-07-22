<?php

namespace BBCMS\Models\Scopes;

trait FileScopes
{
    // Filter
    public function scopeFilter($query, $filters)
    {
        $query->when($filters['filetype'] ?? false, function($query) use ($filters) {
            $query->where('type', $filters['filetype']);
        });
    }
}
