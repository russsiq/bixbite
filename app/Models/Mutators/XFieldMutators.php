<?php

namespace App\Models\Mutators;

use Illuminate\Support\Str;

trait XFieldMutators
{
    /**
     * Set the extra field `name`.
     *
     * @param  string  $name
     * @return void
     */
    public function setNameAttribute(string $name): void
    {
        $this->attributes['name'] = $this->normalizeNameAttributePrefix($name);
    }

    /**
     * Get the extra field `name`.
     *
     * @return string
     */
    public function getNameAttribute(): string
    {
        return $this->normalizeNameAttributePrefix($this->attributes['name']);
    }

    /**
     * Normalize the `name` attribute prefix.
     *
     * @param  string  $name
     * @return string
     */
    public function normalizeNameAttributePrefix(string $name): string
    {
        return trim(Str::start($name, $this->xPrefix()), '_');
    }
}
