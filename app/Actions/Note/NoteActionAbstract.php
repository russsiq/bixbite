<?php

namespace App\Actions\Note;

use App\Actions\ActionAbstract;
use App\Models\Attachment;
use App\Models\Note;
use App\Rules\SqlTextLengthRule;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Validation\Rules\Unique;
use Russsiq\DomManipulator\Facades\DOMManipulator;

abstract class NoteActionAbstract extends ActionAbstract
{
    protected ?Note $note = null;

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
        $input['user_id'] = $this->user()->getAuthIdentifier();
        $input['image_id'] = $input['image_id'] ?? null;
        $input['title'] = Str::teaser($input['title'] ?? null, 255, '');

        // if (empty($input['slug']) && ! empty($input['title'])) {
            $input['slug'] = Str::slug(
                $input['title'], '-', setting('system.translite_code', 'ru__gost_2000_b')
            );
        // }

        $input['description'] = $this->prepareContent($input['description'] ?? null);
        $input['is_completed'] = $input['is_completed'] ?? false;

        return $input;
    }

    /**
     * Prepare the content for validation.
     *
     * @param  string|null  $content
     * @return string
     */
    protected function prepareContent(?string $content): string
    {
        if (is_null($content)) {
            return (string) $content;
        }

        $content = DOMManipulator::removeEmoji($content);

        return DOMManipulator::wrapAsDocument($content)
            ->revisionPreTag()
            ->remove('script');
    }

    /**
     * Get the validation rules used to validate `user_id` field.
     *
     * @return array
     */
    protected function userIdRules(): array
    {
        if ($this->note instanceof Note) {
            return [];
        }

        return [
            'user_id' => [
                'bail',
                'required',
                'integer',
                'min:1',
                "size:{$this->user()->getAuthIdentifier()}",
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `image_id` field.
     *
     * @return array
     */
    protected function imageIdRules(): array
    {
        if (is_null($this->note)) {
            return [];
        }

        return [
            'image_id' => [
                'bail',
                'sometimes',
                'nullable',
                'integer',
                'min:1',
                Rule::exists(Attachment::TABLE, 'id')
                    ->where('type', 'image')
                    ->where('attachable_id', $this->note->id)
                    ->where('attachable_type', Note::TABLE)
            ],
        ];
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
                    Rule::unique(Note::TABLE, 'slug'),
                    fn (Unique $unique) => $this->note instanceof Note
                        ? $unique->ignore($this->note->id, 'id')
                        : $unique
                ),
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `description` field.
     *
     * @return array
     */
    protected function descriptionRules(): array
    {
        return [
            'description' => [
                'sometimes',
                'nullable',
                'string',
                $this->container->make(SqlTextLengthRule::class),
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `is_completed` field.
     *
     * @return array
     */
    protected function isCompletedRules(): array
    {
        if (is_null($this->note)) {
            return [];
        }

        return [
            'is_completed' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}
