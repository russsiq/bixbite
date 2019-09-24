<?php

namespace BBCMS\Http\Requests\Api\V1\Article;

use BBCMS\Models\Article;
use BBCMS\Http\Requests\Request;

class MassUpdate extends Request
{
    protected $allowedActions = [
        'published',
        'unpublished',
        'draft',
        'on_mainpage',
        'allow_com',
        'currdate',
        'is_favorite',
        'is_catpinned',
    ];

    public function authorize()
    {
        return true;
    }

    protected function validationData()
    {
        return $this->all();
    }

    public function rules()
    {
        return [
            'articles' => [
                'required',
                'array',
            ],

            'articles.*' => [
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
            'articles.*' => __('msg.validate.articles'),
            'mass_action.*' => __('msg.validate.mass_action'),
        ];
    }
}
