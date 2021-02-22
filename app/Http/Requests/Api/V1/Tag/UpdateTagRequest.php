<?php

namespace App\Http\Requests\Api\V1\Tag;

use App\Http\Requests\Api\V1\Tag\StoreTagRequest;

class UpdateTagRequest extends StoreTagRequest
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
