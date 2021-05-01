<?php

namespace App\Models\Relations;

use App\Models\Category;

trait Categoryable
{
    public function categories()
    {
        return $this->morphToMany(Category::class, 'categoryable', 'categoryables', 'categoryable_id', 'category_id')
            ->withPivot('is_main');
    }

    public function getCategoryAttribute(): Category
    {
        return $this->categories->where('is_main', true)->first() ?: $this->categories->first();
    }

    // public function getCategoriesAttribute()
    // {
    //     return cache()->rememberForever($this->id.'-categories', function () {
    //         return $this->categories()->get();
    //     });
    // }

    // /**
    //  * Get a attributes to first category
    //  *
    //  * @var array
    //  */
    // public function getCategoryAttribute()
    // {
    //     if(is_null($category = $this->categories->last())) {
    //         $category = new \stdClass();
    //         $category->title = 'No name';
    //         $category->slug = 'none';
    //         $category->alt_url = null;
    //         $category->template = null;
    //     }

    //     return $category;
    // }

    /*// Связь OneToOne
    public function category()
    {
        return $this->categories()->last();
        //return $this->belongsTo('App\Models\Category');
    }*/
}
