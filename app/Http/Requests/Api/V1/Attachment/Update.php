<?php

namespace App\Http\Requests\Api\V1\Attachment;

use Illuminate\Support\Str;

class Update extends AttachmentRequest
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
            'name',

        ]);

        $this->replace($input)
            ->merge([
                // 1 Do not change the filename. He may become unavailable.
                // 'name' => Str::slug($this->input('title')).'_'.time(),

                // 2 Do not change the file title. Leave for validation.
                // 'title' => $this->input('title', null),

                // 3 Clean html tags in descroption.
                'description' => Str::cleanHTML($this->input('description', null)),

            ]);
    }

    /**
     * Получить массив правил валидации,
     * которые будут применены к запросу.
     * @return array
     */
    public function rules(): array
    {
        return [
            // Always check the `attachable_type` first.
            'attachable_type' => [
                'nullable',
                'alpha_dash',
                'in:'.self::morphMap(),

            ],

            // After that, we check for a record in the database.
            'attachable_id' => [
                'bail',
                'nullable',
                'integer',
                'required_with:attachable_type',
                'exists:'.$this->input('attachable_type').',id',

            ],

            'title' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\w\s\.\,\-\_\?\!\(\)]+$/u',

            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',

            ],

        ];
    }
}
