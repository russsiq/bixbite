<?php

namespace App\Models\Relations;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read User $user Get the user that owns the current model instance.
 */
trait BelongsToUserTrait
{
    /**
     * Get the user that owns the current model instance.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class, // $related
            'user_id',   // $foreignKey
            'id',        // $ownerKey
            'user',      // $relation
        );
    }
}
