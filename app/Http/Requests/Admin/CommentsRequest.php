<?php

namespace BBCMS\Http\Requests\Admin;

use BBCMS\Models\Comment;
use BBCMS\Http\Requests\Request;

class CommentsRequest extends Request
{
    public function authorize()
    {
        return true;
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
                'in:published,unpublished,delete',
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
