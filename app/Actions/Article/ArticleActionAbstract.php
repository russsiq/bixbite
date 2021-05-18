<?php

namespace App\Actions\Article;

use App\Actions\ActionAbstract;
use App\Models\Article;
use App\Rules\MetaRobotsRule;
use App\Rules\SqlTextLengthRule;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

abstract class ArticleActionAbstract extends ActionAbstract
{
    protected ?Article $article = null;

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    abstract protected function rules(): array;

    protected function relationshipsRules()
    {
        return [
            // Отношения, которых тут быть не должно.
            'categories' => [
                'nullable',
                'array',

            ],

            'categories.*.id' => [
                'required',
                'integer',
                'exists:categories,id',

            ],

            /*'attachments' => [
                'nullable',
                'array',
            ],

            'attachments.*' => [
                'integer',
                'exists:attachments,id',
            ],

            'images' => [
                'nullable',
                'array',
            ],

            'images.*' => [
                'integer',
                'exists:attachments,id',
            ],*/

            'tags' => [
                'nullable',
                'array',

            ],

            'tags.*.id' => [
                'required',
                'integer',
                'exists:tags,id',

            ],

            'tags.*.title' => [
                'bail',
                'required',
                'string',
                'max:255',
                'regex:/^[0-9\w\s]+$/u',
            ],

            'tags.*.slug' => [
                'bail',
                'required',
                'string',
                'max:255',
                'alpha_dash',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `user_id` field.
     *
     * @return array
     */
    protected function userIdRules(): array
    {
        return [
            'user_id' => [
                'bail',
                'required',
                'integer',
                'min:1',
                'exists:users,id',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `image_id` field.
     *
     * @return array
     */
    protected function imageIdRules(): array
    {
        return [
            'image_id' => [
                'bail',
                'nullable',
                'integer',
                'min:1',
                'exists:attachments,id',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `state` field.
     *
     * @return array
     */
    protected function stateRules(): array
    {
        return [
            'state' => [
                'required',
                'integer',
                'in:0,1,2',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `title` field.
     *
     * @return array
     */
    protected function titleRules(): array
    {
        return [
            'title' => [
                'bail',
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `slug` field.
     *
     * @return array
     */
    protected function slugRules(): array
    {
        return [
            'slug' => [
                'bail',
                'required',
                'string',
                'max:255',
                'alpha_dash',
                with(
                    Rule::unique(Article::TABLE, 'slug'),
                    fn (Unique $unique) => $this->article instanceof Article
                        ? $unique->ignore($this->article->id, 'id')
                        : $unique
                ),
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `teaser` field.
     *
     * @return array
     */
    protected function teaserRules(): array
    {
        return [
            'teaser' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `content` field.
     *
     * @return array
     */
    protected function contentRules(): array
    {
        return [
            'content' => [
                'nullable',
                'string',
                $this->container->make(SqlTextLengthRule::class),
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `meta_description` field.
     *
     * @return array
     */
    protected function metaDescriptionRules(): array
    {
        return [
            'meta_description' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `meta_keywords` field.
     *
     * @return array
     */
    protected function metaKeywordsRules(): array
    {
        return [
            'meta_keywords' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `meta_robots` field.
     *
     * @return array
     */
    protected function metaRobotsRules(): array
    {
        return [
            'meta_robots' => $this->container->make(MetaRobotsRule::class),
        ];
    }

    /**
     * Get the validation rules used to validate `on_mainpage` field.
     *
     * @return array
     */
    protected function onMainpageRules(): array
    {
        return [
            'on_mainpage' => [
                'boolean',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `is_favorite` field.
     *
     * @return array
     */
    protected function isFavoriteRules(): array
    {
        return [
            'is_favorite' => [
                'boolean',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `is_pinned` field.
     *
     * @return array
     */
    protected function isPinnedRules(): array
    {
        return [
            'is_pinned' => [
                'boolean',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `is_catpinned` field.
     *
     * @return array
     */
    protected function isCatpinnedRules(): array
    {
        return [
            'is_catpinned' => [
                'boolean',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `allow_com` field.
     *
     * @return array
     */
    protected function allowedCommentsRules(): array
    {
        return [
            'allow_com' => [
                'required',
                'numeric',
                'in:0,1,2', // {0: deny; 1: allow; 2: by default}
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `views` field.
     *
     * @return array
     */
    protected function viewsRules(): array
    {
        return [
            'views' => [
                'nullable',
                'integer',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `published_at` field.
     *
     * @return array
     */
    protected function publishedAtRules(): array
    {
        return [
            'published_at' => [
                'nullable',
                'date',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `created_at` field.
     *
     * @return array
     */
    protected function createdAtRules(): array
    {
        return [
            'created_at' => [
                'nullable',
                'date',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `updated_at` field.
     *
     * @return array
     */
    protected function updatedAtRules(): array
    {
        return [
            'updated_at' => [
                'nullable',
                'date',
            ],
        ];
    }
}
