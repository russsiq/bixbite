<?php

namespace BBCMS\Models;

use BBCMS\Models\Article;
use BBCMS\Models\File;
use BBCMS\Models\Note;
use BBCMS\Models\Comment;
use BBCMS\Models\Privilege;
use BBCMS\Models\XField;
use BBCMS\Models\Traits\hasOnline;

use BBCMS\Models\Relations\hasFollows;

use BBCMS\Models\Mutators\UserMutators;
use BBCMS\Models\Observers\UserObserver;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use hasFollows, hasOnline, UserMutators;
    // use Commentable; user not commentable, Profile is commentable !!!

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'password',
        'last_ip',
        'logined_at',
        'where_from',
        'info',
        'role',
    ];
    protected $appends = [
        'logined'
    ];
    protected $hidden = [
        'email',
        'password',
        'remember_token',
        'role',
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(UserObserver::class);
    }

    // public function profile()
    // {
    //    return $this->hasOne(Profile::class);
    // }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Posts - this is a comments on wall of user profile page.
    public function posts()
    {
        return $this->morphMany(Comment::class, 'commentable', 'commentable_type', 'commentable_id', 'id');
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Checks if the user belongs to role.
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return $role == $this->role;
    }

    /**
     * Checks if User has access to $privilege.
     * @param string $privilege
     * @return bool
     */
    public function canDo(string $privilege): bool
    {
        // $privileges = CacheFile::fromMap('privileges');
        $privileges = cache('privileges') ?? Privilege::getModel()->privileges();

        return $privileges[$privilege][$this->role] ?? false;
    }
}
