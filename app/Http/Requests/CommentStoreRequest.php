<?php

namespace App\Http\Requests;

// Сторонние зависимости.
use App\Http\Requests\BaseFormRequest;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Support\Str;
use Russsiq\DomManipulator\Facades\DOMManipulator;

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
            $input['name'] = Str::teaser($this->input('name'));
            $input['email'] = filter_var($this->input('email'), FILTER_SANITIZE_EMAIL, FILTER_FLAG_EMPTY_STRING_NULL);
        }

        $input['content'] = $this->prepareContent($this->input('content'));

        $this->replace($input)
            ->merge([
                // Default value.
                'is_approved' => ($this->user() and $this->user()->hasRole('owner')) or ! setting('comments.moderate'),
                'parent_id' => $this->input('parent_id', null),

                // Default value from route.
                'commentable_id' => (int) $this->route('commentable_id'),
                'commentable_type' => Str::slug($this->route('commentable_type'), '_'),

                // Aditional default value.
                'user_ip' => $this->ip(),

            ]);
    }

    protected function prepareContent(string $content = null): string
    {
        if (is_null($content)) {
            return '';
        }

        $content = DOMManipulator::removeEmoji($content);

        $content = DOMManipulator::wrapAsDocument($content)
            ->revisionPreTag()
            ->remove('script');

        if (! setting('comments.use_html', false)) {
            $content = Str::cleanHTML($content);
        }

        return $content;
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
                !auth()->check() && config('g_recaptcha.used') ? 'g_recaptcha' : 'nullable',

            ],

            'content' => [
                'required',
                'string',
                'between:10,1500',

            ],

        ];
    }
}
