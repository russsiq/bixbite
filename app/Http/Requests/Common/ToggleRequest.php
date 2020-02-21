<?php

namespace BBCMS\Http\Requests\Common;

use BBCMS\Http\Requests\BaseFormRequest;

class ToggleRequest extends BaseFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function validationData()
    {
        return $this->replace([
            'id' => (int) $this->route('id'),
            'model' => ucfirst($this->route('model')),
            'attribute' => $this->route('attribute'),
        ])->all();
    }

    public function rules()
    {
        return [
            'id' => ['bail', 'required', 'integer'],
            'model' => ['bail', 'required', 'string', 'max:125', 'regex:/^[a-zA-Z_]+$/u'],
            'attribute' => ['bail', 'required', 'string', 'max:125', 'regex:/^[a-z_]+$/u'],
        ];
    }
}
