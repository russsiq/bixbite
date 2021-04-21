<?php

namespace App\Http\Requests\Api\V1\Article;

use App\Http\Requests\Api\V1\Article\StoreArticleRequest;
use App\Rules\MetaRobotsRule;
use App\Rules\SqlTextLength;
use App\Rules\TitleRule;

class UpdateArticleRequest extends StoreArticleRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(
        MetaRobotsRule $metaRobotsRule,
        SqlTextLength $sqlTextLengthRule,
        TitleRule $titleRule
    ): array {
        return array_merge(parent::rules(...func_get_args()), [
            //
        ]);
    }
}
