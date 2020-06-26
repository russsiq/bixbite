<?php

namespace App\Models\Mutators;

trait TagMutators
{
    /**
     * Получить атрибут `url`.
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return route('tags.tag', $this);
    }
}
