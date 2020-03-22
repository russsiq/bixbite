<?php

namespace App\Http\Requests\Api\V1\Category;

// Сторонние зависимости.
use App\Http\Requests\BaseFormRequest;

class CategoryRequest extends BaseFormRequest
{
    /**
     * Подготовить данные для валидации.
     * @return void
     */
    protected function prepareForValidation()
    {
        $input = $this->except([
            '_token',
            '_method',
            'submit',
            'created_at',
            'updated_at',
            'deleted_at',

        ]);

        $input['title'] = filter_var($input['title'], FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
        $input['slug'] = string_slug($input['slug'] ?? $input['title']);

        $input['description'] = html_clean($this->input('description', null));
        $input['keywords'] = html_clean($this->input('keywords', null));

        // Delete all scripts from info sector.
        $input['info'] = ! empty($input['info']) ? preg_replace("/\<script.*?\<\/script\>/", '', $input['info']) : null;

        if (! empty($input['alt_url'])) {
            $input['alt_url'] = filter_var($input['alt_url'], FILTER_SANITIZE_URL, FILTER_FLAG_EMPTY_STRING_NULL);
        }

        $this->replace($input)
            ->merge([
                // Default value to checkbox.
                'show_in_menu' => $this->input('show_in_menu', false),

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
            'title' => [
                'required',
                'string',
                'max:255',

            ],

            'slug' => [
                'required',
                'string',
                'max:255',

            ],

            'alt_url' => [
                'nullable',
                'string',
                'max:255',

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

            'info' => [
                'nullable',
                'string',
                'max:500',

            ],

            'show_in_menu' => [
                'required',
                'boolean',

            ],

            'paginate' => [
                'nullable',
                'integer',

            ],

            'order_by' => [
                'nullable',
                'string',

            ],

            'direction' => [
                'nullable',
                'in:desc,asc',

            ],

            'template' => [
                'nullable',
                'alpha_dash',

            ],

            // Relations types.
            'image_id' => [
                'nullable',
                'integer',

            ],

        ];
    }
}
