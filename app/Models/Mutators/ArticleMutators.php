<?php

namespace BBCMS\Models\Mutators;

/**
 * https://schema.org/Article
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

    /**
     * Get `created` in humans date format.
     * @return mixed
     */
    public function getCreatedAttribute()
    {
        return is_null($this->attributes['created_at']) ? null : $this->created_at->diffForHumans();
    }

    /**
     * Get `updated` in humans date format.
     * @return mixed
     */
    public function getUpdatedAttribute()
    {
        return is_null($this->attributes['updated_at']) ? null : $this->updated_at->diffForHumans();
    }

    /**
     * Get `date_created` in ISO 8601 date format.
     * @return mixed
     */
    public function getDateCreatedAttribute()
    {
        return is_null($this->attributes['created_at']) ? null : $this->created_at->toIso8601String();
    }

    /**
     * Get `date_published` in ISO 8601 date format.
     * @return mixed
     */
    public function getDateCreatedAttribute()
    {
        return is_null($this->attributes['published_at']) ? null : $this->published_at->toIso8601String();
    }

    /**
     * Get `date_modified` in ISO 8601 date format.
     * @return mixed
     */
    public function getDateModifiedAttribute()
    {
        return is_null($this->attributes['updated_at']) ? null : $this->updated_at->toIso8601String();
    }
}
