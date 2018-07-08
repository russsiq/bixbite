<?php

namespace BBCMS\Models\Scopes;

use Carbon\Carbon;

trait FilterScope
{
    // Filter
    public function scopeFilter($query, $filters)
    {
        $query->when($filters['month'] ?? false, function($query) use ($filters) {
            $query->whereMonth('created_at', Carbon::parse($filters['month'])->month);
        })
        ->when($filters['year'] ?? false, function($query) use ($filters) {
            $query->whereYear('created_at', $filters['year']);
        })
        ->when($filters['user_id'] ?? false, function($query) use ($filters) {
            $query->where('user_id', (int) $filters['user_id']);
        });

        // $query->when(request('filter_by') == 'likes', function ($q) {
        //     return $q->where('likes', '>', request('likes_amount', 0));
        // });

        // $query->when(request('role', false), function ($q, $role) {
        //     return $q->where('role_id', $role);
        // });
    }
}
