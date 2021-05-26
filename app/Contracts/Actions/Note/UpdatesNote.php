<?php

namespace App\Contracts\Actions\Note;

use App\Models\Note;

interface UpdatesNote
{
    /**
     * Validate and update the given note.
     *
     * @param  Note  $note
     * @param  array  $input
     * @return Note
     */
    public function update(Note $note, array $input): Note;
}
