<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Tag model.
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 *
 * @property-read \App\Models\Article $articles Get all of the articles for the tag.
 *
 * @method static \Database\Factories\TagFactory factory()
 *
 * @mixin \Illuminate\Database\Query\Builder
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Tag extends Model
{
    use HasFactory;
    use Mutators\TagMutators;
    use Scopes\TagScopes;

    /**
     * The default table associated with the model.
     *
     * @const string
     */
    public const TABLE = 'tags';

    /**
     * {@inheritDoc}
     */
    protected $table = self::TABLE;

    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected $attributes = [
        'title' => '',
        'slug' => '',
    ];

    /**
     * {@inheritDoc}
     */
    protected $appends = [
        'url',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'title' => 'string',
        'slug' => 'string',
        'url' => 'string',
    ];

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'title',
        'slug',
    ];

    /**
     * {@inheritDoc}
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
            Article::class, // $related
            'taggable',     // $name
            'taggables',    // $table
            'tag_id',       // $foreignPivotKey
            'taggable_id',  // $relatedPivotKey
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
