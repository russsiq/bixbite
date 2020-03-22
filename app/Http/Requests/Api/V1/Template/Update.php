<?php

namespace App\Http\Requests\Api\V1\Template;

class Update extends TemplateRequest
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
                // Шаблон должен существовать при обновлении содержимого.
                function ($attribute, $value, $fail) {
                    if (!$this->input('exists')) {
                        $fail(sprintf(trans('msg.not_exists'), $value));
                    }
                },

            ],

            'content' => [
                'required',
                'string',

            ],

        ];
    }
}
