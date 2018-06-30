<?php

namespace BBCMS\Http\Requests;

use BBCMS\Models\Category;
use BBCMS\Http\Requests\Request;

class CategoriesRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function sanitize()
    {
        $input = $this->except(['_token', '_method', /*'created_at', 'updated_at', 'deleted_at',*/ 'submit']);

        $input['title'] = filter_var($input['title'], FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
        $input['slug'] = string_slug($this->input('slug') ?? $this->input('title'));
        if (!empty($input['alt_url'])) {
            $input['alt_url'] = filter_var($input['alt_url'], FILTER_SANITIZE_URL, FILTER_FLAG_EMPTY_STRING_NULL);
        }
        $input['description'] = teaser($input['description'], 255);
        $input['keywords'] = teaser($input['keywords'], 255);
        if (!empty($input['info'])) {
            $input['info'] = preg_replace("/\<script.*?\<\/script\>/", '', $input['info']);
        }

        return $this->replace($input)->all();
    }

    protected function validationData()
    {
        return $this->merge([
            // default value to checkbox
            'show_in_menu' => $this->input('show_in_menu', null),
        ])->all();
    }

    public function rules()
    {
        return [
            'title'         => ['required', 'string', 'max:255',
                                'unique:categories,title'.
                                (isset($this->category->id) ? ",".$this->category->id.",id" : ''),
                                ],
            'slug'          => ['required', 'string', 'max:255',
                                'unique:categories,slug'.
                                (isset($this->category->id) ? ",".$this->category->id.",id" : ''),
                                ],
            'alt_url'       => ['nullable', 'string', 'max:255',],
            // 'img'        => 'alpha_num',
            'description'   => ['nullable', 'string', 'max:255',],
            'keywords'      => ['nullable', 'string', 'max:255',],
            'info'          => ['nullable', 'string', 'max:500',],

            'show_in_menu'  => ['nullable', 'boolean',],
            'paginate'      => ['nullable', 'integer',],
            'order_by'      => ['nullable', 'string',],
            'direction'     => ['required', 'string', 'in:desc,asc',],
            'template'      => ['nullable', 'alpha_dash',],
        ];
    }
}
