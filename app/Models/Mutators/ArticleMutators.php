<?php

namespace BBCMS\Models\Mutators;

/**
 * https://schema.org/Article
 * https://github.com/russsiq/art-schema-markup
 */

trait ArticleMutators
{
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = string_slug($value ?? $this->title);
    }

    public function getUrlAttribute()
    {
        return action('ArticlesController@article', [$this->categories->pluck('slug')->implode('/'), $this->id, $this->slug]);
    }

    public function getTeaserAttribute()
    {
        return $this->attributes['teaser'] ?? teaser($this->content, setting('articles.teaser_length', 150));
    }

    public function getViewsAttribute()
    {
        return setting('articles.views_used', false) ? $this->attributes['views'] : null;
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
     * @return mixed
     */
    public function getDatePublishedAttribute() // ToDo: create column published_at.
    {
        return empty($this->attributes['created_at']) ? null : $this->created_at->toIso8601String();
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
