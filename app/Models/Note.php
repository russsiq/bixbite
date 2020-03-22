<?php

namespace App\Models;

// Сторонние зависимости.
use App\Models\User;
use App\Models\Observers\NoteObserver;

class Note extends BaseModel
{
    use Mutators\NoteMutators,
        Relations\Fileable;

    /**
     * Таблица БД, ассоциированная с моделью.
     * @var string
     */
    protected $table = 'notes';

    /**
     * Первичный ключ таблицы БД.
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Значения по умолчанию для атрибутов модели.
     * @var array
     */
    protected $attributes = [
        'image_id' => null,
        'title' => '',
        'slug' => '',
        'description' => '',
        'is_completed' => false,

    ];

    /**
     * Атрибуты, которые должны быть приведены к базовым типам.
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'image_id' => 'integer',
        'title' => 'string',
        'slug' => 'string',
        'description' => 'string',
        'is_completed' => 'boolean',

    ];

    /**
     * Атрибуты, для которых разрешено массовое присвоение значений.
     * @var array
     */
    protected $fillable = [
        'user_id',
        'image_id',
        'title',
        'slug',
        'description',
        'is_completed',
    ];

    /**
     * Атрибуты, по которым разрешена фильтрация сущностей.
     * @var array
     */
    protected $allowedFilters = [
        'is_completed',

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
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::observe(NoteObserver::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'user');
    }
}
