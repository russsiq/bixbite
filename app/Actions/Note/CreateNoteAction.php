<?php

namespace App\Actions\Note;

use App\Contracts\Actions\Note\CreatesNote;
use App\Models\Note;

class CreateNoteAction extends NoteActionAbstract implements CreatesNote
{
    /**
     * Validate and create a newly note.
     *
     * @param  array  $input
     * @return Note
     */
    public function create(array $input): Note
    {
        $this->authorize('create', Note::class);

        $this->note = Note::create(
            $this->validate(
                $this->prepareForValidation($input)
            )
        )->fresh();

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
            $this->userIdRules(), // Only when create.
            // $this->imageIdRules(), // Only when update.
            $this->titleRules(),
            $this->slugRules(),
            $this->descriptionRules(),
            // $this->isCompletedRules(), // Only when update.
        );
    }
}
