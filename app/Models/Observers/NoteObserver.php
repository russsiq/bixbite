<?php

namespace App\Models\Observers;

// Сторонние зависимости.
use App\Models\Note;

/**
 * Наблюдатель модели `Note`.
 */
class NoteObserver extends BaseObserver
{
    /**
     * Обработать событие `deleting` модели.
     * @param  Note  $note
     * @return void
     */
    public function saved(Note $note): void
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

    /**
     * Обработать событие `deleting` модели.
     * @param  Note  $note
     * @return void
     */
    public function deleting(Note $note): void
    {
        $note->attachments()->get()->each->delete();
    }

    /**
     * Прикрепить изображение к указанной заметке.
     * @param  Note  $note
     * @return void
     */
    protected function attachImage(Note $note): void
    {
        if (is_int($image_id = $note->image_id)) {
            $note->attachments()
                ->getRelated()
                ->whereId($image_id)
                ->update([
                    'attachable_type' => $note->getMorphClass(),
                    'attachable_id' => $note->id,
                ]);
        }
    }

    /**
     * Открепить и удалить изображение от указанной записи.
     * @param  Note  $note
     * @return void
     */
    protected function deleteImage(Note $note): void
    {
        if (is_int($image_id = $note->getOriginal('image_id'))) {
            $note->attachments()
                ->whereId($image_id)
                ->get()
                ->each
                ->delete();
        }
    }
}
