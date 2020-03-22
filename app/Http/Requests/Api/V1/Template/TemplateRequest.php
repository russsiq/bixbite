<?php

namespace App\Http\Requests\Api\V1\Template;

// Сторонние зависимости.
use App\Http\Requests\BaseFormRequest;
use App\Models\Template;

class TemplateRequest extends BaseFormRequest
{
    /**
     * Подготовить данные для валидации.
     * @return void
     */
    protected function prepareForValidation()
    {
        $filename = $this->get('filename');
        $content = $this->get('content', null);

        $template = new Template([
            'filename' => $filename,
        ]);

        $template->get();

        $this->replace([
                'filename' => $filename,
                'content' => $content,

            ])
            ->merge([
                'template' => $template,
                'path' => $template->path,
                'exists' => $template->exists,

            ]);
    }

    /**
     * Получить пользовательские имена атрибутов
     * для формирования сообщений валидатора.
     * @return array
     */
    public function attributes(): array
    {
        return [
            'filename' => trans('Template'),
            'content' => trans('Content'),

        ];
    }

    /**
     * Получить массив пользовательских строк перевода
     * для формирования сообщений валидатора.
     * @return array
     */
    public function messages(): array
    {
        return [
            // 'filename.required' => trans('msg.filename.required'),
            'content.required' => trans('msg.content.required'),

        ];
    }
}
