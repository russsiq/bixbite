<?php

namespace App\Http\Requests\Api\V1\User;

// Сторонние зависимости.
use App\Http\Requests\BaseFormRequest;
use App\Models\Privilege;
use App\Models\User;
use Illuminate\Validation\Rule;

class UserRequest extends BaseFormRequest
{
    /**
     * Подготовить данные для валидации.
     * @return void
     */
    protected function prepareForValidation()
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

        $this->replace($input);
    }

    /**
     * Получить массив правил валидации,
     * которые будут применены к запросу.
     * @return array
     */
    public function rules(): array
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
                Rule::unique('users')->ignore($this->user),

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
