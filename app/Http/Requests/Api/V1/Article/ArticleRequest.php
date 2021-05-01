<?php

namespace App\Http\Requests\Api\V1\Article;

// Сторонние зависимости.
use App\Http\Requests\BaseFormRequest;
use App\Models\XField;
use App\Rules\MetaRobotsRule;
use Illuminate\Validation\Rule;

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
     *
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
     *
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
     *
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
     *
     * @return array
     */
    public function rules(): array
    {
        $extensibles = [];

        foreach (XField::fields('articles') as $field) {
            $rule = $field->type;

            if (in_array($rule, ['array', 'text'])) {
                $rule = 'string';
            } elseif ('timestamp' === $rule) {
                $rule = 'date';
            }

            $extensibles[$field->name] = [
                'nullable',
                $rule,

            ];
        }

        return array_merge($extensibles, [
            // Отношения и другие поля с индексами.
            'user_id' => [
                'bail',
                'integer',
                'exists:users,id',

            ],

            'image_id' => [
                'nullable',
                'integer',
                'min:1',
                'exists:attachments,id',

            ],

            'state' => [
                'required',
                'integer',
                'in:0,1,2',

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
                'nullable', // null - content="all"
                'string',
                Rule::in(MetaRobotsRule::DIRECTIVES),

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

            /*'attachments' => [
                'nullable',
                'array',
            ],

            'attachments.*' => [
                'integer',
                'exists:attachments,id',
            ],

            'images' => [
                'nullable',
                'array',
            ],

            'images.*' => [
                'integer',
                'exists:attachments,id',
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
                'bail',
                'required',
                'string',
                'max:255',
                'regex:/^[0-9\w\s]+$/u',
            ],

            'tags.*.slug' => [
                'bail',
                'required',
                'string',
                'max:255',
                'alpha_dash',
            ],

        ]);
    }
}
