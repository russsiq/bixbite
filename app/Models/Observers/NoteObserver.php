<?php

namespace BBCMS\Models\Observers;

use BBCMS\Models\Note;

class NoteObserver
{
    public function saved(Note $note)
    {
        $dirty = $note->getDirty();

        // Set new or delete old article image.
        if (array_key_exists('image_id', $dirty)) {
            // Deleting always.
            $this->deleteImage($note);

            // Attaching.
            $this->attachImage($note);
        }
    }

    public function deleting(Note $note)
    {
        $note->files()->get()->each->delete();
    }

    protected function attachImage(Note $note)
    {
        if (is_int($image_id = $note->image_id)) {
            $note->files()
                ->getRelated()
                ->whereId($image_id)
                ->update([
                    'attachment_type' => $note->getMorphClass(),
                    'attachment_id' => $note->id,
                ]);
        }
    }

    protected function deleteImage(Note $note)
    {
        if (is_int($image_id = $note->getOriginal('image_id'))) {
            $note->files()
                ->whereId($image_id)
                ->get()
                ->each
                ->delete();
        }
    }
}
