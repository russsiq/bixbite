<?php

namespace App\Models\Mutators;

// Сторонние зависимости.
use Illuminate\Support\Str;

trait XFieldMutators
{
    /**
     * Задать атрибуту `name` значение.
     * @param  string  $value
     * @return void
     */
    public function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = trim(Str::start($value, $this->xPrefix()), '_');
    }

    /**
     * Получить значение атрибута `name`.
     * @return string
     */
    public function getNameAttribute(): string
    {
        return trim(Str::start($this->attributes['name'], $this->xPrefix()), '_');
    }
}
