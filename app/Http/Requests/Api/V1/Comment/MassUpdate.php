<?php

namespace App\Http\Requests\Api\V1\Comment;

// Сторонние зависимости.
use App\Http\Requests\BaseFormRequest;

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
            'is_approved',
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
            'comments.*' => trans('msg.validate.comments'),
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
            'comments' => [
                'required',
                'array',

            ],

            'comments.*' => [
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
