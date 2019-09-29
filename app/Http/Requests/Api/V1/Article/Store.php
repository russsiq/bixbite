<?php

namespace BBCMS\Http\Requests\Api\V1\Article;

class Store extends ArticleRequest
{
    public function validationData()
    {
        $this->merge([
            'date_at' => 'currdate',
        ]);

        return parent::validationData();
    }
}
