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
            $disk->move($this->originalPath($attachment), $attachment->path());

            if ('image' === $attachment->type) {
                foreach ($attachment->thumbSizes() as $size => $value) {
                    if ($disk->exists($old_path = $this->originalPath($attachment, $size))) {
                        $disk->move($old_path, $attachment->path($size));
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
                $attachmentPath = $attachment->getPathAttribute($size);

                if (! is_null($attachmentPath) && $disk->exists($attachmentPath)) {
                    $disk->delete($attachmentPath);
                }
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
