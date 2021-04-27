<?php

namespace App\Http\Requests\Api\V1\Article;

class Update extends ArticleRequest
{
    /**
     * Получить массив правил валидации,
     * которые будут применены к запросу.
     * @return array
     */
    public function rules(): array
    {
        $rules = parent::rules();

        // При обновлении Записи, не должно быть пользователя.
        unset($rules['user_id']);

        return array_merge($rules, [
            'created_at' => array_merge([
                'nullable',

            ], $rules['created_at']),

            // При обновлении Записи, дата создания может быть и не указана.
            'updated_at' => array_merge([
                'nullable',

            ], $rules['updated_at']),

        ]);
    }
}
