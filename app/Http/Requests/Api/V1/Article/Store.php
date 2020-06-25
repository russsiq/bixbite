<?php

namespace App\Http\Requests\Api\V1\Article;

class Store extends ArticleRequest
{
    /**
     * Получить массив правил валидации,
     * которые будут применены к запросу.
     * @return array
     */
    public function rules(): array
    {
        $rules = parent::rules();

        // При создании Записи, не должно быть даты обновления.
        unset($rules['updated_at']);

        return array_merge($rules, [
            // При создании Записи, обязательно должен быть указан пользователь.
            'user_id' => array_merge([
                'required',

            ], $rules['user_id']),

            // При создании Записи, обязательно должна быть указана дата создания.
            'created_at' => array_merge([
                'required',

            ], $rules['created_at']),

        ]);
    }
}
