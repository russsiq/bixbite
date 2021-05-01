<?php

namespace App\Models\Mutators;

trait CategoryMutators
{
    public function getUrlAttribute()
    {
        return $this->alt_url ?? route(/*$this->table*/ "articles.category", ['category'=> $this->path]);
    }

    public function getEditPageAttribute()
    {
        return $this->id ? url('/dashboard/categories/'.$this->id.'/edit') : null;
    }

    public function getIsRootAttribute()
    {
        return ! $this->parent_id;
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
