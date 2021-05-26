<?php

namespace App\Actions\Note;

use App\Contracts\Actions\Note\UpdatesNote;
use App\Models\Note;

class UpdateNoteAction extends NoteActionAbstract implements UpdatesNote
{
    /**
     * Validate and update the given note.
     *
     * @param  Note  $note
     * @param  array  $input
     * @return Note
     */
    public function update(Note $note, array $input): Note
    {
        $this->authorize(
            'update', $this->note = $note
        );

        $this->note->update(
            $this->validate(
                $this->prepareForValidation($input)
            )
        );

        return $this->note;
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    protected function rules(): array
    {
        return array_merge(
            // $this->userIdRules(), // Only when create.
            $this->imageIdRules(), // Only when update.
            $this->titleRules(),
            $this->slugRules(),
            $this->descriptionRules(),
            $this->isCompletedRules(), // Only when update.
        );
    }
}
