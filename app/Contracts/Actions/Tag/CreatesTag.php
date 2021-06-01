<?php

namespace App\Contracts\Actions\Tag;

use App\Models\Tag;

interface CreatesTag
{
    /**
     * Validate and create a newly tag.
     *
     * @param  array  $input
     * @return Tag
     */
    public function create(array $input): Tag;
}
