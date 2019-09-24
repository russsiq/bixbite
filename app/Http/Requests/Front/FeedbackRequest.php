<?php

namespace BBCMS\Http\Requests\Front;

use BBCMS\Http\Requests\Request;

class FeedbackRequest extends Request
{
    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    protected function validationData()
    {
        return $this->merge([
                'name' => teaser($this->input('name'), 100),
                'contact' => html_secure($this->input('contact')),
                'content' => nl2br(html_secure($this->input('content'))),
                'politics' => $this->input('politics', false),
            ])
            ->all();
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
            ],

            'contact' => [
                'required',
                'string',
                'min:6',
                'max:255',
            ],

            'content' => [
                'required',
                'string',
                'min:25',
                'max:1000',
            ],

            'politics' => [
                'accepted',
            ],

            'file' => [
                'sometimes',
                'file',
                'max:51200',
                'mimetypes:application/zip',
            ]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => trans('feedback.validation.name.required'),
            'contact.required' => trans('feedback.validation.contact.required'),
            'content.required' => trans('feedback.validation.content.required'),
            'politics.accepted' => trans('feedback.validation.politics.accepted'),
            'file.max' => trans('feedback.validation.file.max'),
            'file.mimetypes' => trans('feedback.validation.file.mimetypes'),
        ];
    }

    public function attributes()
    {
        return [
            'name' => trans('feedback.form.name'),
            'contact' => trans('feedback.form.contact'),
            'content' => trans('feedback.form.content'),
            'politics' => trans('feedback.form.politics'),
            'file' => trans('feedback.form.file'),
        ];
    }
}
