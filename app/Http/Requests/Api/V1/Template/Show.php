<?php

namespace BBCMS\Http\Requests\Api\V1\Template;

class Show extends TemplateRequest
{
    public function rules()
    {
        return [
            'filename' => [
                'required',
                'string',
                // Шаблон должен существовать при отображении содержимого.
                function ($attribute, $value, $fail) {
                    if (!$this->input('exists')) {
                        $fail(sprintf(__('msg.not_exists'), $value));
                    }
                },
            ],
        ];
    }
}
