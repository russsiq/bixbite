<?php

namespace App\Models\Observers;

use App\Models\Attachment;

class AttachmentObserver extends BaseObserver
{
    /**
     * Handle the Attachment "updating" event.
     *
     * @param  Attachment  $attachment
     * @return void
     */
    public function updating(Attachment $attachment): void
    {
        if ($this->originalPath($attachment) !== $attachment->path()) {
            $disk = $attachment->storageDisk();
            $disk->rename($this->originalPath($attachment), $attachment->path());

            if ('image' === $attachment->type) {
                foreach ($attachment->thumbSizes() as $size => $value) {
                    if ($disk->exists($old_path = $this->originalPath($attachment, $size))) {
                        $disk->rename($old_path, $attachment->path($size));
                    }
                }
            }
        }
    }

    /**
     * Handle the Attachment "deleting" event.
     *
     * @param  Attachment  $attachment
     * @return void
     */
    public function deleting(Attachment $attachment): void
    {
        $disk = $attachment->storageDisk();
        $disk->delete($attachment->path());

        if ('image' === $attachment->type) {
            // Automatically seting null image_id, see in migration to attachment.
            foreach ($attachment->thumbSizes() as $size => $value) {
                $disk->delete($attachment->getPathAttribute($size));
            }
        }
    }

    /**
     * Get original path to file.
     *
     * @param  string|null $thumbSize
     * @return string
     */
    protected function originalPath(Attachment $attachment, string $thumbSize = null)
    {
        return $attachment->getOriginal('type')
            .DS.$attachment->getOriginal('folder')
            .($thumbSize ? DS.$thumbSize : '')
            .DS.$attachment->getOriginal('name').'.'.$attachment->getOriginal('extension');
    }
}
