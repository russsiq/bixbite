<?php

namespace App\Actions\Category;

use App\Contracts\Actions\Category\DeletesCategory;
use App\Models\Category;
use Illuminate\Contracts\Validation\Validator;

class DeleteCategoryAction extends CategoryActionAbstract implements DeletesCategory
{
    /**
     * Delete the given category.
     *
     * @param  Category  $category
     * @return int  Remote category ID.
     */
    public function delete(Category $category): int
    {
        $this->authorize(
            'delete', $this->category = $category->fresh()
        );

        $this->validate([
            'categoryable_count' => $this->category->getCategoryableCount(),
        ]);

        $id = $category->id;

        $category->delete();

        return $id;
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'categoryable_count' => [
                'required',
                'integer',
                'size:0',
            ],
        ];
    }
}
