<?php

namespace App\Contracts\Actions\Tag;

interface DetachesTag
{
    /**
     * Validate and detach the tag.
     *
     * @param  string  $taggable_type
     * @param  integer  $taggable_id
     * @param  integer  $tag_id
     * @return void
     */
    public function detach(string $taggable_type, int $taggable_id, int $tag_id): void;
}
