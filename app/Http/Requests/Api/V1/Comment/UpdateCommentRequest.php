<?php

namespace App\Http\Requests\Api\V1\Comment;

use App\Http\Requests\Api\V1\Comment\StoreCommentRequest;

class UpdateCommentRequest extends StoreCommentRequest
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
