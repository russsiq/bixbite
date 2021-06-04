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
        'Your comment has been successfully added to the moderation queue.',
        'Your comment was successfully added.'
    ];

    /**
     * Get the response status message.
     *
     * @return string
     */
    public function statusMessage(): string;
}
