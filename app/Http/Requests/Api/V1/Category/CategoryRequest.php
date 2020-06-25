<?php

namespace App\Http\Requests\Api\V1\Category;

// Сторонние зависимости.
use App\Http\Requests\BaseFormRequest;
use Illuminate\Support\Str;
use Russsiq\DomManipulator\Facades\DOMManipulator;

class CategoryRequest extends BaseFormRequest
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
            'created_at',
            'updated_at',
            'deleted_at',

        ]);

        $input['title'] = Str::teaser($this->input('title'), 255, '');
        $input['slug'] = $this->input('slug') ?: Str::slug($this->input('title'), '-', setting('system.translite_code', 'ru__gost_2000_b'));

        $input['description'] = Str::teaser($this->input('description'));
        $input['keywords'] = Str::teaser($this->input('keywords'), 255, '');
        $input['info'] = $this->prepareContent($this->input('info'));

        if (! empty($input['alt_url'])) {
            $input['alt_url'] = filter_var($input['alt_url'], FILTER_SANITIZE_URL, FILTER_FLAG_EMPTY_STRING_NULL);
        }

        $this->replace($input)
            ->merge([
                // Default value to checkbox.
                'show_in_menu' => $this->input('show_in_menu', false),

            ]);
    }

    protected function prepareContent(string $content = null): string
    {
        if (is_null($content)) {
            return '';
        }

        $content = DOMManipulator::removeEmoji($content);

        return DOMManipulator::wrapAsDocument($content)
            ->revisionPreTag()
            ->remove('script');
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
                'bail',
                'required',
                'string',
                'max:255',
                'alpha_dash',

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
