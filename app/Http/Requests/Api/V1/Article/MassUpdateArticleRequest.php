<?php

namespace App\Http\Requests\Api\V1\Article;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MassUpdateArticleRequest extends FormRequest
{
    /**
     * Получить массив пользовательских строк перевода
     * для формирования сообщений валидатора.
     * @return array
     */
    public function messages(): array
    {
        return [
            'articles.*' => trans('msg.validate.articles'),
            'mass_action.*' => trans('msg.validate.mass_action'),

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
            'articles' => [
                'required',
                'array',
            ],

            'articles.*' => [
                'required',
                'integer',
            ],

            'mass_action' => [
                'required',
                'string',
                Rule::in([
                    'published',
                    'unpublished',
                    'draft',
                    'on_mainpage',
                    'allow_com',
                    'currdate',
                    'is_favorite',
                    'is_catpinned',
                ]),
            ],
        ];
    }
}
