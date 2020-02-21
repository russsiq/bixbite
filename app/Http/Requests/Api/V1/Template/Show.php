<?php

namespace BBCMS\Http\Requests\Api\V1\Template;

class Show extends TemplateRequest
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
                // Шаблон должен существовать при отображении содержимого.
                function ($attribute, $value, $fail) {
                    if (!$this->input('exists')) {
                        $fail(sprintf(trans('msg.not_exists'), $value));
                    }
                },

            ],

        ];
    }
}
