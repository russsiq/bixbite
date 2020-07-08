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
            // Отношения и другие поля с индексами.
            'user_id' => [
                'bail',
                'integer',
                'exists:users,id',

            ],

            'image_id' => [
                'nullable',
                'integer',
                'exists:files,id',

            ],

            'state' => [
                'required',
                'string',
                'in:published,unpublished,draft',

            ],

            // Основное содержимое.
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
                'alpha_dash',

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

            // Мета поля.
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

            'robots' => [
                'nullable',
                'string',
                'in:noindex,nofollow,none', // null - content="all"

            ],

            // Дополнительная информация.
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

            'allow_com' => [
                'required',
                'numeric',
                'in:0,1,2', // 0 - no; 1 - yes; 2 - by default

            ],

            // Счетчики.
            'views' => [
                'nullable',
                'integer',

            ],

            'shares' => [
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

            'created_at' => [
                // 'date_format:"Y-m-d H:i:s"',
                'date',

            ],

            'updated_at' => [
                // 'date_format:"Y-m-d H:i:s"',
                'date',

            ],

            // Отношения, которых тут быть не должно.
            'categories' => [
                'nullable',
                'array',

            ],

            'categories.*.id' => [
                'required',
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

            'tags.*.id' => [
                'required',
                'integer',
                'exists:tags,id',

            ],

            'tags.*.title' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\w-]+$/u',

            ],

        ];
    }
}
