<?php

namespace App\Contracts\Actions\Note;

use App\Models\Note;
use Illuminate\Contracts\Pagination\Paginator;

interface FetchesNote
{
    /**
     * Validate query parameters and return a specified note.
     *
     * @param  integer  $id
     * @param  array  $input
     * @return Note
     */
    public function fetch(int $id, array $input): Note;

    /**
     * Validate query parameters and return a collection of notes.
     *
     * @param  array  $input
     * @return Paginator
     */
    public function fetchCollection(array $input): Paginator;
}
