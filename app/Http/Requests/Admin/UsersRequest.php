<?php

namespace BBCMS\Http\Requests\Admin;

use BBCMS\Models\User;
use BBCMS\Http\Requests\Request;

class UsersRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return false;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'users'         => 'required|array',
            'mass_action'   => 'required|string|in:activate,lock,delete,delete_inactive',
        ];
    }

    public function messages()
    {
        return [
            'users.*'       => 'Необходимо выбрать пользователей',
            'mass_action.*' => 'Необходимо выбрать действие',
        ];
    }
}
