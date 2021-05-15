<?php

namespace App\Contracts\Responses;

use Illuminate\Contracts\Support\Responsable;

interface SuccessfulCommentCreateResponseContract extends Responsable
{
    /**
     * The response status language key.
     *
     * @const string[]
     */
    public const STATUSES = [
        'Your comment was successfully added, however this item requires that all comments be moderated by the owner before they are publicly displayed.',
        'Your comment was successfully added.'
    ];

    /**
     * Get the response status message.
     *
     * @return string
     */
    public function statusMessage(): string;
}
