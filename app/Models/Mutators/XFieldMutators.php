<?php

namespace App\Models\Mutators;

// Сторонние зависимости.
use Illuminate\Support\Str;

trait XFieldMutators
{
    public function setNameAttribute(string $value)
    {
        $this->attributes['name'] = trim(Str::start($value, $this->xPrefix()), '_');
    }

    public function getNameAttribute()
    {
        return trim(Str::start($this->attributes['name'], $this->xPrefix()), '_');
    }
}
