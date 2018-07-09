<?php

namespace BBCMS\Http\Requests\Admin;

use BBCMS\Models\Article;
use BBCMS\Http\Requests\Request;

class ArticlesRequest extends Request
{
    public function authorize()
    {
        return true;
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
                'in:published,unpublished,draft,on_mainpage,not_on_mainpage,allow_com,disallow_com,currdate,delete,delete_drafts',
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
