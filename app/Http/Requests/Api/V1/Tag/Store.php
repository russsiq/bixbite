<?php

namespace App\Http\Requests\Api\V1\Tag;

class Store extends TagRequest
{
    /**
     * Получить массив правил валидации,
     * которые будут применены к запросу.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = parent::rules();

        return array_merge($rules, [
            // При создании Тега, проверяем на уникальность.
            'title' => array_merge([
                'unique:tags,title',

            ], $rules['title']),

            'slug' => array_merge([
                'unique:tags,slug',

            ], $rules['slug']),
        ]);
    }
}
