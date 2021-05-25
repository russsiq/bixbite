<?php

namespace App\Models\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read User $user
 */
interface BelongsToUserContract
{
    /**
     * Get the user that owns the current model instance.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo;
}
