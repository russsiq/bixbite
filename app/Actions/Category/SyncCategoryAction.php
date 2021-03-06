<?php

namespace App\Actions\Category;

use App\Contracts\Actions\Category\SyncsCategory;
use App\Models\Category;
use App\Models\Contracts\CategoryableContract;
use Illuminate\Collections\ItemNotFoundException;
use Illuminate\Collections\MultipleItemsFoundException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\Relation;

class SyncCategoryAction extends CategoryActionAbstract implements SyncsCategory
{
    protected ?EloquentCollection $categories;
    protected $stopOnFirstFailure = true;

    /**
     * Validate and sync the categories associations.
     *
     * @param  string  $categoryable_type
     * @param  integer  $categoryable_id
     * @param  array  $input
     * @return void
     */
    public function sync(string $categoryable_type, int $categoryable_id, array $input = []): void
    {
        // Validation comes first.
        $validated = $this->validate($input);

        $this->categories = $this->resolveCategories($validated['categories']);
        $this->categoryable = $this->resolveCategoryable($categoryable_type, $categoryable_id);

        $this->authorize('update', $this->categoryable);

        foreach ($this->categories as $category) {
            $this->authorize('update', $category);
        }

        $synced = [];

        foreach ($validated['categories'] as $category) {
            $synced[$category['category_id']] = [
                'is_main' => $category['is_main'],
            ];
        }

        $this->categoryable->categories()->sync($synced);
    }

    /**
     * Get categories by their primary key.
     *
     * @param  array  $categories
     * @return EloquentCollection
     */
    protected function resolveCategories(array $categories = []): EloquentCollection
    {
        return Category::withoutEvents(
            fn () => Category::withoutGlobalScopes()
                ->withOnly([])
                ->whereIn('id', array_column($categories, 'category_id'))
                ->get('id')
        );
    }

    /**
     * Get the model associated with a polymorphic type by its primary key.
     *
     * @param  string  $type
     * @param  integer  $id
     * @return CategoryableContract
     */
    protected function resolveCategoryable(string $type, int $id): CategoryableContract
    {
        $model = Relation::getMorphedModel($type);

        return $model::withoutEvents(
            fn () => $model::withoutGlobalScopes()
                ->withOnly([])
                ->findOrFail($id, ['id'])
        );
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'categories' => [
                'array',
                'required',
            ],

            'categories.*.category_id' => [
                'bail',
                'required',
                'integer',
                'min:1',
                'distinct:strict',

            ],

            'categories.*.is_main' => [
                'bail',
                'boolean',
            ],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  Validator  $validator
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->isEmpty()) {
                $attributes = $validator->attributes();
                $categories = collect($attributes)->only('categories')->flatten(1);

                try {
                    $categories->sole('is_main', true);

                    if (Category::whereIn('parent_id', $categories->pluck('category_id'))->count()) {
                        $errorMessage = 'A category with child categories cannot contain entries associated with it.';
                    }
                } catch (ItemNotFoundException $th) {
                    $errorMessage = 'You have not specified the main category.';
                } catch (MultipleItemsFoundException $th) {
                    $errorMessage = 'There can only be one main category.';
                } finally {
                    if (isset($errorMessage)) {
                        $validator->errors()->add('categories', $this->translate($errorMessage));
                    }
                }
            }
        });
    }
}
