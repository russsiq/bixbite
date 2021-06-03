<?php

namespace App\Actions\Category;

use App\Contracts\Actions\Category\DetachesCategory;
use App\Models\Contracts\CategoryableContract;
use App\Models\Category;
use Illuminate\Database\Eloquent\Relations\Relation;

class DetachCategoryAction extends CategoryActionAbstract implements DetachesCategory
{
    /**
     * Validate and detach the category.
     *
     * @param  string  $categoryable_type
     * @param  integer  $categoryable_id
     * @param  integer  $category_id
     * @return void
     */
    public function detach(string $categoryable_type, int $categoryable_id, int $category_id): void
    {
        $this->category = $this->resolveCategory($category_id);
        $this->categoryable = $this->resolveCategoryable($categoryable_type, $categoryable_id);

        $this->authorize('update', $this->category);
        $this->authorize('update', $this->categoryable);

        $this->categoryable->categories()->detach($this->category);
    }

    /**
     * Get the Category by its primary key.
     *
     * @param  integer  $id
     * @return Category
     */
    protected function resolveCategory(int $id): Category
    {
        return Category::withoutEvents(
            fn () => Category::withoutGlobalScopes()
                ->withOnly([])
                ->findOrFail($id, ['id'])
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
            //
        ];
    }
}
