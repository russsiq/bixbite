<?php

namespace App\Models;

// Сторонние зависимости.
use App\Models\Article;
use App\Models\Comment;
use App\Models\File;
use App\Models\Note;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

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

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    // public function profile()
    // {
    //    return $this->hasOne(Profile::class);
    // }

    /**
     * Комментарии на стене профиля пользователя.
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function posts()
    {
        return $this->morphMany(Comment::class, 'commentable', 'commentable_type', 'commentable_id', 'id');
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
