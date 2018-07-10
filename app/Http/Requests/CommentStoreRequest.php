<?php

namespace BBCMS\Http\Requests;

use BBCMS\Http\Requests\Request;

class CommentStoreRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Check if unregistered user are allowed to commenting.
        if (setting('comments.regonly') and ! $this->user()) {
            return false;
        }

        return true;
    }

    public function sanitize()
    {
        $input = $this->except(['_token','_method','submit']);

        if($this->user()) {
            $input['user_id'] = $this->user()->id;
        } else {
            $input = array_merge($input, [
                'name' => teaser($this->input('name'), 255),
                'email' => filter_var($this->input('email'), FILTER_SANITIZE_EMAIL, FILTER_FLAG_EMPTY_STRING_NULL),
                'captcha' => filter_var($this->input('captcha'), FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_EMPTY_STRING_NULL),
            ]);
        }

        if (! setting('comments.use_html', false)) {
            $input['content'] = html_clean($input['content']);
        }

        $input['content'] = preg_replace_callback("/\<code\>(.+?)\<\/code\>/is",
            function ($match) {
                return '<pre>' . html_secure($match[1]) . '</pre>';
            }, $input['content']
        );
        $input['content'] = preg_replace("/\<script.*?\<\/script\>/", '', $input['content']);

        return $this->replace($input)->all();
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    protected function validationData()
    {
        return $this->merge([
            // Default value.
            'is_approved' => 'owner' == user('role') or ! setting('comments.moderate'),
            'parent_id' => $this->input('parent_id', null),
            // Default value from route.
            'commentable_id' => $this->route('commentable_id'),
            'commentable_type' => string_slug($this->route('commentable_type'), '_'),
            // Aditional default value.
            'user_ip' => $this->ip(),
        ])->all();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
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
                (auth()->check() ? 'sometimes' : 'required'),
                'between:3,255',
                'string',
                'unique:users,name',
            ],
            'email' => [
                (auth()->check() ? 'sometimes' : 'required'),
                'between:6,255',
                'email',
                'unique:users,email',
            ],
            'user_ip' => [
                'required',
            ],
            'captcha' => [
                (auth()->check() ? 'sometimes' : 'required'),
                'digits:4',
            ],
            'content' => [
                'required',
                'string',
                'between:4,1500',
            ],
        ];
    }

    /**
     * Set custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            // 'captcha' => 'верификационный код',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check captcha for unregistered visitors
            if (! auth()->check() and setting('system.captcha_used', true)) {
                if (md5($this->captcha) != session('captcha')) {
                    $validator->errors()->add('captcha', __('validation.captcha'));
                }
            }
        });

        return $validator;
    }
}
