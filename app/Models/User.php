<?php

namespace BBCMS\Models;

use BBCMS\Models\Article;
use BBCMS\Models\File;
use BBCMS\Models\Note;
use BBCMS\Models\Comment;
use BBCMS\Models\Privilege;
use BBCMS\Models\XField;

use BBCMS\Models\Relations\hasFollows;

use BBCMS\Models\Mutators\UserMutators;
use BBCMS\Models\Observers\UserObserver;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    use hasFollows;
    use UserMutators;
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
    ];
    protected $guarded = [
        // Role is guarded, but F12 and final.
        // Remember about safety.
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

    protected $isOnlinePrefix = 'users.is-online-';
    protected $isOnlineMinutes = 15;

    protected static function boot()
    {
        parent::boot();
        static::observe(UserObserver::class);
    }

    // public function profile()
    // {
    //    return $this->hasOne(Profile::class);
    // }

    // OneToMany - один пользователь может добавить несколько статей
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    // Связь OneToMany: один пользователь - много комментов
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Posts - this is a comments on wall of user profile page.
    public function posts()
    {
        return $this->morphMany(Comment::class, 'commentable', 'commentable_type', 'commentable_id', 'id');
    }

    // OneToMany - один пользователь может добавить несколько заметок
    public function files()
    {
        return $this->hasMany(File::class);
    }

    // OneToMany - один пользователь может добавить несколько заметок
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    // Check if user is online.
    public function isOnline()
    {
        return cache()->has($this->isOnlineKey());
    }

    // Get key to check if user is online.
    public function isOnlineKey()
    {
        return $this->isOnlinePrefix.$this->id;
    }

    // Time in minutes when user can be considered online.
    public function isOnlineMinutes()
    {
        return setting('users.online_minutes', $this->isOnlineMinutes);
    }

    // Last time when user was active.
    public function lastActive()
    {
        return $this->isOnline()
            ? cache($this->isOnlineKey())->diffForHumans()
            : $this->logined;
    }

    public function manageFromRequest($request)
    {
        $this->fillable = array_merge($this->fillable,
            XField::fields($this->table)->pluck('name')->toArray()
        );

        $data = $request->except(['avatar']);

        if (! empty($data['role']) and 'owner' == user('role')) {
            $this->role = $data['role'];
        }

        return $this->fill($data)
                    ->attachAvatar($request)
                    ->save();
    }

    public function attachAvatar($request)
    {
        // Delete user avatar
        $this->deleteAvatar($request->delete_avatar);

        if ($request->hasFile('avatar') and $request->file('avatar')->isValid()) {

            // Path to folder avatars for store
            $path = 'uploads'.DS.'avatars';

            // Get new user avatar from $request input
            $avatar = $request->file('avatar');

            // Formation of avatar file name.
            // $this->id - is_null, if it is new $user.
            [$width, $height] = getimagesize($avatar->getRealPath());
            $extension = $avatar->getClientOriginalExtension();
            $filename = $this->id.'_'.time().'_'.$width.'x'.$height.'.'.$extension;

            // Storage file
            $avatar->storeAs($path, $filename);

            // Atach to model
            $this->avatar = $filename;
        }

        return $this;
    }

    public function deleteAvatar($force = false)
    {
        // Path to folder avatars
        $path = app()->basePath('uploads'.DS.'avatars'.DS);

        // Get old user avatar file name.
        $avatar = $this->getOriginal('avatar');

        // Check and delete, if user already has an avatar.
        if ($force and ! is_null($avatar) and file_exists($path.$avatar) and unlink($path.$avatar)) {
            $this->avatar = null;
        }

        return $this;
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
