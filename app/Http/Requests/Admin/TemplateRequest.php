<?php

namespace BBCMS\Http\Requests\Admin;

use BBCMS\Http\Requests\Request;

class TemplateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasRole('owner');
    }

    public function rules()
    {
        return [
            'template' => [
                'required',
                'string',
            ]
        ];
    }

    public function attributes()
    {
        return [
            'template' => __('template')
        ];
    }
}
