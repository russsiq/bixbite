<?php

namespace App\Models\Relations;

use App\Models\Attachment;
use App\Models\Contracts\AttachableContract;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property-read EloquentCollection|Attachment[] $attachments Get all of the current model attachments.
 * @property-read EloquentCollection|Attachment[] $images Get all images attached to the current model.
 * @property-read Attachment|null $image Get the first image attached to the current model.
 */
trait AttachableTrait
{
    /**
     * Boot the Attachable trait for a model.
     *
     * @return void
     */
    public static function bootAttachableTrait(): void
    {
        static::deleting(function (AttachableContract $attachable) {
            $attachable->attachments()->get()->each->delete();
        });
    }

    /**
     * Get all of the current model attachments.
     *
     * @return MorphMany
     */
    public function attachments(): MorphMany
    {
        return $this->morphMany(
            Attachment::class,      // $related
            'attachable',           // $name
            'attachable_type',      // $type
            'attachable_id',        // $id
            $this->getKeyName(),    // $localKey
        );
    }

    /**
     * Get all images attached to the current model.
     *
     * @return EloquentCollection
     */
    public function getImagesAttribute(): EloquentCollection
    {
        return $this->attachments->where('type', 'image');
    }

    /**
     * Get the first image attached to the current model.
     *
     * @return Attachment|null
     */
    public function getImageAttribute(): ?Attachment
    {
        if ($this->exists
            && array_key_exists('image_id', $this->attributes)) {
            return $this->images->where('id', $this->image_id)->first();
        }

        return null;
    }
}
