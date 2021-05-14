<?php

namespace App\Actions\Article;

use App\Contracts\Actions\Article\MassUpdatesArticle;
use App\Models\Article;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Validation\Rule;

class MassUpdateArticleAction extends ArticleActionAbstract implements MassUpdatesArticle
{
    /**
     * Validate and mass update specific articles.
     *
     * @param  array  $input
     * @return EloquentCollection
     */
    public function massUpdate(array $input): EloquentCollection
    {
        $this->authorize('massUpdate', Article::class);

        $validated = $this->validate($input);

        ['mass_action' => $attribute, 'articles' => $ids] = $validated;

        $query = Article::whereIn('id', $ids);

        switch ($attribute) {
            case 'draft':
                $query->update([
                    'state' => 0,
                ]);
                break;
            case 'published':
                $query->whereHas('categories')
                    ->update([
                        'state' => 1,
                    ]);
                break;
            case 'unpublished':
                $query->update([
                    'state' => 2,
                ]);
                break;
            case 'on_mainpage':
            case 'allow_com':
            case 'is_favorite':
            case 'is_catpinned':
                $article = $query->firstOrFail($attribute);
                $query->update([
                    $attribute => ! $article->{$attribute},
                ]);
                break;
            case 'currdate':
                $query->timestamps = false;
                $query->update([
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => null,
                ]);
                $query->timestamps = true;
                break;
        }

        // No need to load relationships.

        return $query->get();
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    protected function messages(): array
    {
        return [
            // 'articles.*' => trans('msg.validate.articles'),
            // 'mass_action.*' => trans('msg.validate.mass_action'),
        ];
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    protected function rules(): array
    {

        // `ids` `attribute` `present_value` `desired_value`

        return [
            'articles' => [
                'required',
                'array',
            ],

            'articles.*' => [
                'required',
                'integer',
            ],

            'mass_action' => [
                'required',
                'string',
                Rule::in([
                    'published',
                    'unpublished',
                    'draft',
                    'on_mainpage',
                    'allow_com',
                    'currdate',
                    'is_favorite',
                    'is_catpinned',
                ]),
            ],
        ];
    }
}
