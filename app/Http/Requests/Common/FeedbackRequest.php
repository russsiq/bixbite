<?php

namespace BBCMS\Http\Requests\Common;

use BBCMS\Http\Requests\Request;

class FeedbackRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function sanitize()
    {
        return $this->replace([
            'feedback_name' => teaser($this->input('feedback_name'), 100),
            'feedback_email' => html_secure($this->input('feedback_email')),
            'feedback_text' => nl2br(html_secure($this->input('feedback_text'))),
        ])->all();
    }

    public function rules()
    {
        return [
            'feedback_name' => ['required', 'string', 'max:100'],
            'feedback_email' => ['required', 'string', 'max:255'],
            'feedback_text' => ['required', 'string', 'max:1000', 'not_regex:/((https?:\/\/)|(www\.))([\d\w\.\-]+)\.([\w\.]+)/u'],
        ];
    }

    public function messages()
    {
        return [
            'feedback_name.required' => 'Вам необходимо представиться.',
            'feedback_email.required' => 'Укажите как с вами связаться.',
            'feedback_text.required' => 'Введите текст вашего сообщения, пожалуйста.',
            'feedback_text.not_regex' => 'Ссылки расцениваются как спам.',
        ];
    }

    public function attributes()
    {
        return [
            'feedback_name' => 'Имя',
            'feedback_email' => 'Email',
            'feedback_text' => 'Текст сообщения',
        ];
    }

}
