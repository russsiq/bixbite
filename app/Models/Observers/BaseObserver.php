<?php

namespace App\Models\Observers;

use Illuminate\Database\Eloquent\Model as ParentModel;

/**
 * Абстрактный базовый класс наблюдателя за событиями моделей Eloquent.
 *
 * `retrieved` — при извлечении модели из БД.
 * `creating` / `created` — новая модель сохраняется впервые.
 * `updating` / `updated` — модель уже существует и вызывается метод `save`.
 * `saving` / `saved` — в обоих вышеперечисленных случаях.
 *
 * При массовом обновлении или удалении не будут инициированы
 * события `saved`, `updated`, `deleting`, и `deleted`.
 */
abstract class BaseObserver
{
    /**
     * Массив ключей для очистки кэша.
     *
     * @var array
     */
    protected $keysToForgetCache = [];

    /**
     * Очистить кэш модели по заданным в массиве ключам.
     *
     * @param  ParentModel  $entity
     * @return void
     */
    protected function forgetCacheByKeys(ParentModel $entity): void
    {
        foreach ($this->keysToForgetCache as $key => $method) {
            cache()->forget($key);

            if ($method) {
                $model = $entity->getModel();

                if ($method && method_exists($model, $method)) {
                    call_user_func_array([$model, $method], []);
                }
            }
        }
    }

    /**
     * Добавить новые ключи в масив ключей для очистки кэша.
     *
     * @return void
     */
    protected function addToCacheKeys(array $keys): void
    {
        $this->keysToForgetCache = array_merge($this->keysToForgetCache, $keys);
    }
}
