<?php

namespace App\Contracts\Actions\Note;

use App\Models\Note;

interface CreatesNote
{
    /**
     * Validate and create a newly note.
     *
     * @param  array  $input
     * @return Note
     */
    public function create(array $input): Note;
}
