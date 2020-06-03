<?php

namespace App\Http\Requests\Api\V1\Article;

// Сторонние зависимости.
use Illuminate\Support\Facades\Auth;

class Store extends ArticleRequest
{
    /**
     * Подготовить данные для валидации.
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'date_at' => 'currdate',
            // Не доверяя пользователю,
            // выбираем его идентификатор
            // из фасада аутентификации.
            'user_id' => Auth::id(),

        ]);

        parent::prepareForValidation();
    }
}
