<?php

namespace BBCMS\Http\Requests\Api\V1\Article;

// Сторонние зависимости.
use BBCMS\Http\Requests\BaseFormRequest;

class MassUpdate extends BaseFormRequest
{
    /**
     * Общий массив допустимых значений для правила `in:список_значений`.
     * @var array
     */
    protected $allowedForInRule = [
        'mass_action' => [
            'published',
            'unpublished',
            'draft',
            'on_mainpage',
            'allow_com',
            'currdate',
            'is_favorite',
            'is_catpinned',

        ],

    ];

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
                'integer',

            ],

            'mass_action' => [
                'required',
                'string',
                'in:'.$this->allowedForInRule('mass_action'),

            ],

        ];
    }
}
