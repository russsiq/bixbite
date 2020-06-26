<?php

namespace App\Models\Scopes;

// Сторонние зависимости.
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;

trait TagScopes
{
    /**
     * Фильтрация записей по часто используемым критериям.
     * @param  Builder  $builder
     * @param  array  $filters
     * @return void
     */
    public function scopeFilter(Builder $builder, array $filters): void
    {
        // $builder->when($filters['month'], function(Builder $builder, string $month) {
        //     $builder->whereMonth('created_at', Carbon::parse($month)->month);
        // })
        // ->when($filters['year'], function(Builder $builder, int $year) {
        //     $builder->whereYear('created_at', $year);
        // })
        // ->when($filters['user_id'], function(Builder $builder, int $user_id) {
        //     $builder->where('user_id', $user_id);
        // });
    }

    /**
     * [scopeSearchByKeyword description]
     * @param  Builder  $builder
     * @param  string|null  $keyword
     * @return void
     */
    public function scopeSearchByKeyword(Builder $builder, ?string $keyword): void
    {
        $builder->when($keyword, function(Builder $builder, string $keyword) {
            $builder->where('title', 'like', '%'.$keyword.'%');
        });
    }
}
