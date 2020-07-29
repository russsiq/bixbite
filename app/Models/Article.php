<?php

namespace App\Models;

// Сторонние зависимости.
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Модель Записи.
 */
class Article extends BaseModel
{
    use Mutators\ArticleMutators,
        Relations\Categoryable,
        Relations\Commentable,
        Relations\Extensible,
        Relations\Fileable,
        Relations\Taggable,
        Scopes\ArticleScopes,
        Traits\Dataviewer,
        Traits\FullTextSearch;

    /**
     * Таблица БД, ассоциированная с моделью.
     * @var string
     */
    protected $table = 'articles';

    /**
     * Первичный ключ таблицы БД.
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Следует ли обрабатывать временные метки модели.
     * @var bool
     */
    public $timestamps = false;

    /**
     * Аксессоры, добавляемые при сериализации модели.
     * @var array
     */
    protected $appends = [
        'url',
        'created',
        'updated',
        // 'image',

    ];

    /**
     * Атрибуты, которые должны быть типизированы.
     * @var array
     */
    protected $casts = [
        'title' => 'string',
        'img' => 'array',
        'image_id' => 'integer',
        'on_mainpage' => 'boolean',
        'is_favorite' => 'boolean',
        'is_pinned' => 'boolean',
        'is_catpinned' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',

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
        'teaser',
        'content',
        'description',
        'keywords',
        // Флаги.
        'allow_com',
        'state',
        'robots',
        'on_mainpage',
        'is_favorite',
        'is_pinned',
        'is_catpinned',
        // Расширения.
        'views',
        'votes',
        'rating',
        // Даты.
        'created_at',
        'updated_at',

    ];

    /**
     * Отношения, которые всегда должны быть загружены.
     * Пример: при выводе списка комментариев нужно получить ссылку на комментарий,
     * а ссылка на комментарий к записи формируется с использованием категорий.
     * @var array
     */
    protected $with = [
        'categories:categories.id,categories.title,categories.slug,categories.template',

    ];

    /**
     * Атрибуты, по которым разрешена фильтрация сущностей.
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'title',
        'content',
        'state',
        'views',
        'created_at',
        // 'votes',
        // 'rating',

        // Вложенная фильтрация.
        'comments.content',
        'comments.is_approved',
        'comments.count',
        'comments.created_at',
        'files.count',
        'categories.id',

    ];

    /**
     * Атрибуты, по которым разрешена сортировка сущностей.
     * @var array
     */
    protected $orderableColumns = [
        'id',
        'title',
        'views',
        'state',
        'created_at',

    ];

    /**
     * Атрибуты, по которым будет выполняться полнотекстовый поиск.
     * @var array
     */
    protected $searchable = [
        'title',
        'content',

    ];

    /**
     * Получить автора записи.
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'user');
    }

    /**
     * Получить настройки записей.
     * @return HasMany
     */
    public function settings(): HasMany
    {
        return $this->hasMany(Setting::class, 'module_name');
    }
}
