<?php

namespace BBCMS\Http\Requests\Api\V1\Category;

use BBCMS\Models\Category;
use BBCMS\Http\Requests\BaseFormRequest;

class CategoryRequest extends BaseFormRequest
{
    public function validationData()
    {
        $input = $this->except([
            '_token',
            '_method',
            'created_at',
            'updated_at',
            'deleted_at',
            'submit',
        ]);

        $input['title'] = filter_var($input['title'], FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
        $input['slug'] = string_slug($input['slug'] ?? $input['title']);

        $input['description'] = html_clean($this->input('description', null));
        $input['keywords'] = html_clean($this->input('keywords', null));

        // Delete all scripts from info sector.
        $input['info'] = ! empty($input['info']) ? preg_replace("/\<script.*?\<\/script\>/", '', $input['info']) : null;

        if (! empty($input['alt_url'])) {
            $input['alt_url'] = filter_var($input['alt_url'], FILTER_SANITIZE_URL, FILTER_FLAG_EMPTY_STRING_NULL);
        }

        return $this->replace($input)
            ->merge([
                // Default value to checkbox.
                'show_in_menu' => $this->input('show_in_menu', false),
            ])
            ->all();
    }

    public function rules()
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'required',
                'string',
                'max:255',
            ],

            'alt_url' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
                'max:255',
            ],

            'keywords' => [
                'nullable',
                'string',
                'max:255',
            ],

            'info' => [
                'nullable',
                'string',
                'max:500',
            ],

            'show_in_menu' => [
                'required',
                'boolean',
            ],

            'paginate' => [
                'nullable',
                'integer',
            ],

            'order_by' => [
                'nullable',
                'string',
            ],

            'direction' => [
                'nullable',
                'in:desc,asc',
            ],

            'template' => [
                'nullable',
                'alpha_dash',
            ],

            // Relations types.
            'image_id' => [
                'nullable',
                'integer',
            ],
        ];
    }
}
