<?php

namespace BBCMS\Http\Requests\Api\V1\Template;

class Store extends TemplateRequest
{
    /**
     * Получить массив правил валидации,
     * которые будут применены к запросу.
     * @return array
     */
    public function rules(): array
    {
        return [
            'filename' => [
                'required',
                'string',
                // Шаблон с таким же именем не должен существовать при создании.
                function ($attribute, $value, $fail) {
                    if ($this->input('exists')) {
                        $fail(sprintf(trans('msg.already_exists'), $value));
                    }
                },

            ],

            'content' => [
                'nullable',
                'string',

            ],

        ];
    }
}
