<?php

namespace App\Models;

// Зарегистрированные фасады приложения.
use Illuminate\Support\Facades\Schema;

/**
 * Модель Привилегии.
 */
class Privilege extends BaseModel
{
    /**
     * Таблица БД, ассоциированная с моделью.
     * @var string
     */
    protected $table = 'privileges';

    /**
     * Первичный ключ таблицы БД.
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * User exposed observable events.
     * These are extra user-defined events observers may subscribe to.
     * @var array
     */
    protected $observables = [
        'tableUpdated',

    ];

    /**
     * Получить массив групп пользователей.
     * @return array
     */
    public function roles(): array
    {
        return cache()->rememberForever('roles', function () {
            return array_diff(Schema::getColumnListing($this->table), [
                'id',
                'privilege',
                'description',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
        });
    }

    /**
     * Получить массив привилегий пользователей.
     * @return array
     */
    public function privileges(): array
    {
        return cache()->rememberForever('privileges', function () {
            return $this->select([
                    'privilege',
                ])
                ->addSelect(
                    $this->roles()
                )
                ->get()
                ->mapWithKeys(function ($item) {
                    $out = array_filter($item->toArray());

                    unset($out['privilege']);

                    return [
                        $item['privilege'] => $out
                    ];
                })
                ->toArray();
        });
    }

    /**
     * Обновить все привилегии и
     * сохранить обновленные модели в базу данных.
     * @return void
     */
    public function saveTable(array $table): void
    {
        $roles = array_diff($this->roles(), [
            'owner',

        ]);

        foreach ($roles as $role) {
            if (isset($table[$role])) {
                $this->whereIn('id', $table[$role])->update([$role => 1]);
                $this->whereNotIn('id', $table[$role])->update([$role => null]);
            } else {
                $this->whereNotNull($role)->update([$role => null]);
            }
        }

        $this->whereNull('owner')->update(['owner' => 1]);

        $this->fireModelEvent('tableUpdated', false);
    }
}
