<?php

namespace BBCMS\Http\Requests\Api\V1\Template;

class Update extends TemplateRequest
{
    public function rules()
    {
        return [
            'filename' => [
                'required',
                'string',
                // Шаблон должен существовать при обновлении содержимого.
                function ($attribute, $value, $fail) {
                    if (!$this->input('exists')) {
                        $fail(sprintf(__('msg.not_exists'), $value));
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
