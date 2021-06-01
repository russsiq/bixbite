<?php

namespace App\Contracts\Actions\Tag;

use App\Models\Tag;

interface DeletesTag
{
    /**
     * Delete the given tag.
     *
     * @param  Tag  $tag
     * @return int  Remote tag ID.
     */
    public function delete(Tag $tag): int;
}
