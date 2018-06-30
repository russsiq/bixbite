<?php

namespace BBCMS\Http\Requests\Admin;

use BBCMS\Models\Article;
use BBCMS\Http\Requests\Request;

class ArticleRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function sanitize()
    {
        $input = $this->except(['_token', '_method', 'submit']);

        $input['user_id'] = $this->article->user_id ?? user('id');

        $input['title'] = filter_var($input['title'], FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
        $input['slug'] = string_slug($this->input('slug') ?? $this->input('title'));
        $input['teaser'] = html_clean($input['teaser']);
        $input['content'] = preg_replace("/\<script.*?\<\/script\>/", '', $input['content']);
        $input['description'] = teaser($input['description'], 255);
        $input['keywords'] = teaser($input['keywords'], 255);
        $input['tags'] = array_map(
            function (string $tag) {
                return string_slug($tag, setting('tags.delimiter', '-'), false, false);
            }, preg_split('/,/', $input['tags'], -1, PREG_SPLIT_NO_EMPTY)
        );

        if (isset($input['customdate']) or isset($input['currdate'])) {
            if (isset($input['currdate'])) {
                $input['created_at'] = date('Y-m-d H:i:s');
            } else {
                $input['created_at'] = date_format(date_create($input['created_at']), 'Y-m-d H:i:s');
            }
            $input['updated_at'] = null;
        } elseif (isset($this->article->id)) {
            unset($input['created_at']);
            $input['updated_at'] = date('Y-m-d H:i:s');
        } else {
            $input['created_at'] = date('Y-m-d H:i:s');
        }

        return $this->replace($input)->all();
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    protected function validationData()
    {
        return $this->merge([
            // default value to the checkbox
            'on_mainpage' => $this->input('on_mainpage', null),
            'is_pinned' => $this->input('is_pinned', null),
            'is_catpinned' => $this->input('is_catpinned', null),
            'is_favorite' => $this->input('is_favorite', null),
        ])->all();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // Main content
            'title'             => ['required', 'string', 'max:255', // 'bail',
                                    'unique:articles,title'.
                                    (isset($this->article->id) ? ','.$this->article->id.',id' : ''),
                                ],
            'slug'              => ['required', 'string', 'max:255',
                                    'unique:articles,slug'.
                                    (isset($this->article->id) ? ','.$this->article->id.',id' : ''),
                                    // to get from ROUTE admin/articles/{article}/edit
                                    // use Illuminate\Validation\Rule;
                                    // Rule::unique('articles')->ignore($this->article->id),
                                ],
            'teaser' => ['nullable', 'string', 'max:255', ],
            'content' => ['nullable', 'string', ],
            'description' => ['nullable', 'string', 'max:255', ],
            'keywords' => ['nullable', 'string', 'max:255', ],
            // Flags ?
            'state' => ['required', 'string', 'in:draft,unpublished,published', ],
            'on_mainpage' => ['nullable', 'boolean', ],
            'is_favorite' => ['nullable', 'boolean', ],
            'is_pinned' => ['nullable', 'boolean', ],
            'is_catpinned' => ['nullable', 'boolean', ],
            'currdate' => ['nullable', 'boolean', ],
            // DateTime
            'customdate' => ['nullable', 'boolean', ],
            'created_at' => ['nullable', 'date_format:"Y-m-d H:i:s"', 'required_with:customdate', ],
            // Extension
            'allow_com' => ['required', 'numeric', 'in:0,1,2', ],
            'views' => ['nullable', 'integer', ],
            'votes' => ['nullable', 'integer', ],
            'rating' => ['nullable', 'integer', ],

            // Relations types
            // 'images' => ['nullable', 'string', 'max:255', ],
            // 'files'          => ['nullable', 'string', 'max:255', ],
            'image_id' => ['nullable', 'integer', ],

            'categories' => ['required', 'array', ],
            'categories.*' => ['integer', 'exists:categories,id', ],

            'tags' => ['nullable', 'array', ],
            'tags.*' => ['nullable', 'string', 'max:255', 'regex:/^[\w\s-_\.]+$/u', ],
        ];
    }

    /**
    * Configure the validator instance.
    *
    * @param  \Illuminate\Validation\Validator  $validator
    * @return void
    */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

        });

        return $validator;
    }
}
