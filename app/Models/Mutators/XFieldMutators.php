<?php

namespace App\Models\Mutators;

use Illuminate\Support\Str;

trait XFieldMutators
{
    /**
     * Задать атрибуту `name` значение.
     *
     * @param  string  $name
     * @return void
     */
    public function setNameAttribute(string $name): void
    {
        $this->attributes['name'] = $this->normalizeNameAttributePrefix($name);
    }

    /**
     * Получить значение атрибута `name`.
     *
     * @return string
     */
    public function getNameAttribute(): string
    {
        return $this->normalizeNameAttributePrefix($this->attributes['name']);
    }

    /**
     * Нормализовать префикс атрибута `name`.
     *
     * @param  string $name
     * @return string
     */
    public function normalizeNameAttributePrefix(string $name): string
    {
        return trim(Str::start($name, $this->xPrefix()), '_');
    }
}
