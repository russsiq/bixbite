<?php

namespace BBCMS\Models;

use BBCMS\Models\Article;
use BBCMS\Models\File;
use BBCMS\Models\Note;
use BBCMS\Models\Comment;
use BBCMS\Models\Privilege;
use BBCMS\Models\Mutators\UserMutators;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use UserMutators;
    // use Commentable; user not commentable, Profile is commentable !!!

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name', 'email', 'avatar', 'password', 'last_ip', 'logined_at',
        //  'role' - is guarded, but F12 and final. Remember about safety
    ];
    protected $appends = [
        'logined'
    ];
    protected $hidden = [
        'email', 'password', 'remember_token', 'role',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            // // 1 Roles. Not used in this version App.
            // $user->roles()->detach();

            // 2 Чтобы вызвать метод `Article::deleting`,
            //   в котором прописаны дополнительные удаляшки
            $user->articles()->get(['id'])->each->delete();

            // 3 Обновим комментарии якобы они от незарегистрированного пользователя,
            //   но только после удаления новостей пользователя,
            //   т.к. он мог наоставлять комментов к своим записям, а это лишние запросы
            $user->comments()->update(['user_id' => null, 'name' => $user->name, 'email' => $user->email]);

            // 4 Просто удаляем, т.к. заметки не имеют реляц. связей
            $user->notes()->delete();

            // 5 Always delete avatar from storage
            $user->deleteAvatar(true);
        });
    }

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

    // Check if user is online
    public function isOnline()
    {
        return cache()->has('user-is-online-' . $this->id);
    }

    public function manageFromRequest($request)
    {
        $data = $request->except(['avatar']);

        if (! empty($data['role'])) {
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

    /*
    |--------------------------------------------------------------------------
    | Below is a simplified bundle User->Role->Permission.
    |--------------------------------------------------------------------------
    |
    | Made to reduce unnecessary requests into the database.
    | In fact, we only need to know the user can or not can Do.
    | In General, it is necessary to cram in a conf. file and not to be steamed.
    |
    */

    /**
     * Checks if User has access to $privilege.
     * @param string $privilege
     * @return bool
     */
    public function canDo(string $privilege)
    {
        if ($this->hasRole('owner') and 'production' === env('APP_ENV')) {
            return true;
        }

        return in_array($privilege, $this->getCachedPrivileges());
    }

    /**
     * Checks if the user belongs to role.
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role)
    {
        return $role === $this->role;
    }

    /**
     * Get all cached privileges belongs to role.
     * @return array
     */
    protected function getCachedPrivileges()
    {
        return cache()->rememberForever('privileges.'.$this->role, function () {
            return $this->getPrivileges();
        });
    }

    /**
     * Get all privileges belongs to role.
     * @return array
     */
    protected function getPrivileges()
    {
        return Privilege::select('privilege')
            ->where('privileges.'.$this->role, 1)
            ->pluck('privilege')
            ->toArray();
    }
}
