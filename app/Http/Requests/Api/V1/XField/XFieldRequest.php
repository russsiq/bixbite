<?php

namespace BBCMS\Http\Requests\Api\V1\XField;

use BBCMS\Models\XField;
use BBCMS\Http\Requests\BaseFormRequest;

use Illuminate\Validation\Rule;

class XFieldRequest extends BaseFormRequest
{
    /**
     * Получить данные из запроса для валидации.
     *
     * @return array
     */
    public function validationData()
    {
        $input = $this->except([
            '_token',
            '_method',
            'submit',
        ]);

        return $this->replace($input)
            ->merge([
                //
            ])
            ->all();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'extensible' => [
                'required',
                'string',
                'in:'.implode(',', XField::extensibles()),
            ],

            'name' => [
                'required',
                'string',
                'regex:/^[a-z_]+$/',
            ],

            'type' => [
                'required',
                'string',
                'in:'.implode(',', XField::fieldTypes()),
            ],

            'params' => [
                'nullable',
                'array',
                'required_if:type,array',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\w\s\d\-\_\.]+$/u',
            ],

            'descr' => [
                'nullable',
                'string',
                'max:500',
            ],

            'html_flags' => [
                'nullable',
                'string',
                'max:500',
            ],
        ];
    }

    public function attributes()
    {
        return [
           'extensible' => __('extensible'),
           'name' => __('name'),
           'type' => __('type'),
           'params' => __('params'),
           'title' => __('title'),
           'descr' => __('descr'),
           'html_flags' => __('html_flags'),
       ];
    }

    public function messages()
    {
        return [
            'params.required_if' => __('validation.params.required_if'),
        ];
    }
}
