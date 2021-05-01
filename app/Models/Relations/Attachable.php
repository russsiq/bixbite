<?php

namespace App\Models\Relations;

use App\Models\Attachment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Attachable
{
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable', 'attachable_type', 'attachable_id', 'id');
    }

    public function getImagesAttribute(): Collection
    {
        return $this->attachments->where('type', 'image');
    }

    public function getImageAttribute(): ?Attachment
    {
        return $this->images->where('id', $this->image_id)->first();
    }
}
