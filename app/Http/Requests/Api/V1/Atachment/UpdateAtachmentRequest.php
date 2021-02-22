<?php

namespace App\Http\Requests\Api\V1\Atachment;

use App\Http\Requests\Api\V1\Atachment\StoreAtachmentRequest;

class UpdateAtachmentRequest extends StoreAtachmentRequest
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
