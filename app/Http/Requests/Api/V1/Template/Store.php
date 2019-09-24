<?php

namespace BBCMS\Http\Requests\Api\V1\Template;

class Store extends TemplateRequest
{
    public function rules()
    {
        return [
            'filename' => [
                'required',
                'string',
                // Шаблон с таким же именем не должен существовать при создании.
                function ($attribute, $value, $fail) {
                    if ($this->input('exists')) {
                        $fail(sprintf(__('msg.already_exists'), $value));
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
