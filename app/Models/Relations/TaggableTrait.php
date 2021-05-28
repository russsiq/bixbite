<?php

namespace App\Models\Relations;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property-read EloquentCollection|Tag[] $tags Get all of the tags for the current model.
 */
trait TaggableTrait
{
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
