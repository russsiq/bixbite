<?php

namespace App\Actions\Note;

use App\Contracts\Actions\Note\FetchesNote;
use App\Models\Note;
use Illuminate\Contracts\Pagination\Paginator;

class FetchNoteAction extends NoteActionAbstract implements FetchesNote
{
    /**
     * Validate query parameters and return a specified note.
     *
     * @param  integer  $id
     * @param  array  $input
     * @return Note
     */
    public function fetch(int $id, array $input): Note
    {
        $this->note = Note::findOrFail($id);

        $this->authorize('view', $this->note);

        $this->note->load([
            'attachments',
        ]);

        return $this->note;
    }

    /**
     * Validate query parameters and return a collection of notes.
     *
     * @param  array  $input
     * @return Paginator
     */
    public function fetchCollection(array $input): Paginator
    {
        $this->authorize('viewAny', Note::class);

        /** @var \App\Models\User */
        $user = $this->user();

        return $user->notes()
            ->with([
                'attachments',
            ])
            ->paginate();
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    protected function rules(): array
    {
        return array_merge(
            //
        );
    }
}
