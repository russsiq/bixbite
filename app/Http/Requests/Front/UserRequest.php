<?php

namespace BBCMS\Http\Requests\Front;

use BBCMS\Models\Privilege;
use BBCMS\Http\Requests\Request;

class UserRequest extends Request
{
    /**
     * Получить данные из запроса для валидации.
     *
     * @return array
     */
    public function validationData()
    {
        $input = $this->except([
            '_token',
            '_method',
            'submit',
        ]);

        if (empty($input['password'])) {
            unset($input['password']);
        }

        if (! empty($input['info'])) {
            $input['info'] = preg_replace("/\<script.*?\<\/script\>/", '', $input['info']);
        }

        if (! empty($input['where_from'])) {
            $input['where_from'] = preg_replace("/\<script.*?\<\/script\>/", '', $input['where_from']);
        }

        // Always mark as `null` to remove old avatar.
        if (! empty($input['delete_avatar'])) {
            $input['avatar'] = null;
        }

        // !!! ДОБАВИТЬ ВСЕ ПРОВЕРКИ И САНИТАЦИИ - ЭТО Э ПОЛЬЗОВАТЕЛИ !!!

        return $this->replace($input)
            ->all();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'between:3,255',
                'regex:/^[\w\s\.\,\-\_]+$/u',
            ],

            'email' => [
                'required',
                'email',
                'between:6,255',
                'unique:users,email' . (isset($this->user->id) ? ','.$this->user->id.',id' : ''),
            ],

            'password' => [
                'string',
                'between:6,255',
                'confirmed',
                isset($this->user->id) ? 'nullable' : 'required',
            ],

            'role' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                'in:'.implode(',', Privilege::getModel()->roles()),
            ],

            'where_from' => [
                'nullable',
                'string',
                'max:255',
                'alpha_num',
            ],

            'info' => [
                'nullable',
                'string',
                'max:500',
                'regex:/^[\w\s\.\,\-\_\?\!\r\n]+$/u',
            ],
            
            'avatar' => [
                'nullable',
                'image',
                'mimes:png,jpg,jpeg,gif,bmp',
                'max:800',
                'dimensions:max_width='.setting('users.avatar_max_width', 140).',max_height='.setting('users.avatar_max_height', 140),
            ],
        ];
    }
}
