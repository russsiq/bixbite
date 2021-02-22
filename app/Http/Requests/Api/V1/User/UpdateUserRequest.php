<?php

namespace App\Http\Requests\Api\V1\User;

use App\Http\Requests\Api\V1\User\StoreUserRequest;

class UpdateUserRequest extends StoreUserRequest
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
