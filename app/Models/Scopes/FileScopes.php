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

        $query->when($filters['attachment_id'] ?? false, function($query) use ($filters) {
            $query->where('attachment_id', (int) $filters['attachment_id']);
        });

        $query->when($filters['attachment_type'] ?? false, function($query) use ($filters) {
            $query->where('attachment_type', html_clean($filters['attachment_type']));
        });
    }
}
