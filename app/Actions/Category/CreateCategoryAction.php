<?php

namespace App\Actions\Category;

use App\Contracts\Actions\Category\CreatesCategory;
use App\Models\Category;

class CreateCategoryAction extends CategoryActionAbstract implements CreatesCategory
{
    /**
     * Validate and create a newly category.
     *
     * @param  array  $input
     * @return Category
     */
    public function create(array $input): Category
    {
        $this->authorize('create', Category::class);

        $this->category = Category::create(
            $this->validate(
                $this->prepareForValidation($input)
            )
        )->fresh();

        return $this->category;
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    protected function rules(): array
    {
        return array_merge(
            // $this->extraFieldsRules(Category::getModel()), // Only when update.
            $this->imageIdRules(),
            $this->parentIdRules(),
            // $this->positionRules(), // Only when mass update.
            $this->titleRules(),
            $this->slugRules(),
            $this->altUrlRules(),
            $this->infoRules(),
            $this->metaDescriptionRules(),
            $this->metaKeywordsRules(),
            $this->metaRobotsRules(),
            $this->showInMenuRules(),
            $this->orderByRules(),
            $this->directionRules(),
            $this->paginateRules(),
            $this->templateRules(),
        );
    }
}
