<?php

namespace App\Actions\Tag;

use App\Contracts\Actions\Tag\DeletesTag;
use App\Models\Tag;

class DeleteTagAction extends TagActionAbstract implements DeletesTag
{
    /**
     * Delete the given tag.
     *
     * @param  Tag  $tag
     * @return int  Remote tag ID.
     */
    public function delete(Tag $tag): int
    {
        $this->authorize(
            'delete', $this->tag = $tag->fresh()
        );

        $id = $tag->id;

        $tag->delete();

        return $id;
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            //
        ];
    }
}
