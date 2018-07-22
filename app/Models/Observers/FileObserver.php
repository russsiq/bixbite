<?php

namespace BBCMS\Models\Observers;

use BBCMS\Models\File;

class FileObserver
{
    public function updating(File $file)
    {
        if ($file->originalPath() != $file->path()) {
            $disk = $file->storageDisk();
            $disk->rename($file->originalPath(), $file->path());

            if ('image' == $file->type) {
                foreach ($file->thumbSizes() as $size => $value) {
                    if ($disk->exists($old_path = $file->originalPath($size))) {
                        $disk->rename($old_path, $file->path($size));
                    }
                }
            }
        }
    }

    public function deleting(File $file)
    {
        $disk = $file->storageDisk();
        $disk->delete($file->path());

        if ('image' == $file->type) {
            // Automatically seting null image_id, see in migration to attachment.
            foreach ($file->thumbSizes() as $size => $value) {
                $disk->delete($file->getPathAttribute($size));
            }
        }
    }

    // protected function dettachImage(Article $article)
    // {
    //     if (is_int($image_id = $article->image_id)) {
    //         $article->files()
    //             ->getRelated()
    //             ->whereId($image_id)
    //             ->update([
    //                 'attachment_type' => $article->getMorphClass(),
    //                 'attachment_id' => $article->id,
    //             ]);
    //     }
    // }
    //
    // protected function deleteImage(Article $article)
    // {
    //     if (is_int($image_id = $article->getOriginal('image_id'))) {
    //         $article->files()
    //             ->whereId($image_id)
    //             ->get()
    //             ->each
    //             ->delete();
    //     }
    // }
}
