<?php

namespace BBCMS\Http\Requests;

// Сторонние зависимости.
use BBCMS\Http\Requests\BaseFormRequest;

class CommentUpdateRequest extends BaseFormRequest
{
    /**
     * Подготовить данные для валидации.
     * @return void
     */
    protected function prepareForValidation()
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

        $this->replace($input);
    }

    /**
     * Получить массив правил валидации,
     * которые будут применены к запросу.
     * @return array
     */
    public function rules(): array
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
