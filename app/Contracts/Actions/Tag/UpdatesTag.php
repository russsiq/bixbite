<?php

namespace App\Contracts\Actions\Tag;

use App\Models\Tag;

interface UpdatesTag
{
    /**
     * Validate and update the given tag.
     *
     * @param  Tag  $tag
     * @param  array  $input
     * @return Tag
     */
    public function update(Tag $tag, array $input): Tag;
}
