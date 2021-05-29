<?php

// http://designpatternsphp.readthedocs.io/ru/latest/More/EAV/README.html

namespace App\Models;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * XField model.
 *
 * @property int $id
 * @property string $extensible
 * @property string $name
 * @property string $type
 * @property array $params
 * @property string $title
 * @property string $descr
 * @property string $html_flags
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Database\Factories\XFieldFactory factory()
 *
 * @mixin \Illuminate\Database\Query\Builder
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class XField extends Model
{
    use HasFactory;
    use Mutators\XFieldMutators;
    use Traits\Dataviewer;

    /**
     * The column name prefix in the database tables of the extensible model.
     *
     * @const string
     */
    public const X_PREFIX = 'x_';

    /**
     * The maximum length of a column name.
     *
     * @link https://dev.mysql.com/doc/refman/8.0/en/identifier-length.html
     *
     * @const int
     */
    public const MAXIMUM_LENGTH_COLUMN_NAME = 64;

    /**
     * The table associated with the model.
     *
     * @const string
     */
    public const TABLE = 'x_fields';

    /**
     * {@inheritDoc}
     */
    protected $table = self::TABLE;

    /**
     * {@inheritDoc}
     */
    protected $attributes = [
        'type' => 'string',
        'params' => '{}',
        'title' => null,
        'descr' => null,
        'html_flags' => null,
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'type' => 'string',
        'params' => 'array',
    ];

    /**
     * {@inheritDoc}
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
     * Attributes by which filtering is allowed.
     *
     * @var string[]
     */
    protected $allowedFilters = [
        //
    ];

    /**
     * The attributes by which sorting is allowed.
     *
     * @var string[]
     */
    protected $orderableColumns = [
        'id',
        'title',
        'created_at',
    ];

    /**
     * Allowed database table names
     * for which new extra fields can be created.
     *
     * @var string[]
     */
    protected static $extensibles = [
        Article::TABLE,
        Category::TABLE,
        User::TABLE,
    ];

    /**
     * Types of extra fields for tables in the database.
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
     * Cached in-memory extra fields collection.
     *
     * @var EloquentCollection|null
     */
    protected static ?EloquentCollection $cachedExtraFields = null;

    /**
     * Get the collection of extra fields for a given table.
     *
     * @param  string|null  $table
     * @return EloquentCollection
     */
    public static function fields(string $table = null): EloquentCollection
    {
        if (! static::$cachedExtraFields instanceof EloquentCollection) {
            static::$cachedExtraFields = static::all();
        }

        if (is_null($table)) {
            return static::$cachedExtraFields;
        }

        return static::$cachedExtraFields->where('extensible', $table);
    }

    /**
     * Get a list of the allowed database table names
     * for which new extra fields can be created.
     *
     * @return array
     */
    public static function extensibles(): array
    {
        return static::$extensibles;
    }

    /**
     * Get a maximum length of a column name.
     *
     * @return int
     */
    public static function maximumLengthColumnName(): int
    {
        return self::MAXIMUM_LENGTH_COLUMN_NAME;
    }

    /**
     * Get a limit the length of the `name` column.
     *
     * @return int
     */
    public static function limitLengthNameColumn(): int
    {
        return static::maximumLengthColumnName() - mb_strlen(
            static::getModel()->xPrefix()
        );
    }

    /**
     * Get a list with the types of extra fields.
     *
     * @return array
     */
    public static function fieldTypes(): array
    {
        return static::$fieldTypes;
    }

    /**
     * Get the column name prefix in the database tables
     * of the extensible model.
     *
     * @return string
     */
    public function xPrefix(): string
    {
        return self::X_PREFIX;
    }
}
