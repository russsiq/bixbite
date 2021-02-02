<?php

namespace App\Http\Requests\Api\V1\Article;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreArticleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // Не валидируем эти поля, оставляем управление
            // наблюдателям, контроллерам и другой нечисти.
            // 'user_id' => [],

            'title' => ['required', 'string', 'max:255'],
            'slug' => ['sometimes', 'string', 'max:255', 'alpha_dash', Rule::unique('articles')/*->ignore($this->id)*/,],
            'teaser' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string', 'max:255'],

            'meta_description' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'meta_robots' => ['nullable', 'string', 'max:255', 'in:all,noindex,nofollow,none'],

            'on_mainpage' => ['nullable', 'boolean'],
            'is_favorite' => ['nullable', 'boolean'],
            'is_pinned' => ['nullable', 'boolean'],
        ];
    }
}
