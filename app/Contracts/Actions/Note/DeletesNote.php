<?php

namespace App\Contracts\Actions\Note;

use App\Models\Note;

interface DeletesNote
{
    /**
     * Delete the given note.
     *
     * @param  Note  $note
     * @return int  Remote note ID.
     */
    public function delete(Note $note): int;
}
