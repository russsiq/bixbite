<?php

namespace App\Actions\XField;

use App\Contracts\Actions\XField\UpdatesXField;
use App\Models\XField;

class UpdateXFieldAction extends XFieldActionAbstract implements UpdatesXField
{
    /**
     * Validate and update the given extra field.
     *
     * @param  XField  $x_field
     * @param  array  $input
     * @return XField
     */
    public function update(XField $x_field, array $input): XField
    {
        $this->authorize(
            'update', $this->x_field = $x_field
        );

        $this->x_field->update(
            $this->validate($input)
        );

        return $this->x_field;
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    protected function rules(): array
    {
        return array_merge(
            $this->paramsRules(),
            $this->titleRules(),
            $this->descrRules(),
            $this->htmlFlagsRules(),
        );
    }
}
