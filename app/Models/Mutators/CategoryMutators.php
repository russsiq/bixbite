<?php

namespace App\Models\Mutators;

use App\Models\Category;
use RuntimeException;

/**
 * @property-read ?string $edit_page_url Get the URL of the category edit page.
 * @property-read boolean $is_root Determine that the current category is the root.
 * @property-read ?string $url Get the category URL.
 * @property-read ?string $path Get the requested relative path.
 */
trait CategoryMutators
{
    /**
     * Get the URL of the category edit page.
     *
     * @return string|null
     */
    public function getEditPageUrlAttribute(): ?string
    {
        if (! $this->exists) {
            return null;
        }

        return route('categories.edit', $this);
    }

    /**
     * Determine that the current category is the root.
     *
     * @return boolean
     */
    public function getIsRootAttribute(): bool
    {
        return empty($this->parent_id);
    }

    /**
     * Get the category URL.
     *
     * @return string|null
     */
    public function getUrlAttribute(): ?string
    {
        if (! $this->exists) {
            return null;
        }

        if (! empty($this->alt_url)) {
            return $this->alt_url;
        }

        return route('articles.category', [
            'category'=> $this->path,
        ]);
    }

    /**
     * Get the requested relative path.
     *
     * Currently only sibling categories are supported.
     *
     * @return string|null
     */
    public function getPathAttribute(): ?string
    {
        if (! $this->exists) {
            return null;
        }

        if (! empty($this->alt_url)) {
            throw new RuntimeException(trans(sprintf(
                'You are asking for a relative path, but the current category is an external resource link to [%s].',
                $this->alt_url
            )));
        }

        return $this->slug;

        $parents = collect([$this]);
        $parent = $this->parent;

        while (! empty($parent)) {
            $parents->prepend($parent);
            $parent = $parent->parent;
        }

        return $parents->pluck('slug')->implode('/');
    }
}
