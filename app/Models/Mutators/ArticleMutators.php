<?php

namespace BBCMS\Models\Mutators;

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

    public function getCreatedAttribute()
    {
        return is_null($this->attributes['created_at']) ? null : $this->created_at->diffForHumans();
    }

    public function getUpdatedAttribute()
    {
        return is_null($this->attributes['updated_at']) ? null : $this->updated_at->diffForHumans();
    }

    public function getTeaserAttribute()
    {
        return $this->attributes['teaser'] ?? teaser($this->content, setting('articles.teaser_length', 150));
    }
}
