<?php

namespace BBCMS\Http\Requests\Common;

// Сторонние зависимости.
use BBCMS\Http\Requests\BaseFormRequest;

class ToggleRequest extends BaseFormRequest
{
    /**
     * Подготовить данные для валидации.
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->replace([
            'id' => (int) $this->route('id'),
            'model' => ucfirst($this->route('model')),
            'attribute' => $this->route('attribute'),

        ]);
    }

    /**
     * Получить массив правил валидации,
     * которые будут применены к запросу.
     * @return array
     */
    public function rules(): array
    {
        return [
            'id' => [
                'bail',
                'required',
                'integer',

            ],

            'model' => [
                'bail',
                'required',
                'string',
                'max:125',
                'regex:/^[a-zA-Z_]+$/u',

            ],

            'attribute' => [
                'bail',
                'required',
                'string',
                'max:125',
                'regex:/^[a-z_]+$/u',

            ],

        ];
    }
}
