<?php

namespace App\Http\Requests\Api\V1\Article;

use App\Http\Requests\Api\V1\Article\StoreArticleRequest;
use Illuminate\Validation\Rule;

class UpdateArticleRequest extends StoreArticleRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('articles')->ignore(
                    $this->route('article')
                ),
            ],
        ]);
    }
}
