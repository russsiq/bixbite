<?php

namespace BBCMS\Http\Requests\Front;

use BBCMS\Http\Requests\BaseFormRequest;

class FeedbackRequest extends BaseFormRequest
{
    /**
     * Получить данные из запроса для валидации.
     *
     * @return array
     */
    public function validationData()
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
            ],

            'g-recaptcha-response' => 'g_recaptcha',
        ];
    }

    public function messages()
    {
        $trans = trans('feedback.form.validation');

        return is_array($trans) ? $trans : [];
    }

    public function attributes()
    {
        $trans = trans('feedback.form.attributes');

        return is_array($trans) ? $trans : [];
    }
}
