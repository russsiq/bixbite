<?php

namespace BBCMS\Http\Requests\Api\V1\Article;

class Store extends ArticleRequest
{
    /**
     * Подготовить данные для валидации.
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'date_at' => 'currdate',

        ]);

        return parent::prepareForValidation();
    }
}
