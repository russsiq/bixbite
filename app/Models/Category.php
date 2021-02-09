<?php

namespace App\Models;

use App\Models\Collections\CategoryCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'parent_id',
        // 'position',
        'title', 'slug', 'alt_url', 'info',
        'meta_description', 'meta_keywords', 'meta_robots',
        'show_in_menu',
        'paginate', 'template', 'order_by', 'direction',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'parent_id' => 'integer',
        'position' => 'integer',

        'title' => 'string',
        'slug' => 'string',
        'alt_url' => 'string',
        'info' => 'string',

        'meta_description' => 'string',
        'meta_keywords' => 'string',
        'meta_robots' => 'string',

        'show_in_menu' => 'boolean',
        'paginate' => 'integer',
        'template' => 'string',
        'order_by' => 'string',
        'direction' => 'string',
    ];

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return CategoryCollection
     */
    public function newCollection(array $models = []): CategoryCollection
    {
        return new CategoryCollection($models);
    }

    public function articles(): MorphToMany
    {
        return $this->morphedByMany(Article::class, 'categoryable');
    }
}
