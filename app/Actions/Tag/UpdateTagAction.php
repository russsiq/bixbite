<?php

namespace App\Actions\Tag;

use App\Contracts\Actions\Tag\UpdatesTag;
use App\Models\Tag;

class UpdateTagAction extends TagActionAbstract implements UpdatesTag
{
    protected $stopOnFirstFailure = true;

    /**
     * Validate and update the given tag.
     *
     * @param  Tag  $tag
     * @param  array  $input
     * @return Tag
     */
    public function update(Tag $tag, array $input): Tag
    {
        $this->authorize(
            'update', $this->tag = $tag
        );

        $this->tag->update(
            $this->validate(
                $this->prepareForValidation($input)
            )
        );

        return $this->tag;
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    protected function rules(): array
    {
        return array_merge(
            $this->titleRules(),
            $this->slugRules(),
        );
    }
}
