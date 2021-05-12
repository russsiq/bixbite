<?php

namespace App\Models\Mutators;

use App\Http\Controllers\ArticlesController;
use Illuminate\Support\Str;

/**
 * https://schema.org/Article
 * https://github.com/russsiq/art-schema-markup
 */
trait ArticleMutators
{
    /**
     * $this->is_published
     *
     * @return boolean
     */
    public function getIsPublishedAttribute(): bool
    {
        return 2 === $this->state;
    }

    /**
     * $this->raw_content
     *
     * @return string
     */
    public function getRawContentAttribute(): string
    {
        return (string) $this->attributes['content'];
    }

    public function getUrlAttribute()
    {
        return ($this->id and $this->categories->count() > 0  and $this->is_published)
            ? action([ArticlesController::class, 'article'], [
                $this->categories->pluck('slug')->implode('_'),
                $this->id,
                $this->slug
            ]) : null;
    }

    public function getEditPageAttribute(): ?string
    {
        return $this->id ? route('dashboard')."/$this->table/$this->id/edit" : null;
    }

    public function getViewsAttribute()
    {
        return setting('articles.views_used', true) ? $this->attributes['views'] : null;
    }

    /**
     * Get `created` in humans date format.
     * @return mixed
     */
    public function getCreatedAttribute()
    {
        return empty($this->attributes['created_at']) ? null : $this->created_at->diffForHumans();
    }

    /**
     * Get `updated` in humans date format.
     * @return mixed
     */
    public function getUpdatedAttribute()
    {
        return empty($this->attributes['updated_at']) ? null : $this->updated_at->diffForHumans();
    }

    /**
     * Get `date_created` in ISO 8601 date format.
     * @return mixed
     */
    public function getDateCreatedAttribute()
    {
        return empty($this->attributes['created_at']) ? null : $this->created_at->toIso8601String();
    }

    /**
     * Get `date_published` in ISO 8601 date format.
     *
     * @return mixed
     */
    public function getDatePublishedAttribute()
    {
        return empty($this->attributes['published_at']) ? null : $this->published_at->toIso8601String();
    }

    /**
     * Get `date_modified` in ISO 8601 date format.
     * @return mixed
     */
    public function getDateModifiedAttribute()
    {
        return empty($this->attributes['updated_at']) ? null : $this->updated_at->toIso8601String();
    }
}
