<?php

namespace App\Actions\XField;

use App\Contracts\Actions\XField\CreatesXField;
use App\Models\XField;
use Illuminate\Contracts\Validation\Validator;

class CreateXFieldAction extends XFieldActionAbstract implements CreatesXField
{
    /**
     * Validate and create a newly extra field.
     *
     * @param  array  $input
     * @return XField
     */
    public function create(array $input): XField
    {
        $this->authorize('create', XField::class);

        $this->x_field = XField::create(
            $this->validate($input)
        )->fresh();

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
            $this->extensibleRules(),
            $this->nameRules(),
            $this->typeRules(),
            $this->paramsRules(),
            $this->paramsKeyRules(),
            $this->paramsValueRules(),
            $this->titleRules(),
            $this->descrRules(),
            $this->htmlFlagsRules(),
        );
    }

    /**
     * Configure the validator instance.
     *
     * @param  Validator  $validator
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (! $validator->errors()->has('extensible')
                && ! $validator->errors()->has('name')) {

                $wheres = collect($validator->attributes())
                    ->only(['extensible', 'name'])->toArray();

                $wheres['name'] = XField::getModel()
                    ->normalizeNameAttributePrefix($wheres['name']);

                $validator->errors()
                    ->addIf(
                        XField::query()->where($wheres)->count(),
                        'name', $this->translate('Column [:name] in table [:table] already exists.', [
                            'name' => $wheres['name'],
                            'table' => $wheres['extensible'],
                        ])
                    );
            }
        });
    }
}
