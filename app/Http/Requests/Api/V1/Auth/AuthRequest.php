<?php

namespace App\Http\Requests\Api\V1\Auth;

// Сторонние зависимости.
use App\Http\Requests\BaseFormRequest;

class AuthRequest extends BaseFormRequest
{
    /**
     * Получить массив правил валидации,
     * которые будут применены к запросу.
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',

            ],

            'password' => [
                'required',
                'string',
                'between:6,25',

            ],

        ];
    }
}
