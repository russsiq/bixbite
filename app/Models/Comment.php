<?php

namespace App\Models;

// Сторонние зависимости.
use App\Models\Article;
use App\Models\User;
use App\Models\Collections\CommentCollection;
use App\Models\Observers\CommentObserver;

class Comment extends BaseModel
{
    use Mutators\CommentMutators,
        Traits\Dataviewer;

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
     * Динамически добавляемые в массив или JSON представление модели атрибуты,
     * для которых прописаны методы доступа (акцессоры).
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
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::observe(CommentObserver::class);
    }

    /**
     * All of the relationships to be touched.
     * Automatically "touch" the updated_at timestamp of the owning Forum.
     *
     * @var array
     */
    // protected $touches = ['forum'];

    public function article()
    {
        return $this->belongsTo(Article::class, 'commentable_id', 'id', 'commentable_type');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'user');
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function newCollection(array $models = [])
    {
        return new CommentCollection($models);
    }
}
