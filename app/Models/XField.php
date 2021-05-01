<?php

// http://designpatternsphp.readthedocs.io/ru/latest/More/EAV/README.html

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * XField model.
 *
 * @property-read int $id
 * @property-read string $extensible
 * @property-read string $name
 * @property-read string $type
 * @property-read array $params
 * @property-read string $title
 * @property-read string $descr
 * @property-read string $html_flags
 * @property-read \Illuminate\Support\Carbon $created_at
 * @property-read \Illuminate\Support\Carbon $updated_at
 */
class XField extends Model
{
    use Mutators\XFieldMutators;
    use Traits\Dataviewer;
    use HasFactory;

    /**
     * Префикс имени столбца в таблице БД.
     *
     * @const string
     */
    public const X_PREFIX = 'x_';

    public const TABLE = 'x_fields';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = self::TABLE;

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'type' => 'string',
        'params' => '{}',
        'title' => null,
        'descr' => null,
        'html_flags' => null,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'type' => 'string',
        'params' => 'array',
    ];

    /**
     * Атрибуты, для которых разрешено массовое присвоение значений.
     *
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
        //
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
     *
     *
     * @var string[]
     */
    protected static $extensibles = [
        'articles',
        'categories',
        'users',
    ];

    /**
     * Типы дополнительных полей для таблиц в БД.
     *
     * @var string[]
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
     *
     * @param  string|null  $table
     * @return Collection
     */
    public static function fields(string $table = null): Collection
    {
        $fields = cache()->rememberForever(
            self::TABLE, fn () => static::all()
        );

        if (is_null($table)) {
            return $fields;
        }

        return $fields->where('extensible', $table)
            ->values();
    }

    /**
     * Получить список с разрешенными именами таблиц в БД,
     * для которых доступно создание новых полей.
     *
     * @return array
     */
    public static function extensibles(): array
    {
        return static::$extensibles;
    }

    /**
     * Получить список с типами дополнительных полей.
     *
     * @return array
     */
    public static function fieldTypes(): array
    {
        return static::$fieldTypes;
    }

    /**
     * Получить префикс имени столбца в таблице БД.
     *
     * @return string
     */
    public function xPrefix(): string
    {
        return self::X_PREFIX;
    }
}
