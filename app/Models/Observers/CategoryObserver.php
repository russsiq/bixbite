<?php

namespace App\Models\Observers;

// Сторонние зависимости.
use App\Models\Category;

/**
 * Наблюдатель модели `Category`.
 */
class CategoryObserver extends BaseObserver
{
    /**
     * Массив ключей для очистки кэша.
     * @var array
     */
    protected $keysToForgetCache = [
        'categories' => null,

    ];

    /**
     * Обработать событие `saved` модели.
     * @param  Category  $category
     * @return void
     */
    public function saved(Category $category): void
    {
        $dirty = $category->getDirty();

        // Set new or delete old article image.
        if (array_key_exists('image_id', $dirty)) {
            // Deleting always.
            $this->deleteImage($category);

            // Attaching.
            $this->attachImage($category);
        }

        $this->forgetCacheByKeys($category);
    }

    /**
     * Обработать событие `deleting` модели.
     * @param  Category  $category
     * @return void
     */
    public function deleting(Category $category): void
    {
        $category->articles()
            ->select([
                'articles.id',
                'articles.state',

            ])
            ->update([
                'articles.state' => 'draft',

            ]);

        $category->articles()->detach();
        $category->attachments()->get()->each->delete();
    }

    /**
     * Обработать событие `deleted` модели.
     * @param  Category  $category
     * @return void
     */
    public function deleted(Category $category): void
    {
        $this->forgetCacheByKeys($category);
    }

    /**
     * Прикрепить изображение к указанной категории.
     * @param  Category  $category
     * @return void
     */
    protected function attachImage(Category $category): void
    {
        if (is_int($image_id = $category->image_id)) {
            $category->attachments()
                ->getRelated()
                ->whereId($image_id)
                ->update([
                    'attachable_type' => $category->getMorphClass(),
                    'attachable_id' => $category->id,
                ]);
        }
    }

    /**
     * Открепить и удалить изображение от указанной категории.
     * @param  Category  $category
     * @return void
     */
    protected function deleteImage(Category $category): void
    {
        if (is_int($image_id = $category->getOriginal('image_id'))) {
            $category->attachments()
                ->whereId($image_id)
                ->get()
                ->each
                ->delete();
        }
    }
}
