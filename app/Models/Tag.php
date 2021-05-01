<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Tag model.
 *
 * @property-read int $id
 * @property-read string $title
 * @property-read string $slug
 *
 * @property-read string $url
 */
class Tag extends Model
{
    use Mutators\TagMutators;
    use Scopes\TagScopes;
    use HasFactory;

    public const TABLE = 'tags';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = self::TABLE;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'title' => '',
        'slug' => '',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'title' => 'string',
        'slug' => 'string',
        'url' => 'string',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'title',
        'slug',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get all of the articles for the tag.
     *
     * @return MorphToMany
     */
    public function articles(): MorphToMany
    {
        return $this->morphedByMany(
            /* $related */ Article::class,
            /* $name */ 'taggable',
            /* $table */ 'taggables',
            /* $foreignPivotKey */ 'tag_id',
            /* $relatedPivotKey */ 'taggable_id'
        );
    }

    /**
     * Remove unused tags.
     *
     * @return void
     */
    public function reIndex(): void
    {
        // $tags = $this->select([
        //         'tags.id',
        //         'taggables.tag_id as pivot_tag_id',
        //     ])
        //     ->join('taggables', 'tags.id', '=', 'taggables.tag_id')
        //     ->get()
        //     ->modelKeys();
        //
        // $this->diff($tags)->delete();

        $tags = $this->select([
                'tags.id',
                'taggables.tag_id as pivot_tag_id',
            ])
            ->join('taggables', 'tags.id', '=', 'taggables.tag_id')
            ->get()
            ->keyBy('id')
            ->all();

        $this->whereNotIn('id', array_keys($tags))
            ->delete();
    }
}
