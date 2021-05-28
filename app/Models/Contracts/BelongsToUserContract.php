<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface BelongsToUserContract
{
    public function user(): BelongsTo;
}
