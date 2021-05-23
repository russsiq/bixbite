<?php

namespace App\Models\Relations;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read \App\Models\User $user
 */
trait BelongsToUser
{
    /**
     * Get the user that owns the current model instance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'user');
    }
}
