<?php

namespace App\Models\Mutators;

use App\Models\Article;
use Illuminate\Support\Carbon;

/**
 * @property-read ?string $created Get the difference in a human readable format in the current locale.
 * @property-read ?string $edit_page_url Get the URL of the article edit page.
 * @property-read bool $is_published Determine that the article has been published.
 * @property-read string $raw_content Get the raw content of the article.
 * @property-read ?string $updated Get the difference in a human readable format in the current locale.
 * @property-read ?string $url Get the article URL.
 * @property-read integer $views Get the number of views for an article only if this feature is used.
 */
trait ArticleMutators
{
    /**
     * Get the difference in a human readable format in the current locale.
     *
     * @return string|null
     */
    public function getCreatedAttribute(): ?string
    {
        if ($this->created_at instanceof Carbon) {
            return $this->created_at->diffForHumans();
        }

        return null;
    }

    /**
     * Get the URL of the article edit page.
     *
     * @return string|null
     */
    public function getEditPageUrlAttribute(): ?string
    {
        if (! $this->exists) {
            return null;
        }

        return route('articles.edit', $this);
    }

    /**
     * Determine that the article has been published.
     *
     * @return boolean
     */
    public function getIsPublishedAttribute(): bool
    {
        return Article::STATE['published'] === $this->state
            && $this->categories->count() > 0;
    }

    /**
     * Get the raw content of the article.
     *
     * @return string
     */
    public function getRawContentAttribute(): string
    {
        return (string) $this->attributes['content'];
    }

    /**
     * Get the difference in a human readable format in the current locale.
     *
     * @return string|null
     */
    public function getUpdatedAttribute(): ?string
    {
        if ($this->updated_at instanceof Carbon) {
            return $this->updated_at->diffForHumans();
        }

        return null;
    }

    /**
     * Get the article URL.
     *
     * @return string|null
     */
    public function getUrlAttribute(): ?string
    {
        if ($this->exists && $this->is_published) {
            return route('articles.article', [
                $this->categories->pluck('slug')->implode('_'),
                $this->id,
                $this->slug
            ]);
        }

        return null;
    }

    /**
     * Get the number of views for an article only if this feature is used.
     *
     * @return integer
     */
    public function getViewsAttribute(): int
    {
        return $this->setting->views_used ? $this->attributes['views'] : 0;
    }
}
