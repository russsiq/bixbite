<?php

namespace App\Http\Requests\Api\V1\Article;

// Сторонние зависимости.
use App\Http\Requests\BaseFormRequest;

/**
 * @NB В связи с тем, что Записи идентифицируются по ID,
 * то нет необходимости в уникальности атрибутов `title` и `slug`.
 *
 * @NB Нежелательным является использование метода `route`,
 * так как пока непонятно, как это тестировать. И надо ли?
 */
class ArticleRequest extends BaseFormRequest
{
    /**
     * Общий массив допустимых значений для правила `in:список_значений`.
     * @var array
     */
    protected $allowedForInRule = [
        'date_at' => [
            'currdate',
            'customdate',

        ],

    ];

    /**
     * Получить пользовательские имена атрибутов
     * для формирования сообщений валидатора.
     * @return array
     */
    public function attributes(): array
    {
        return [
            'title' => trans('Title'),
            'slug' => trans('Slug'),
            'teaser' => trans('Teaser'),
            'categories.*' => trans('Category'),

        ];
    }

    /**
     * Получить массив пользовательских строк перевода
     * для формирования сообщений валидатора.
     * @return array
     */
    public function messages(): array
    {
        return [

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
            'user_id' => [
                'bail',
                'sometimes',
                'required',
                'integer',
                'exists:users,id',

            ],

            // Main content.
            'title' => [
                'bail',
                'required',
                'string',
                'max:255',

            ],

            'slug' => [
                'bail',
                'required',
                'string',
                'max:255',

            ],

            'teaser' => [
                'nullable',
                'string',
                'max:255',

            ],

            'content' => [
                'nullable',
                'string',

            ],

            'description' => [
                'nullable',
                'string',
                'max:255',

            ],

            'keywords' => [
                'nullable',
                'string',
                'max:255',

            ],

            // Flags ?
            'state' => [
                'required',
                'string',
                'in:published,unpublished,draft',

            ],

            'on_mainpage' => [
                'nullable',
                'boolean',

            ],

            'is_favorite' => [
                'nullable',
                'boolean',

            ],

            'is_pinned' => [
                'nullable',
                'boolean',

            ],

            'is_catpinned' => [
                'nullable',
                'boolean',

            ],

            // Extension.
            'allow_com' => [
                'required',
                'numeric',
                'in:0,1,2',

            ],

            'views' => [
                'nullable',
                'integer',

            ],

            'votes' => [
                'nullable',
                'integer',

            ],

            'rating' => [
                'nullable',
                'integer',

            ],

            // Relations types.
            'image_id' => [
                'nullable',
                'integer',
                'exists:files,id',

            ],

            'categories' => [
                'nullable',
                'array',

            ],

            'categories.*' => [
                'integer',
                'exists:categories,id',

            ],

            /*'files' => [
                'nullable',
                'array',
            ],

            'files.*' => [
                'integer',
                'exists:files,id',
            ],

            'images' => [
                'nullable',
                'array',
            ],

            'images.*' => [
                'integer',
                'exists:files,id',
            ],*/

            'tags' => [
                'nullable',
                'array',

            ],

            'tags.*' => [
                'string',
                'max:255',
                'regex:/^[\w-]+$/u',

            ],

            // Временные метки.
            'date_at' => [
                'nullable',
                'string',
                'in:'.$this->allowedForInRule('date_at'),

            ],

            'created_at' => [
                'nullable',
                'required_with:date_at',
                // 'date_format:"Y-m-d H:i:s"',

            ],

            'updated_at' => [
                'nullable',
                'required_without:date_at',
                // 'date_format:"Y-m-d H:i:s"',

            ],

        ];
    }
}
