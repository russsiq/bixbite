<?php

namespace App\Http\Requests\Api\V1\Category;

use App\Http\Requests\Api\V1\Category\StoreCategoryRequest;

class UpdateCategoryRequest extends StoreCategoryRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            //
        ]);
    }
}
