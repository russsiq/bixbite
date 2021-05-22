<?php

namespace App\Actions\XField;

use App\Contracts\Actions\XField\DeletesXField;
use App\Models\XField;

class DeleteXFieldAction extends XFieldActionAbstract implements DeletesXField
{
    /**
     * Delete the given extra field.
     *
     * @param  XField  $x_field
     * @return int  Remote extra field ID.
     */
    public function delete(XField $x_field): int
    {
        $this->authorize(
            'delete', $this->x_field = $x_field->fresh()
        );

        $id = $x_field->id;

        $x_field->delete();

        return $id;
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            //
        ];
    }
}
