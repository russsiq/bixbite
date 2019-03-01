<?php

namespace BBCMS\Http\Requests\Admin;

use BBCMS\Http\Requests\Request;

class NoteRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function sanitize()
    {
        $input = $this->except(['_token', '_method', 'submit']);

        return $this->replace($input)->all();
    }

    public function rules()
    {
        return [
            'title' => [
                'sometimes',
                'string',
                'max:225',
                'regex:/^[\w\s\.\,\-\_\?\!\(\)\[\]]+$/u',
                'unique:notes',
            ],
            'descr' => [
                'nullable',
                'string',
                'max:500',
            ],
            'is_completed' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}
