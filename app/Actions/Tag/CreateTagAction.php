<?php

namespace App\Actions\Tag;

use App\Contracts\Actions\Tag\CreatesTag;
use App\Models\Tag;

class CreateTagAction extends TagActionAbstract implements CreatesTag
{
    protected $stopOnFirstFailure = true;

    /**
     * Validate and create a newly tag.
     *
     * @param  array  $input
     * @return Tag
     */
    public function create(array $input): Tag
    {
        $this->authorize('create', Tag::class);

        $this->tag = Tag::create(
                $this->validate(
                    $this->prepareForValidation($input)
                )
            )
            ->fresh();

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
