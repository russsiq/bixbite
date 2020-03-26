<?php

namespace App\Models\Observers;

// Сторонние зависимости.
use App\Models\File;

/**
 * Наблюдатель модели `Article`.
 */
class FileObserver extends BaseObserver
{
    /**
     * Обработать событие `updating` модели.
     * @param  File  $file
     * @return void
     */
    public function updating(File $file): void
    {
        if ($this->originalPath($file) !== $file->path()) {
            $disk = $file->storageDisk();
            $disk->rename($this->originalPath($file), $file->path());

            if ('image' == $file->type) {
                foreach ($file->thumbSizes() as $size => $value) {
                    if ($disk->exists($old_path = $this->originalPath($file, $size))) {
                        $disk->rename($old_path, $file->path($size));
                    }
                }
            }
        }
    }

    /**
     * Обработать событие `deleting` модели.
     * @param  File  $file
     * @return void
     */
    public function deleting(File $file): void
    {
        $disk = $file->storageDisk();
        $disk->delete($file->path());

        if ('image' === $file->type) {
            // Automatically seting null image_id, see in migration to attachment.
            foreach ($file->thumbSizes() as $size => $value) {
                $disk->delete($file->getPathAttribute($size));
            }
        }
    }

    /**
     * Get original path to file.
     *
     * @param  string|null $thumbSize
     * @return string
     */
    protected function originalPath(File $file, string $thumbSize = null)
    {
        return $file->getOriginal('type')
            .DS.$file->getOriginal('category')
            .($thumbSize ? DS.$thumbSize : '')
            .DS.$file->getOriginal('name').'.'.$file->getOriginal('extension');
    }
}
