<?php

namespace App\Http\Requests\Api\V1\Article;

use App\Models\Article;
use App\Rules\MetaRobotsRule;
use App\Rules\SqlTextLength;
use App\Rules\TitleRule;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class StoreArticleRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
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
    public function rules(
        MetaRobotsRule $metaRobotsRule,
        SqlTextLength $sqlTextLengthRule,
        TitleRule $titleRule
    ): array {
        $article = $this->route('article', null);

        return [
            // Не валидируем эти поля, оставляем управление
            // наблюдателям, контроллерам и другой нечисти.
            // 'user_id' => [],

            'title' => ['bail', 'required', $titleRule],
            'slug' => [
                'bail',
                'required',
                'string',
                'max:255',
                'alpha_dash',
                with(
                    Rule::unique(Article::TABLE, 'slug'),
                    fn (Unique $unique) => $article instanceof Article
                        ? $unique->ignore($article->id, 'id')
                        : $unique
                ),
            ],
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

            // 'relationships' => ['present', 'array'],
            // 'relationships.categories' => ['sometimes', 'array'],
            // 'relationships.categories.data' => ['sometimes', 'array'],
            // 'relationships.categories.data.*.id' => ['bail', 'required', 'integer', 'numeric', 'distinct', 'min:1', 'exists:categories,id'],
        ];
    }
}
