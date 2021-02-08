<?php

namespace App\Http\Requests\Api\V1\Article;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreArticleRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->slug ?: $this->title),
        ]);
    }

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
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('articles')],
            'teaser' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string', 'max:255'],

            'meta_description' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'meta_robots' => ['nullable', 'string', 'max:255', 'in:all,noindex,nofollow,none'],

            'on_mainpage' => ['nullable', 'boolean'],
            'is_favorite' => ['nullable', 'boolean'],
            'is_pinned' => ['nullable', 'boolean'],

            'views' => ['sometimes', 'integer'],
        ];
    }
}
