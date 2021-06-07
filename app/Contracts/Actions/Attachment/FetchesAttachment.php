<?php

namespace App\Contracts\Actions\Attachment;

use App\Models\Attachment;
use Illuminate\Contracts\Pagination\Paginator;

interface FetchesAttachment
{
    /**
     * Validate query parameters and return a specified attachment.
     *
     * @param  integer  $id
     * @param  array  $input
     * @return Attachment
     */
    public function fetch(int $id, array $input): Attachment;

    /**
     * Validate query parameters and return a collection of attachments.
     *
     * @param  array  $input
     * @return Paginator
     */
    public function fetchCollection(array $input): Paginator;
}
