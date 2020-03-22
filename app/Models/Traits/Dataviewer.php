<?php

namespace App\Models\Traits;

use App\Models\Support\CustomQueryBuilder;

/**
* @source https://github.com/codekerala/Laravel-5.6-and-Vue.j-2-Dataviewer-Advanced-Filter
*/
trait Dataviewer
{
    public function scopeAdvancedFilter($query)
    {
        $data = $this->validateAdvancedFilter(request()->all());

        return $this->processAdvancedFilter($query, $data)
            ->orderBy($data['order_column'], $data['order_direction'])
            ->paginate($data['limit']);
            // ->appends(request()->only('f'))
    }

    protected function validateAdvancedFilter(array $request)
    {
        $request['order_column'] = $request['order_column'] ?? 'id';
        $request['order_direction'] = $request['order_direction'] ?? 'desc';
        $request['limit'] = $request['limit'] ?? 15;

        // // Debug.
        // dd($request);

        $validator = validator()->make($request, [
            'order_column' => 'sometimes|required|in:'.implode(',', $this->orderableColumns()),
            'order_direction' => 'sometimes|required|in:asc,desc',
            // min:1 to get first entity.
            'limit' => 'sometimes|required|integer|min:1',
            'page' => 'sometimes|required|integer|min:1',
            // Advanced filter.
            'filter_match' => 'sometimes|required|in:and,or',
            'f' => 'sometimes|required|array',
            'f.*.column' => 'required|in:'.implode(',', $this->allowedFilters()),
            'f.*.operator' => 'required_with:f.*.column|in:'.implode(',', $this->allowedFilterOperators()),
            'f.*.query_1' => 'required',
            'f.*.query_2' => 'required_if:f.*.operator,between,not_between',
        ]);

        return $validator->validate();
    }

    protected function processAdvancedFilter($query, $data)
    {
        return (new CustomQueryBuilder)->apply($query, $data);
    }

    public function orderableColumns()
    {
        return $this->orderableColumns;
    }

    public function allowedFilters()
    {
        return $this->allowedFilters;
    }

    public function allowedFilterOperators()
    {
        return [
            // Boolean.
            'boolean',

            // Relation count.
            'equal_to_count',
            'not_equal_to_count',
            'less_than_count',
            'greater_than_count',

            // Count.
            'equal_to',
            'not_equal_to',
            'less_than',
            'greater_than',
            'between',
            'not_between',

            // String.
            'contains',
            'starts_with',
            'ends_with',

            // Timestamps.
            'in_the_past',
            'in_the_next',
            'in_the_period',
        ];
    }
}
