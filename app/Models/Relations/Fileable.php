<?php

namespace BBCMS\Models\Relations;

// Сторонние зависимости.
use BBCMS\Models\File;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Fileable
{
    /**
     * Получить все файлы, прикрепленные к модели.
     * @return MorphMany
     */
    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'attachment', 'attachment_type', 'attachment_id', 'id');
    }

    /**
     * Получить коллекцию изображений, прикрепленных к модели.
     * @return Collection
     */
    public function getImagesAttribute(): Collection
    {
        return $this->files->where('type', 'image');
    }

    /**
     * Получить основное изображение, закрепленное за сущностью.
     * @return File
     */
    public function getImageAttribute(): ?File
    {
        return $this->images->where('id', $this->image_id)->first();
    }
}
