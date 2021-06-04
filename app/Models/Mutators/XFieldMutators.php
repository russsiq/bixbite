<?php

namespace App\Models\Mutators;

use Illuminate\Support\Str;

/**
 * @property-read string $inline_html_flags Get inline html flags attribute.
 * @property-read array $raw_html_flags Get raw html flags attribute.
 *
 * @see \App\Models\XField
 */
trait XFieldMutators
{
    /**
     * Get inline html flags attribute.
     *
     * @return string
     */
    public function getInlineHtmlFlagsAttribute(): string
    {
        $attributes = $this->html_flags;

        if ($attributes->isEmpty()) {
            return '';
        }

        return $attributes->map(function ($attribute) {
            return sprintf('%s="%s"', $attribute['key'], $attribute['value']);
        });
    }

    /**
     * Get raw html flags attribute.
     *
     * @return array
     */
    public function getRawHtmlFlagsAttribute(): array
    {
        return array_column($this->html_flags->toArray(), 'value', 'key');
    }

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
