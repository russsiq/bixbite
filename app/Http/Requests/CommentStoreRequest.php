<?php

namespace App\Http\Requests;

// Сторонние зависимости.
use App\Http\Requests\BaseFormRequest;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Support\Str;

class CommentStoreRequest extends BaseFormRequest
{
    /**
     * Подготовить данные для валидации.
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $input = $this->except(['
            _token',
            '_method',
            'submit',

        ]);

        if ($this->user()) {
            $input['user_id'] = $this->user()->id;
        } else {
            $input['name'] = teaser($this->input('name'), 255);
            $input['email'] = filter_var($this->input('email'), FILTER_SANITIZE_EMAIL, FILTER_FLAG_EMPTY_STRING_NULL);
        }

        $input['content'] = preg_replace_callback(
            "/\<code\>(.+?)\<\/code\>/is",
            function ($match) {
                return '<pre>'.html_secure($match[1]).'</pre>';
            },
            $this->input('content')
        );

        $input['content'] = preg_replace("/\<script.*?\<\/script\>/", '', $input['content']);

        if (! setting('comments.use_html', false)) {
            $input['content'] = Str::cleanHTML($input['content']);
        }

        $this->replace($input)
            ->merge([
                // Default value.
                'is_approved' => ($this->user() and $this->user()->hasRole('owner')) or ! setting('comments.moderate'),
                'parent_id' => $this->input('parent_id', null),

                // Default value from route.
                'commentable_id' => (int) $this->route('commentable_id'),
                'commentable_type' => string_slug($this->route('commentable_type'), '_'),

                // Aditional default value.
                'user_ip' => $this->ip(),

            ]);
    }

    /**
     * Получить пользовательские имена атрибутов
     * для формирования сообщений валидатора.
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name' => trans('auth.name'),
            'email' => trans('auth.email'),
            'content' => trans('comments.content'),

        ];
    }

    /**
     * Получить массив правил валидации,
     * которые будут применены к запросу.
     * @return array
     */
    public function rules(): array
    {
        return [
            'is_approved' => [
                'required',
                'boolean',

            ],

            'parent_id' => [
                'nullable',
                'integer',
                'exists:comments,id',

            ],

            'user_id' => [
                'sometimes',
                'integer',
                'exists:users,id',

            ],

            'commentable_type' => [
                'bail',
                'required',
                'string',

            ],

            'commentable_id' => [
                'bail',
                'required',
                'integer',
                'exists:'.$this->commentable_type.',id',

            ],

            // To prevent guests to use the email and name of users.
            'name' => [
                'bail',
                'required_without:user_id',
                'between:3,255',
                'string',
                'unique:users,name',

            ],

            'email' => [
                'bail',
                'required_without:user_id',
                'between:6,255',
                'email',
                'unique:users,email',

            ],

            'user_ip' => [
                'required',

            ],

            // 'captcha' => [
            //     (auth()->check() ? 'sometimes' : 'required'),
            //     'digits:4',
            //
            // ],

            'g-recaptcha-response' => [
                'bail',
                (! auth()->check() && config('g_recaptcha.used') ? 'required' : 'nullable'),
                'string',
                (! auth()->check() && config('g_recaptcha.used') ? 'g_recaptcha' : 'nullable'),

            ],

            'content' => [
                'required',
                'string',
                'between:10,1500',

            ],

        ];
    }
}
