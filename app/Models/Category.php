<?php

namespace App\Models;

use App\Models\Collections\CategoryCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;

/**
 * Category model.
 *
 * @property int $id
 * @property int $parent_id
 * @property int $position
 * @property string $title
 * @property string $slug
 * @property string|null $alt_url
 * @property string|null $info
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property string $meta_robots
 * @property bool $show_in_menu
 * @property int $paginate
 * @property string|null $template
 * @property string $order_by
 * @property string $direction
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Category extends Model
{
    use HasFactory;

    const TABLE = 'categories';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = self::TABLE;

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'parent_id' => 0,
        'position' => 0,
        'alt_url' => null,
        'info' => null,
        'meta_description' => null,
        'meta_keywords' => null,
        'meta_robots' => 'all',
        'show_in_menu' => true,
        'paginate' => 15,
        'template' => null,
        'order_by' => 'id',
        'direction' => 'desc',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'parent_id', 'position',
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
