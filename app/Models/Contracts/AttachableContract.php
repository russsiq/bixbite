<?php

namespace App\Models\Contracts;

use App\Models\Attachment;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface AttachableContract
{
    public function attachments(): MorphMany;

    public function getImagesAttribute(): EloquentCollection;

    public function getImageAttribute(): ?Attachment;
}
