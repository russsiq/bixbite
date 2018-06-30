<?php

namespace BBCMS\Http\Requests;

use BBCMS\Http\Requests\Request;

class CommentsRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Check if unreg are allowed to make comments
        if (! auth()->check() and setting('comments.regonly')) {
            return false;
        }

        return true;
    }

    public function sanitize()
    {
        $input = $this->except(['_token', '_method', /*'created_at', 'deleted_at', 'updated_at',*/ 'submit']);
        if (! auth()->check()) {
            $input['name'] = teaser($input['name'], 255);
            $input['email'] = filter_var($input['email'], FILTER_SANITIZE_EMAIL, FILTER_FLAG_EMPTY_STRING_NULL);
            $input['captcha'] = filter_var($input['captcha'], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_EMPTY_STRING_NULL);
        } elseif ($user = auth()->user()) {
            $input['user_id'] = $user->id;
        }
        $input['content'] = preg_replace("/\<script.*?\<\/script\>/", '', $input['content']);

        // $content = $input['content'];
        // $content = preg_replace_callback(
        //     "/<code>(.*?)<\/code>/s",
        //     function ($match) {
        //         return '<code>' . html_secure($match[0]) . '</code>';
        //     },
        //     $content
        // );
        // $content = preg_replace("/\<script.*?\<\/script\>/", '', $content);
        // $input['content'] = $content;

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
            // default the value
            'parent_id' => $this->input('parent_id', null),
            // default the value from route
            'commentable_id' => $this->route('commentable_id'),
            'commentable_type' => string_slug($this->route('commentable_type'), '_')
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
            'name'              => [(auth()->check() ? 'sometimes' : 'required'), 'between:3,255', 'string', 'unique:users,name'], // to prevent guests to use the name users
            'email'             => [(auth()->check() ? 'sometimes' : 'required'), 'between:6,255', 'email', 'unique:users,email'], // to prevent guests to use the email users
            'captcha'           => [(auth()->check() ? 'sometimes' : 'required'), 'digits:4'], // to prevent guests to use the email users
            'content'           => ['required', 'string', 'between:4,500'],
            'parent_id'         => ['nullable', 'integer', 'exists:comments,id'],
            'commentable_type'  => ['bail', 'required', 'string'],
            'commentable_id'    => ['bail', 'required', 'integer'] //, 'exists:' . $this->commentable_type . ',id'], // save this position for _id after _type to Both validate
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
