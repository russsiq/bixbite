<?php

namespace App\Models\Relations;

use App\Models\Contracts\TaggableContract;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property-read EloquentCollection|Tag[] $tags Get all of the tags for the current model.
 */
trait TaggableTrait
{
    /**
     * Boot the Taggable trait for a model.
     *
     * @return void
     */
    public static function bootTaggableTrait(): void
    {
        static::registerModelEvent('booted', static function (TaggableContract $taggable) {
            $relation = (string) $taggable->getTable();

            Tag::resolveRelationUsing(
                $relation,
                fn (Tag $tagModel): MorphToMany => $tagModel->morphedByMany(
                    $taggable::class,   // $related
                    'taggable',         // $name
                    'taggables',        // $table
                    'tag_id',           // $foreignPivotKey
                    'taggable_id',      // $relatedPivotKey
                )
            );
        });

        static::deleting(function (TaggableContract $taggable) {
            $taggable->tags()->detach();
        });
    }

    /**
     * Get all of the tags for the current model.
     *
     * @return MorphToMany
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(
            Tag::class,     // $related
            'taggable',     // $name
            'taggables',    // $table
            'taggable_id',  // $foreignPivotKey
            'tag_id',       // $relatedPivotKey
            'id',           // $parentKey
            'id',           // $relatedKey
        );
    }
}
