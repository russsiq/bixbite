<?php

namespace BBCMS\Models;

use BBCMS\Models\Article;
use BBCMS\Models\File;
use BBCMS\Models\Note;
use BBCMS\Models\Comment;

use BBCMS\Models\Relations\Extensible;
use BBCMS\Models\Relations\hasFollows;

use BBCMS\Models\Mutators\UserMutators;
use BBCMS\Models\Observers\UserObserver;

use BBCMS\Models\Traits\hasOnline;
use BBCMS\Models\Traits\hasPrivileges;

use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Traits\Dataviewer;
    use Notifiable;
    use Extensible,
        hasFollows,
        UserMutators,
        hasOnline,
        hasPrivileges;
    // use Commentable; user not commentable, Wall Profile is commentable !!!

    protected $table = 'users';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
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

    protected $appends = [
        'logined',
        'is_online'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // 'email',
        'password',
        'api_token',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $allowedFilters = [
        //
    ];

    protected $orderableColumns = [
        'id',
        'name',
        'logined_at',
        'role'
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(UserObserver::class);
    }

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
