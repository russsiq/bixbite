<?php

namespace App\Actions\Tag;

use App\Actions\ActionAbstract;
use App\Models\Contracts\TaggableContract;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

abstract class TagActionAbstract extends ActionAbstract
{
    protected ?Tag $tag = null;
    protected ?TaggableContract $taggable = null;

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    abstract protected function rules(): array;

    /**
     * Prepare the data for validation.
     *
     * @param  array  $input
     * @return array
     */
    protected function prepareForValidation(array $input): array
    {
        $input['title'] = Str::teaser($input['title'] ?? null, 255, '');

        if (empty($input['slug']) && ! empty($input['title'])) {
            $language = setting('system.translite_code', 'ru__gost_2000_b');

            $input['slug'] = Str::slug($input['title'], '-', $language);
        }

        return $input;
    }

    /**
     * Get the validation rules used to validate `title` field.
     *
     * @return array
     */
    protected function titleRules(): array
    {
        return [
            'title' => [
                'bail',
                'required',
                'string',
                'max:255',
                with(
                    Rule::unique(Tag::TABLE, 'title'),
                    fn (Unique $unique) => $this->tag instanceof Tag
                        ? $unique->ignore($this->tag->id, 'id')
                        : $unique
                ),
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `slug` field.
     *
     * @return array
     */
    protected function slugRules(): array
    {
        return [
            'slug' => [
                'bail',
                'required',
                'string',
                'max:255',
                'alpha_dash',
                with(
                    Rule::unique(Tag::TABLE, 'slug'),
                    fn (Unique $unique) => $this->tag instanceof Tag
                        ? $unique->ignore($this->tag->id, 'id')
                        : $unique
                ),
            ],
        ];
    }
}
