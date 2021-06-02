<?php

namespace App\Models\Contracts;

use App\Models\Collections\CommentCollection;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface CommentableContract
{
    /**
     * Allow comments for the current model.
     *
     * @const array
     */
    public const COMMENTS_ALLOWS = [
        'disable' => 0,
        'enable' => 1,
        'by_default' => 2,
    ];

    public function comments(): MorphMany;

    public function getComments(bool $nested = false): CommentCollection;

    public function getCommentStoreUrlAttribute(): ?string;
}
