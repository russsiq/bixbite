<?php

namespace App\Models;

// Сторонние зависимости.
use App\Models\Article;
use App\Models\User;
use App\Models\Collections\CommentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Модель Комментария.
 */
class Comment extends BaseModel
{
    use Mutators\CommentMutators,
        Traits\Dataviewer,
        HasFactory;

    /**
    * Таблица БД, ассоциированная с моделью.
    * @var string
    */
    protected $table = 'comments';

    /**
     * Первичный ключ таблицы БД.
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Аксессоры, добавляемые при сериализации модели.
     * @var array
     */
    protected $appends = [
        'url',
        'created',
        // 'updated',
        'by_user',
        // 'by_author',

    ];

    /**
     * Атрибуты, которые должны быть типизированы.
     * @var array
     */
    protected $casts = [
        'is_approved' => 'boolean',
        'user_id' => 'integer',
        'parent_id' => 'integer',
        'commentable_type' => 'string',
        'commentable_id' => 'integer',
        'name' => 'string',
        'email' => 'string',
        'content' => 'string',

        'url' => 'string',
        'created' => 'timestamp',
        'updated' => 'timestamp',
        'by_user' => 'string',
        'by_author' => 'string',

    ];

    /**
     * Атрибуты, для которых разрешено массовое присвоение значений.
     * @var array
     */
    protected $fillable = [
        'is_approved',
        'parent_id',
        'user_id',
        'commentable_type',
        'commentable_id',
        'name',
        'email',
        'user_ip',
        'content',

    ];

    /**
     * Атрибуты, по которым разрешена фильтрация сущностей.
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'user_id',
        'content',
        'commentable_type',
        'commentable_id',
        'is_approved',
        'created_at',

    ];

    /**
     * Атрибуты, по которым разрешена сортировка сущностей.
     * @var array
     */
    protected $orderableColumns = [
        'id',
        'created_at',

    ];

    /**
     * Все отношения, которые должны быть затронуты.
     * Automatically "touch" the updated_at timestamp of the owning Forum.
     *
     * @var array
     */
    // protected $touches = ['forum'];

    /**
     * Получить запись, к которой относится комментарий.
     * @return BelongsTo
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'commentable_id', 'id', 'commentable_type');
    }

    /**
     * Получить пользователя, оставившего комментарий.
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'user');
    }

    /**
     * Получить родительскую модель комментария.
     * @return MorphTo
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Создать новый экземпляр коллекции Eloquent.
     * @param  array  $models
     * @return CommentCollection
     */
    public function newCollection(array $models = []): CommentCollection
    {
        return new CommentCollection($models);
    }
}
