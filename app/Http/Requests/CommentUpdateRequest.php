<?php

namespace App\Http\Requests;

// Сторонние зависимости.
use App\Http\Requests\BaseFormRequest;
use Illuminate\Support\Str;
use Russsiq\DomManipulator\Facades\DOMManipulator;

class CommentUpdateRequest extends BaseFormRequest
{
    /**
     * Подготовить данные для валидации.
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $input = $this->only([
            'content',

        ]);

        $input['content'] = $this->prepareContent($this->input('content'));

        $this->replace($input);
    }

    protected function prepareContent(string $content = null): string
    {
        if (is_null($content)) {
            return '';
        }

        $content = DOMManipulator::removeEmoji($content);

        $content = DOMManipulator::wrapAsDocument($content)
            ->revisionPreTag()
            ->remove('script');

        if (! setting('comments.use_html', false)) {
            $content = Str::cleanHTML($content);
        }

        return $content;
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
