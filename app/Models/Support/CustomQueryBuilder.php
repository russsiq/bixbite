<?php

namespace BBCMS\Models\Support;

// Сторонние зависимости.
use Illuminate\Support\Str;

/**
* @source https://github.com/codekerala/Laravel-5.6-and-Vue.j-2-Dataviewer-Advanced-Filter
*/
class CustomQueryBuilder
{
    public function apply($query, $data)
    {
        if (isset($data['f'])) {
            foreach ($data['f'] as $filter) {
                $filter['match'] = $filter['filter_match'] ?? 'and';
                $this->makeFilter($query, $filter);
            }
        }

        return $query;
    }

    protected function makeFilter($query, $filter)
    {
        // Find the type of column.
        if (strpos($filter['column'], '.') !== false) {
            // Nested column.
            $filter['match'] = 'and';

            list($relation, $filter['column']) = explode('.', $filter['column']);

            if ($filter['column'] == 'count') {
                $this->{Str::camel($filter['operator'])}($filter, $query, $relation);
            } else {
                $filter['column'] = $relation.'.'.$filter['column'];

                $query->whereHas($relation, function ($q) use ($filter) {
                    $this->{Str::camel($filter['operator'])}($filter, $q);
                });
            }
        } else {
            // Normal column.
            $this->{Str::camel($filter['operator'])}($filter, $query);
        }
    }

    public function boolean($filter, $query)
    {
        return $query->where($filter['column'], (bool) $filter['query_1'], $filter['match']);
    }

    public function equalToCount($filter, $query, $relation)
    {
        return $query->has($relation, '=', $filter['query_1']);
    }

    public function notEqualToCount($filter, $query, $relation)
    {
        return $query->has($relation, '<>', $filter['query_1']);
    }

    public function lessThanCount($filter, $query, $relation)
    {
        return $query->has($relation, '<', $filter['query_1']);
    }

    public function greaterThanCount($filter, $query, $relation)
    {
        return $query->has($relation, '>', $filter['query_1']);
    }

    public function equalTo($filter, $query)
    {
        return $query->where($filter['column'], '=', $filter['query_1'], $filter['match']);
    }

    public function notEqualTo($filter, $query)
    {
        return $query->where($filter['column'], '<>', $filter['query_1'], $filter['match']);
    }

    public function lessThan($filter, $query)
    {
        return $query->where($filter['column'], '<', $filter['query_1'], $filter['match']);
    }

    public function greaterThan($filter, $query)
    {
        return $query->where($filter['column'], '>', $filter['query_1'], $filter['match']);
    }

    public function between($filter, $query)
    {
        return $query->whereBetween($filter['column'], [$filter['query_1'], $filter['query_2']], $filter['match']);
    }

    public function notBetween($filter, $query)
    {
        return $query->whereNotBetween($filter['column'], [$filter['query_1'], $filter['query_2']], $filter['match']);
    }

    public function contains($filter, $query)
    {
        return $query->where($filter['column'], 'like', '%'.$filter['query_1'].'%', $filter['match']);
    }

    public function startsWith($filter, $query)
    {
        return $query->where($filter['column'], 'like', $filter['query_1'].'%', $filter['match']);
    }

    public function endsWith($filter, $query)
    {
        return $query->where($filter['column'], 'like', '%'.$filter['query_1'], $filter['match']);
    }

    public function inThePast($filter, $query)
    {
        $begin = now();
        $end = now()->endOfDay();

        switch ($filter['query_2']) {
            case 'hours':
                $begin->subHours($filter['query_1']);
                break;
            case 'days':
                $begin->subDays($filter['query_1'])->startOfDay();
                break;
            case 'months':
                $begin->subMonths($filter['query_1'])->startOfDay();
                break;
            case 'years':
                $begin->subYears($filter['query_1'])->startOfDay();
                break;
            default:
                $begin->subDays($filter['query_1'])->startOfDay();
                break;
        }

        return $query->whereBetween($filter['column'], [$begin, $end], $filter['match']);
    }

    public function inTheNext($filter, $query)
    {
        $begin = now()->startOfDay();
        $end = now();

        switch ($filter['query_2']) {
            case 'hours':
                $end->addHours($filter['query_1']);
                break;
            case 'days':
                $end->addDays($filter['query_1'])->endOfDay();
                break;
            case 'months':
                $end->addMonths($filter['query_1'])->endOfDay();
                break;
            case 'years':
                $end->addYears($filter['query_1'])->endOfDay();
                break;
            default:
                $end->addDays($filter['query_1'])->endOfDay();
                break;
        }

        return $query->whereBetween($filter['column'], [$begin, $end], $filter['match']);
    }

    public function inThePeriod($filter, $query)
    {
        $begin = now();
        $end = now();

        switch ($filter['query_1']) {
            case 'today':
                $begin->startOfDay();
                $end->endOfDay();
                break;
            case 'yesterday':
                $begin->subDay(1)->startOfDay();
                $end->subDay(1)->endOfDay();
                break;
            case 'tomorrow':
                $begin->addDay(1)->startOfDay();
                $end->addDay(1)->endOfDay();
                break;
            case 'last_month':
                $begin->subMonth(1)->startOfMonth();
                $end->subMonth(1)->endOfMonth();
                break;
            case 'this_month':
                $begin->startOfMonth();
                $end->endOfMonth();
                break;
            case 'next_month':
                $begin->addMonth(1)->startOfMonth();
                $end->addMonth(1)->endOfMonth();
                break;
            case 'last_year':
                $begin->subYear(1)->startOfYear();
                $end->subYear(1)->endOfYear();
                break;
            case 'this_year':
                $begin->startOfYear();
                $end->endOfYear();
                break;
            case 'next_year':
                $begin->addYear(1)->startOfYear();
                $end->addYear(1)->endOfYear();
                break;
            default:
                break;
        }

        return $query->whereBetween($filter['column'], [$begin, $end], $filter['match']);
    }
}
