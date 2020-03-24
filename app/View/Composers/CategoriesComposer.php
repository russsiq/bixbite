<?php

namespace App\View\Composers;

// Сторонние зависимости.
use App\Models\Category;
use App\Models\Collections\CategoryCollection;
use Illuminate\View\View;

/**
 * Данный компоновщик был добавлен как образец.
 * Недостаток применения компоновщика в том,
 * что его нужно регистрировать в поставщике служб,
 * Но он не для всех маршрутов нужен.
 *
 * К тому же возникают коллизии, связанные с тем,
 * что некоторые переменные в шаблонах извлекаются
 * из глобального помощника `pageinfo`,
 * а категории просто выплёвываются.
 *
  * Пример регистрации.
  *  // Используем компоновщика для извлечения коллекции категорий.
  *  // Директория `components` указана как пример реализации.
  *  View::composer('components.*', CategoriesComposer::class);
 */
class CategoriesComposer
{
    /**
     * Коллекция категорий.
     * @var CategoryCollection|null
     */
    protected static $categories;

    /**
     * Создать новый компоновщик.
     */
    public function __construct()
    {

    }

    /**
     * Привязать данные к шаблону.
     * @param  View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with('categories', $this->categories());
    }

    /**
     * Получить коллекцию категорий.
     * @return CategoryCollection
     */
    protected function categories(): CategoryCollection
    {
        return self::$categories
            ?? self::$categories = $this->resolveCategories();
    }

    /**
     * Извлечь коллекцию категорий из хранилища.
     * @return CategoryCollection
     */
    protected function resolveCategories(): CategoryCollection
    {
        return Category::short()
            ->orderByPosition()
            ->get();
    }
}
