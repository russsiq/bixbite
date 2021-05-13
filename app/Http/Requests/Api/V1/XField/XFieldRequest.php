<?php

namespace App\Http\Requests\Api\V1\XField;

// Сторонние зависимости.
use App\Http\Requests\BaseFormRequest;
use App\Models\XField;

class XFieldRequest extends BaseFormRequest
{
    /**
     * Подготовить данные для валидации.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $input = $this->except([
            '_token',
            '_method',
            'submit',

        ]);

        $this->replace($input);
    }

    /**
     * Получить пользовательские имена атрибутов
     * для формирования сообщений валидатора.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'extensible' => trans('extensible'),
            'name' => trans('name'),
            'type' => trans('type'),
            'params' => trans('params'),
            'params.*.key' => 'Ключ в Списке пар',
            'params.*.value' => 'Значение в Списке пар',
            'title' => trans('title'),
            'descr' => trans('descr'),
            'html_flags' => trans('html_flags'),

        ];
    }

    /**
     * Получить массив пользовательских строк перевода
     * для формирования сообщений валидатора.
     * @return array
     */
    public function messages(): array
    {
        return [
            'params.required_if' => trans('validation.params.required_if'),

        ];
    }

    /**
     * Получить массив правил валидации,
     * которые будут применены к запросу.
     * @return array
     */
    public function rules(): array
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

            'params.*.key' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[\w-]+$/u',

            ],

            'params.*.value' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\w\s\d\-\_\.]+$/u',

            ],

            'title' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\w\s\d\-\_\.\,\(\)]+$/u',

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
}
