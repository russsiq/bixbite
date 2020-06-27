<?php

namespace App\Http\Requests\Api\V1\Note;

// Зарегистрированные фасады приложения.
use Illuminate\Support\Facades\Auth;

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

        // Не доверяя пользователю,
        // выбираем его идентификатор
        // из фасада аутентификации.
        $input['user_id'] = Auth::id();

        $input['image_id'] = $this->input('image_id', null);
        $input['title'] = $this->input('title', null);
        $input['slug'] = Str::slug($this->input('title'));
        $input['description'] = Str::teaser($this->input('description', null), 500);
        $input['is_completed'] = $this->input('is_completed', false);

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
