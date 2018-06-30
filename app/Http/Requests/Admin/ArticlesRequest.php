<?php

namespace BBCMS\Http\Requests\Admin;

use BBCMS\Models\Article;
use BBCMS\Http\Requests\Request;

class ArticlesRequest extends Request
{
    public function authorize()
    {
        // return false;
        return true;
    }

    public function rules()
    {
        return [
            'articles'      => 'required|array',
            'mass_action'   => 'required|string|in:published,unpublished,draft,on_mainpage,not_on_mainpage,allow_com,disallow_com,currdate,delete,delete_drafts',
        ];
    }

    public function messages()
    {
        return [
            'articles.*'    => 'Необходимо выбрать записи',
            'mass_action.*' => 'Необходимо выбрать действие',
        ];
    }
}
