<?php

namespace App\Http\Requests\Front;

// Сторонние зависимости.
use App\Http\Requests\BaseFormRequest;
use Illuminate\Support\Str;

class FeedbackRequest extends BaseFormRequest
{
    /**
     * Подготовить данные для валидации.
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $input = $this->except([
            '_token',
            '_method',
            'submit',

        ]);

        $this->replace($input)
            ->merge([
                'name' => Str::teaser($this->input('name'), 100),
                'contact' => Str::secureHtml($this->input('contact')),
                'content' => nl2br(Str::secureHtml($this->input('content'))),
                'politics' => $this->input('politics', false),

            ]);
    }

    /**
     * Получить пользовательские имена атрибутов
     * для формирования сообщений валидатора.
     * @return array
     */
    public function attributes(): array
    {
        $trans = trans('feedback.form.attributes');

        return is_array($trans) ? $trans : [];
    }

    /**
     * Получить массив пользовательских строк перевода
     * для формирования сообщений валидатора.
     * @return array
     */
    public function messages(): array
    {
        $trans = trans('feedback.form.validation');

        return is_array($trans) ? $trans : [];
    }

    /**
     * Получить массив правил валидации,
     * которые будут применены к запросу.
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',

            ],

            'contact' => [
                'required',
                'string',
                'min:6',
                'max:255',

            ],

            'content' => [
                'required',
                'string',
                'min:25',
                'max:1000',

            ],

            'politics' => [
                'accepted',

            ],

            'file' => [
                'nullable',
                'file',
                'max:51200',
                'mimetypes:application/zip',

            ],

            'g-recaptcha-response' => [
                config('g_recaptcha.used') ? 'g_recaptcha' : 'nullable',

            ],

        ];
    }
}
