<?php

// http://designpatternsphp.readthedocs.io/ru/latest/More/EAV/README.html

namespace App\Models;

// Сторонние зависимости.
use Illuminate\Database\Eloquent\Collection;

class XField extends BaseModel
{
    use Mutators\XFieldMutators,
        Traits\Dataviewer;

    /**
     * Префикс имени столбца в таблице БД.
     * @const string
     */
    const X_PREFIX = 'x_';

    /**
     * Таблица БД, ассоциированная с моделью.
     * @var string
     */
    protected $table = 'x_fields';

    /**
     * Первичный ключ таблицы БД.
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Атрибуты, которые должны быть типизированы.
     * @var array
     */
    protected $casts = [
        'params' => 'array',

    ];

    /**
     * Атрибуты, для которых разрешено массовое присвоение значений.
     * @var array
     */
    protected $fillable = [
        'extensible',
        'name',
        'type',
        'params',
        'title',
        'descr',
        'html_flags',

    ];

    /**
     * Атрибуты, по которым разрешена фильтрация сущностей.
     * @var array
     */
    protected $allowedFilters = [

    ];

    /**
     * Атрибуты, по которым разрешена сортировка сущностей.
     * @var array
     */
    protected $orderableColumns = [
        'id',
        'title',
        'created_at',

    ];

    /**
     * Разрешенные имена таблиц в БД,
     * для которых доступно создание новых полей.
     * @var array
     */
    protected static $extensibles = [
        'articles',
        'categories',
        'users',

    ];

    /**
     * Типы дополнительных полей для таблиц в БД.
     * @var array
     */
    protected static $fieldTypes = [
        'string',
        'integer',
        'boolean',
        'array',
        'text',
        'timestamp',

    ];

    /**
     * Получить коллекцию полей для указанной таблицы.
     * @param  string|null  $table
     * @return Collection
     */
    public static function fields(string $table = null): Collection
    {
        $fields = cache()
            ->rememberForever(static::getModel()->getTable(), function () {
                return static::query()->get();
            });

        return is_null($table) ? $fields : $fields->where('extensible', $table);
    }

    /**
     * Получить список с разрешенными именами таблиц в БД,
     * для которых доступно создание новых полей.
     * @return array
     */
    public static function extensibles(): array
    {
        return static::$extensibles;
    }

    /**
     * Получить список с типами дополнительных полей.
     * @return array
     */
    public static function fieldTypes(): array
    {
        return static::$fieldTypes;
    }

    /**
     * Получить префикс имени столбца в таблице БД.
     * @return string
     */
    public function xPrefix(): string
    {
        return self::X_PREFIX;
    }
}
