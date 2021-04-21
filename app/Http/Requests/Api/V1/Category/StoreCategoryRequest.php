<?php

namespace App\Http\Requests\Api\V1\Category;

use App\Models\Category;
use App\Rules\MetaRobotsRule;
use App\Rules\SqlTextLength;
use App\Rules\TitleRule;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
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
        return [
            'parent_id' => [
                'bail',
                'required',
                'integer',
                'numeric',
                $this->input('parent_id') > 0
                    ? Rule::exists('categories', 'id')
                        ->where(function (Builder $query) {
                            $category = $this->route('category');

                            if ($category instanceof Category) {
                                $query->where('id', '<>', $category->id);
                            }

                            $query->where('id', '=', (int) $this->input('parent_id'));
                        })
                    : 'size:0',
            ],

            'position' => ['bail', 'sometimes', 'integer', 'min:0'],

            'title' => ['bail', 'required', $titleRule],
            'slug' => [
                'bail',
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('categories', 'slug')
                    ->where(function (Builder $query) {
                        $category = $this->route('category');

                        if ($category instanceof Category) {
                            $query->where('slug', '<>', $category->slug);
                        }
                    }),
            ],
            'alt_url' => ['nullable', 'string', 'max:255'],
            'info' => ['nullable', $sqlTextLengthRule],

            'meta_description' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'meta_robots' => ['sometimes', 'string', 'max:255', $metaRobotsRule],

            'show_in_menu' => ['sometimes', 'boolean'],
            'paginate' => ['sometimes', 'integer', 'min:1'],
            'template' => ['nullable', 'string', 'max:255'],
            'order_by' => ['sometimes', 'string', 'max:255'],
            'direction' => ['sometimes', 'string', 'max:255', 'in:desc,asc'],

            'created_at' => ['sometimes', 'date'],
            'updated_at' => ['sometimes', 'date'],
        ];
    }
}
