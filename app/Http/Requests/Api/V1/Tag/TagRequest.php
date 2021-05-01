<?php

namespace App\Http\Requests\Api\V1\Tag;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TagRequest extends FormRequest
{
    /**
     * Indicates whether validation should stop after the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->whenFilled('title', function ($title) {
            $language = setting('system.translite_code', 'ru__gost_2000_b');

            $this->merge([
                'slug' => Str::slug($title, '-', $language),
            ]);
        });
    }

    /**
     * Получить массив правил валидации,
     * которые будут применены к запросу.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => [
                'bail',
                'required',
                'string',
                'max:255',
                'regex:/^[0-9\w\s]+$/u',

            ],

            'slug' => [
                'bail',
                'required',
                'string',
                'max:255',
                'alpha_dash',
            ],

            'taggable_type' => [
                'nullable',
                'alpha_dash',
                Rule::in(
                    array_keys(Relation::morphMap())
                ),

            ],

            'taggable_id' => [
                'bail',
                'nullable',
                'integer',
                'required_with:taggable_type',
                'exists:'.$this->input('taggable_type').',id',

            ],

        ];
    }
}
