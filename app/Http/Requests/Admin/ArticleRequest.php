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
        $input = $this->except([
            '_token',
            '_method',
            'submit',
        ]);

        $input['user_id'] = $this->article->user_id ?? user('id');

        $input['title'] = filter_var($input['title'], FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
        $input['slug'] = string_slug($this->input('slug') ?? $this->input('title'));
        $input['teaser'] = html_clean($input['teaser']);

        $input['content'] = preg_replace_callback("/\<code\>(.+?)\<\/code\>/is",
            function ($match) {
                return '<pre>' . html_secure($match[1]) . '</pre>';
            }, $this->input('content')
        );
        $input['content'] = preg_replace("/\<script.*?\<\/script\>/", '', $input['content']);
        $input['content'] = str_replace('<p><br></p>', '', $input['content']);
        $input['content'] = $this->removeEmoji($input['content']);

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

        if (empty($input['categories'])) {
            $input['state'] = 'draft';
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
            'on_mainpage' => $this->input('on_mainpage', 0),
            'is_pinned' => $this->input('is_pinned', 0),
            'is_catpinned' => $this->input('is_catpinned', 0),
            'is_favorite' => $this->input('is_favorite', 0),
        ])->all();
    }

    /**
     * Set custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'title' => __('title#tip'),
            'teaser' => __('teaser'),
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $except = is_integer($id = optional($this->article)->id)  ? ",$id,id" : '';

        return [
            // Main content.
            'title' => [
                'bail',
                'required',
                'string',
                'max:255',
                'unique:articles,title'.$except,
            ],
            'slug' => [
                'bail',
                'nullable',
                'string',
                'max:255',
                'unique:articles,slug'.$except,
                'required_with:title',
            ],
            'teaser' => [
                'nullable',
                'string',
                'max:255',
            ],
            'content' => [
                'nullable',
                'string',
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

            // Flags ?
            'state' => [
                'required',
                'string',
                'in:draft,unpublished,published',
            ],
            'on_mainpage' => [
                'nullable',
                'boolean',
            ],
            'is_favorite' => [
                'nullable',
                'boolean',
            ],
            'is_pinned' => [
                'nullable',
                'boolean',
            ],
            'is_catpinned' => [
                'nullable',
                'boolean',
            ],

            // DateTime.
            'currdate' => [
                'nullable',
                'boolean',
            ],
            'customdate' => [
                'nullable',
                'boolean',
            ],
            'created_at' => [
                'nullable',
                'date_format:"Y-m-d H:i:s"',
                'required_with:customdate',
            ],

            // Extension.
            'allow_com' => [
                'required',
                'numeric',
                'in:0,1,2',
            ],
            'views' => [
                'nullable',
                'integer',
            ],
            'votes' => [
                'nullable',
                'integer',
            ],
            'rating' => [
                'nullable',
                'integer',
            ],

            // Relations types.
            'image_id' => [
                'nullable',
                'integer',
                'exists:files,id',
            ],
            'categories' => [
                'nullable',
                'array',
            ],
            'categories.*' => [
                'integer',
                'exists:categories,id',
            ],
            'files' => [
                'nullable',
                'array',
            ],
            'files.*' => [
                'integer',
                'exists:files,id',
            ],
            'images' => [
                'nullable',
                'array',
            ],
            'images.*' => [
                'integer',
                'exists:files,id',
            ],
            'tags' => [
                'nullable',
                'array',
            ],
            'tags.*' => [
                'string',
                'max:255',
                'regex:/^[\w-]+$/u',
            ],
        ];
    }

    /**
     * Remove Emoji Characters in PHP by Himphen Hui.
     * https://medium.com/coding-cheatsheet/remove-emoji-characters-in-php-236034946f51
     *
     * @param  string $string
     * @return string
     */
    public function removeEmoji(string $string)
    {
        // Match Emoticons.
        $regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clear_string = preg_replace($regex_emoticons, '', $string);

        // Match Miscellaneous Symbols and Pictographs.
        $regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clear_string = preg_replace($regex_symbols, '', $clear_string);

        // Match Transport And Map Symbols.
        $regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clear_string = preg_replace($regex_transport, '', $clear_string);

        // Match Miscellaneous Symbols.
        $regex_misc = '/[\x{2600}-\x{26FF}]/u';
        $clear_string = preg_replace($regex_misc, '', $clear_string);

        // Match Dingbats.
        $regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
        $clear_string = preg_replace($regex_dingbats, '', $clear_string);

        return $clear_string;
    }
}
