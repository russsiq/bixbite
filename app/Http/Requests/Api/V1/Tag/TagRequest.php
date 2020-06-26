<?php

namespace App\Http\Requests\Api\V1\Tag;

// Сторонние зависимости.
use App\Http\Requests\BaseFormRequest;
use Illuminate\Database\Eloquent\Relations\Relation;

class TagRequest extends BaseFormRequest
{
    /**
     * Получить массив правил валидации,
     * которые будут применены к запросу.
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => [
                'string',
                'max:255',
                'regex:/^[\w-]+$/u',

            ],

            'taggable_type' => [
                'nullable',
                'alpha_dash',
                'in:'.self::morphMap(),

            ],

            'taggable_id' => [
                'bail',
                'nullable',
                'integer',
                'required_with:taggable_type',
                'exists:'.$this->input('taggable_type').',id',

            ],

        ];
    }

    /**
     * Получить список из карты полиморфных отношений.
     * @return string
     */
    protected static function morphMap(): string
    {
        return implode(',', array_keys(Relation::morphMap()));
    }
}
