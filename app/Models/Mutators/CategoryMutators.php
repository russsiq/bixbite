<?php

namespace BBCMS\Models\Mutators;

trait CategoryMutators
{
    public function setSlugAttribute($value)
    {
        // $this->attributes['slug'] = string_slug((is_null($value) ? $this->title : $value), '-');
        $this->attributes['slug'] = string_slug($value ?? $this->title);
    }

    public function getUrlAttribute()
    {
        return empty($this->alt_url) ? route(/*$this->table*/ "articles.category", ['category'=> $this->path]) : $this->alt_url;
    }

    public function getRootAttribute()
    {
        return !$this->parent_id;
    }

    public function getPathAttribute($values)
    {
        return $this->slug;

        $parents = collect([]);
        $parents->prepend($this);
        $parent = $this->parent;

        while (!is_null($parent)) {
            $parents->prepend($parent);
            $parent = $parent->parent;
        }

        return $parents->pluck('slug')->implode('/');
    }
}
