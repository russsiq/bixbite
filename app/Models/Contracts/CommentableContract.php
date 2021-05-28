<?php

namespace App\Models\Contracts;

use App\Models\Collections\CommentCollection;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface CommentableContract
{
    public function comments(): MorphMany;

    public function getComments(bool $nested = false): CommentCollection;

    public function getCommentStoreUrlAttribute(): ?string;
}
