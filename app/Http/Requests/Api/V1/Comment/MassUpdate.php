<?php

namespace BBCMS\Http\Requests\Api\V1\Comment;

use BBCMS\Models\Comment;
use BBCMS\Http\Requests\BaseFormRequest;

class MassUpdate extends BaseFormRequest
{
    protected $allowedActions = [
        'published',
        'unpublished',
        'is_approved',
    ];

    public function validationData()
    {
        return $this->all();
    }

    public function rules()
    {
        return [
            'comments' => [
                'required',
                'array',
            ],

            'comments.*' => [
                'integer',
            ],

            'mass_action' => [
                'required',
                'string',
                'in:'.implode(',', $this->allowedActions),
            ],
        ];
    }

    public function messages()
    {
        return [
            'comments.*' => __('msg.validate.comments'),
            'mass_action.*' => __('msg.validate.mass_action'),
        ];
    }
}
