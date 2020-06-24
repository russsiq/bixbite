<?php

namespace App\Http\Requests\Api\V1\Note;

// Сторонние зависимости.
use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class NoteRequest extends BaseFormRequest
{
    /**
     * Подготовить данные для валидации.
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $input = [];

        $input['user_id'] = $this->get('user_id', null);
        $input['image_id'] = $this->get('image_id', null);
        $input['title'] = $this->get('title', null);
        $input['slug'] = string_slug($input['title']);
        $input['description'] = Str::teaser($this->get('description', null), 500);
        $input['is_completed'] = $this->get('is_completed', false);

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
            'title' => [
                'required',
                'string',
                'max:225',
                'regex:/^[\w\s\.\,\-\_\?\!\(\)\[\]]+$/u',

            ],

            'slug' => [
                'required',
                'string',
                'max:225',
                'alpha_dash',
                Rule::unique('notes')->ignore($this->note),

            ],

            'description' => [
                'required',
                'string',
                'max:500',

            ],

            'is_completed' => [
                'required',
                'boolean',

            ],

            // Relations types.
            'user_id' => [
                'required',
                Rule::in(auth('api')->user()->id),

            ],

            'image_id' => [
                'nullable',
                'exists:files,id',

            ],

        ];
    }
}
