<?php

namespace BBCMS\Models\Scopes;

use Carbon\Carbon;

trait FilterScope
{
    // Filter
    public function scopeFilter($query, $filters)
    {
        if (! empty($filters['month']) and $month = $filters['month']) {
            $query->whereMonth('created_at', Carbon::parse($month)->month);
        }
        if (! empty($filters['year']) and $year = $filters['year']) {
            $query->whereYear('created_at', $year);
        }
        if (! empty($filters['user']) and $user = $filters['user']) {
            $query->where('user_id', (int) $user);
                // ->orWhere();
        }

        // $query->when(request('filter_by') == 'likes', function ($q) {
        //     return $q->where('likes', '>', request('likes_amount', 0));
        // });

        // $query->when(request('role', false), function ($q, $role) {
        //     return $q->where('role_id', $role);
        // });
    }
}
