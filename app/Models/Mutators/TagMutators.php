<?php

namespace App\Models\Mutators;

/**
 * @property-read ?string $url Get the tag URL.
 */
trait TagMutators
{
    /**
     * Get the tag URL.
     *
     * @return string|null
     */
    public function getUrlAttribute(): ?string
    {
        if (! $this->exists) {
            return null;
        }

        return route('tags.tag', $this);
    }
}
