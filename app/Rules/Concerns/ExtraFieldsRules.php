<?php

namespace App\Rules\Concerns;

use App\Models\Contracts\ExtensibleContract;
use Illuminate\Validation\Rule;

trait ExtraFieldsRules
{
    /**
     * Get the validation rules used to validate `x_*` fields.
     *
     * @param  ExtensibleContract  $extensible
     * @return array
     */
    protected function extraFieldsRules(ExtensibleContract $extensible): array
    {
        $extraFields = [];

        foreach ($extensible->x_fields as $field) {
            $rules = [
                'bail',
            ];

            array_push(
                $rules,
                $field->html_flags->contains('key', 'required')
                    ? 'required'
                    : 'nullable'
            );

            switch ($field->type) {
                case 'integer':
                    array_push($rules, 'integer');
                    break;

                case 'boolean':
                    array_push($rules, 'boolean');
                    break;

                case 'string':
                    array_push($rules, 'string', 'max:255');
                    break;

                case 'array':
                    array_push($rules, 'string', Rule::in(
                        array_column($field->params, 'key')
                    ));
                    break;

                case 'text':
                    array_push($rules, 'string');
                    break;

                case 'timestamp':
                    array_push($rules, 'date');
                    break;

                default:
                    array_push($rules, $field->type);
                    break;
            }

            $field->html_flags->map(function ($attribute) use (&$rules) {
                if ('min' === $attribute['key']) {
                    array_push($rules, 'min:'.$attribute['value']);
                }
            });

            $extraFields[$field->name] = $rules;

            $this->customAttributes[$field->name] = $field->title;
        }

        return $extraFields;
    }
}
