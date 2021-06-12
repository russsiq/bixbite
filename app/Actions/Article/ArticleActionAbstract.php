<?php

namespace App\Actions\Article;

use App\Actions\ActionAbstract;
use App\Models\Article;
use App\Models\Attachment;
use App\Rules\MetaRobotsRule;
use App\Rules\SqlTextLengthRule;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use Russsiq\DomManipulator\Facades\DOMManipulator;

abstract class ArticleActionAbstract extends ActionAbstract
{
    protected ?Article $article = null;

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    abstract protected function rules(): array;

    /**
     * Prepare the data for validation.
     *
     * @param  array  $input
     * @return array
     */
    protected function prepareForValidation(array $input): array
    {
        $input['user_id'] = $this->user() ? $this->user()->getAuthIdentifier() : null;
        $input['image_id'] = $input['image_id'] ?? null;

        if (empty($input['categories']) || empty($input['state'])) {
            $input['state'] = Article::STATE['draft'];
        }

        $input['title'] = Str::teaser($input['title'] ?? null, 255, '');

        // if (! setting('articles.manual_slug', false)) {
            // if (empty($input['slug']) && ! empty($input['title'])) {
                $input['slug'] = Str::slug(
                    $input['title'], '-', setting('system.translite_code', 'ru__gost_2000_b')
                );
            // }
        // }

        $input['teaser'] = Str::teaser($input['teaser'] ?? null, 255, '');
        $input['content'] = $this->prepareContent($input['content'] ?? null);
        $input['meta_description'] = Str::teaser($input['meta_description'] ?? null, 255, '');
        $input['meta_keywords'] = Str::teaser($input['meta_keywords'] ?? null, 255, '');
        $input['meta_robots'] = $input['meta_robots'] ?? 'all';
        $input['on_mainpage'] = $input['on_mainpage'] ?? true;
        $input['is_favorite'] = $input['is_favorite'] ?? false;
        $input['is_pinned'] = $input['is_pinned'] ?? false;
        $input['is_catpinned'] = $input['is_catpinned'] ?? false;
        $input['allow_com'] = $input['allow_com'] ?? Article::COMMENTS_ALLOWS['by_default'];
        $input['views'] = $input['views'] ?? 0;

        return $input;
    }

    /**
     * Prepare the content for validation.
     *
     * @param  string|null  $content
     * @return string
     */
    protected function prepareContent(?string $content): string
    {
        if (is_null($content)) {
            return (string) $content;
        }

        $content = DOMManipulator::removeEmoji($content);

        return DOMManipulator::wrapAsDocument($content)
            ->revisionPreTag()
            ->remove('script');
    }

    /**
     * Get the validation rules used to validate `user_id` field.
     *
     * @return array
     */
    protected function userIdRules(): array
    {
        if ($this->article instanceof Article) {
            return [];
        }

        return [
            'user_id' => [
                'bail',
                'required',
                'integer',
                'min:1',
                "size:{$this->user()->getAuthIdentifier()}",
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
        if (is_null($this->article)) {
            return [];
        }

        return [
            'image_id' => [
                'bail',
                'sometimes',
                'nullable',
                'integer',
                'min:1',
                Rule::exists(Attachment::TABLE, 'id')
                    ->where('type', 'image')
                    ->where('attachable_id', $this->article->id)
                    ->where('attachable_type', Article::TABLE),
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
                Rule::in(array_values(Article::STATE)),
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
