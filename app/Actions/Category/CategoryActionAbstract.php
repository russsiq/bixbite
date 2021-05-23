<?php

namespace App\Actions\Category;

use App\Actions\ActionAbstract;
use App\Models\Attachment;
use App\Models\Category;
use App\Rules\MetaRobotsRule;
use App\Rules\SqlTextLengthRule;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Validation\Rules\Unique;
use Russsiq\DomManipulator\Facades\DOMManipulator;

abstract class CategoryActionAbstract extends ActionAbstract
{
    protected ?Category $category = null;

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
        $input['image_id'] = $input['image_id'] ?? null;

        if (empty($input['parent_id'])) {
            unset($input['parent_id']);
        }

        $input['title'] = Str::teaser($input['title'] ?? null, 255, '');

        if (empty($input['slug']) && ! empty($input['title'])) {
            $input['slug'] = Str::slug(
                $input['title'], '-', setting('system.translite_code', 'ru__gost_2000_b')
            );
        }

        $input['alt_url'] = empty($input['alt_url'])
            ? null
            : filter_var($input['alt_url'], FILTER_SANITIZE_URL, FILTER_FLAG_EMPTY_STRING_NULL);

        $input['info'] = $this->prepareContent($input['info'] ?? null);
        $input['meta_description'] = Str::teaser($input['meta_description'] ?? null, 255, '');
        $input['meta_keywords'] = Str::teaser($input['meta_keywords'] ?? null, 255, '');

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
     * Get the validation rules used to validate `image_id` field.
     *
     * @return array
     */
    protected function imageIdRules(): array
    {
        return [
            'image_id' => [
                'bail',
                'sometimes',
                'nullable',
                'integer',
                'min:1',
                Rule::exists(Attachment::TABLE, 'id'),
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `parent_id` field.
     *
     * @return array
     */
    protected function parentIdRules(): array
    {
        return [
            'parent_id' => [
                'bail',
                'sometimes',
                'integer',
                'min:1',
                with(
                    Rule::exists(Category::TABLE, 'id'),
                    fn (Exists $exists) => $this->category instanceof Category
                        ? $exists->whereNot('id', $this->category->id)
                        : $exists
                ),
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `position` field.
     *
     * @return array
     */
    protected function positionRules(): array
    {
        return [
            'position' => [
                'bail',
                'sometimes',
                'integer',
                'min:1',
                with(
                    Rule::unique(Category::TABLE, 'position'),
                    fn (Unique $unique) => $this->category instanceof Category
                        ? $unique->ignore($this->category->id, 'id')
                        : $unique
                ),
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
                    Rule::unique(Category::TABLE, 'slug'),
                    fn (Unique $unique) => $this->category instanceof Category
                        ? $unique->ignore($this->category->id, 'id')
                        : $unique
                ),
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `alt_url` field.
     *
     * @return array
     */
    protected function altUrlRules(): array
    {
        return [
            'alt_url' => [
                'bail',
                'nullable',
                'string',
                'max:255',
                'url',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `info` field.
     *
     * @return array
     */
    protected function infoRules(): array
    {
        return [
            'info' => [
                'sometimes',
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
                'sometimes',
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
                'sometimes',
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
            'meta_robots' => [
                'sometimes',
                'nullable',
                $this->container->make(MetaRobotsRule::class),
            ]
        ];
    }

    /**
     * Get the validation rules used to validate `show_in_menu` field.
     *
     * @return array
     */
    protected function showInMenuRules(): array
    {
        return [
            'show_in_menu' => [
                'sometimes',
                'boolean',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `order_by` field.
     *
     * @return array
     */
    protected function orderByRules(): array
    {
        return [
            'order_by' => [
                'sometimes',
                'string',
                'max:255',
                Rule::in([
                    'id',
                    'title',
                    'votes',
                    'rating',
                    'views',
                    'comments_count',
                    'created_at',
                    'updated_at',
                ]),
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `direction` field.
     *
     * @return array
     */
    protected function directionRules(): array
    {
        return [
            'direction' => [
                'sometimes',
                'string',
                'max:255',
                Rule::in([
                    'desc',
                    'asc',
                ]),
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `paginate` field.
     *
     * @return array
     */
    protected function paginateRules(): array
    {
        return [
            'paginate' => [
                'sometimes',
                'nullable',
                'integer',
                'max:255',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `template` field.
     *
     * @return array
     */
    protected function templateRules(): array
    {
        return [
            'template' => [
                'sometimes',
                'nullable',
                'alpha_dash',
            ],
        ];
    }
}
