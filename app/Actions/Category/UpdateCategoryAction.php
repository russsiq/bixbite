<?php

namespace App\Actions\Category;

use App\Contracts\Actions\Category\UpdatesCategory;
use App\Models\Category;
use App\Rules\Concerns\ExtraFieldsRules;

class UpdateCategoryAction extends CategoryActionAbstract implements UpdatesCategory
{
    use ExtraFieldsRules;

    /**
     * Validate and update the given extra field.
     *
     * @param  Category  $category
     * @param  array  $input
     * @return Category
     */
    public function update(Category $category, array $input): Category
    {
        $this->authorize(
            'update', $this->category = $category
        );

        $this->category->update(
            $this->validate(
                $this->prepareForValidation($input)
            )
        );

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
            $this->extraFieldsRules(Category::getModel()),
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
