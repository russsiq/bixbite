<?php

namespace App\Actions\Note;

use App\Contracts\Actions\Note\DeletesNote;
use App\Models\Note;

class DeleteNoteAction extends NoteActionAbstract implements DeletesNote
{
    /**
     * Delete the given note.
     *
     * @param  Note  $note
     * @return int  Remote note ID.
     */
    public function delete(Note $note): int
    {
        $this->authorize(
            'delete', $this->note = $note->fresh()
        );

        $id = $note->id;

        $note->delete();

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
