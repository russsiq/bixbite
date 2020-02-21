<?php

namespace BBCMS\Http\Requests;

use BBCMS\Http\Requests\BaseFormRequest;

class CommentUpdateRequest extends BaseFormRequest
{
    /**
     * Получить данные из запроса для валидации.
     *
     * @return array
     */
    public function validationData()
    {
        $input = $this->only([
            'content',
        ]);

        $input['content'] = preg_replace_callback("/\<code\>(.+?)\<\/code\>/is",
            function ($match) {
                return '<pre>'.html_secure($match[1]).'</pre>';
            }, $this->input('content')
        );

        $input['content'] = preg_replace("/\<script.*?\<\/script\>/", '', $input['content']);

        if (! setting('comments.use_html', false)) {
            $input['content'] = html_clean($input['content']);
        }

        return $this->replace($input)
            ->all();
    }

    public function rules()
    {
        return [
            'content' => [
                'required',
                'string',
                'between:4,1500',
            ],
        ];
    }
}
