<?php

namespace App\Actions\XField;

use App\Contracts\Actions\XField\FetchesXField;
use App\Models\XField;
use Illuminate\Contracts\Pagination\Paginator;

class FetchXFieldAction extends XFieldActionAbstract implements FetchesXField
{
    /**
     * Validate query parameters and return a specified extra field.
     *
     * @param  integer  $id
     * @param  array  $input
     * @return XField
     */
    public function fetch(int $id, array $input): XField
    {
        $this->x_field = XField::findOrFail($id);

        $this->authorize('view', $this->x_field);

        return $this->x_field;
    }

    /**
     * Validate query parameters and return a collection of extra fields.
     *
     * @param  array  $input
     * @return Paginator
     */
    public function fetchCollection(array $input): Paginator
    {
        $this->authorize('viewAny', XField::class);

        return XField::query()
            ->advancedFilter($input);
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    protected function rules(): array
    {
        return array_merge(
            //
        );
    }
}
