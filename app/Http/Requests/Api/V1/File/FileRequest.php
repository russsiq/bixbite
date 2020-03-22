<?php

namespace App\Http\Requests\Api\V1\File;

// Сторонние зависимости.
use App\Http\Requests\BaseFormRequest;
use Illuminate\Database\Eloquent\Relations\Relation;

class FileRequest extends BaseFormRequest
{
    /**
     * Получить массив пользовательских строк перевода
     * для формирования сообщений валидатора.
     * @return array
     */
    public function messages(): array
    {
        return [
            'file.dimensions' => sprintf(
                trans('validation.dimensions_large'),
                $this->input('properties.width'),
                $this->input('properties.height')
            ),

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
