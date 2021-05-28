<?php

namespace App\Models\Contracts;

interface MustBeOnlineContract
{
    public function fixActivity(): void;

    public function getIsOnlineAttribute(): bool;

    public function getLastActiveAttribute(): ?string;
}
