<?php

namespace App\Http\Requests\Api\V1\Article;

use App\Rules\SqlTextLength;
use App\Rules\TitleRule;
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
            'slug' => Str::slug(
                $this->input('slug') ?: $this->input('title')
            ),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $metaRobotsRule = $this->container->make(MetaRobotsRule::class);
        $sqlTextLengthRule = $this->container->make(SqlTextLength::class);
        $titleRule = $this->container->make(TitleRule::class);

        return [
            // Не валидируем эти поля, оставляем управление
            // наблюдателям, контроллерам и другой нечисти.
            // 'user_id' => [],

            'title' => ['required', $titleRule],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('articles')],
            'teaser' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', $sqlTextLengthRule],

            'meta_description' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'meta_robots' => ['sometimes', 'string', 'max:255', $metaRobotsRule],

            'on_mainpage' => ['sometimes', 'boolean'],
            'is_favorite' => ['sometimes', 'boolean'],
            'is_pinned' => ['sometimes', 'boolean'],

            'views' => ['sometimes', 'integer'],

            'created_at' => ['sometimes', 'date'],
            'updated_at' => ['sometimes', 'date'],
        ];
    }
}
