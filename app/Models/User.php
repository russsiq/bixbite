<?php

namespace App\Models;

// Сторонние зависимости.
use App\Models\Article;
use App\Models\Comment;
use App\Models\File;
use App\Models\Note;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

/**
 * Модель Пользователя.
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Mutators\UserMutators,
        Relations\Extensible,
        Relations\hasFollows,
        Traits\Dataviewer,
        Traits\hasOnline,
        Traits\hasPrivileges,
        Notifiable;

    // use Commentable; user not commentable, Wall Profile is commentable !!!

    /**
     * Таблица БД, ассоциированная с моделью.
     * @var string
     */
    protected $table = 'users';

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
        'logined',
        'is_online',

    ];

    /**
     * Атрибуты, которые должны быть типизированы.
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',

    ];

    /**
     * Атрибуты, для которых разрешено массовое присвоение значений.
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'info',
        'where_from',
        'last_ip',
        'logined_at',
        'banned_until',

    ];

    /**
     * Скрываемые из массива или JSON представления модели атрибуты.
     * @var array
     */
    protected $hidden = [
        // 'email',
        'password',
        'api_token',
        'remember_token',

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
        'name',
        'logined_at',
        'role',

    ];

    /**
     * Получить все записи пользователя.
     * @return HasMany
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Получить все комментарии пользователя.
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Получить все файлы, загруженные пользователем.
     * @return HasMany
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    /**
     * Получить все заметки пользователя.
     * @return HasMany
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    // public function profile()
    // {
    //    return $this->hasOne(Profile::class);
    // }

    /**
     * Комментарии на стене профиля пользователя.
     * @return MorphMany
     */
    public function posts(): MorphMany
    {
        return $this->morphMany(
            Comment::class,
            'commentable',
            'commentable_type',
            'commentable_id',
            'id'
        );
    }

    /**
     * [generateApiToken description]
     * @return string
     */
    public function generateApiToken(): string
    {
        $this->api_token = hash('sha256', Str::random(60));
        $this->save();

        return $this->api_token;
    }

    /**
     * [resetApiToken description]
     * @return void
     */
    public function resetApiToken()
    {
        $this->api_token = null;
        $this->save();
    }
}
