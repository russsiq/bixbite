<?php

namespace App\Models;

use App\Models\Contracts\ExtensibleContract;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * User model.
 *
 * @property-read int $id
 * @property-read string $name
 * @property-read string $email
 * @property-read \Illuminate\Support\Carbon|null $email_verified_at
 * @property-read string $password
 * @property-read string $remember_token
 * @property-read string $role
 * @property-read ?string $avatar
 * @property-read ?string $info
 * @property-read ?string $location
 * @property-read ?string $last_ip
 * @property-read \Illuminate\Support\Carbon|null $logined_at
 * @property-read \Illuminate\Support\Carbon|null $banned_until
 * @property-read \Illuminate\Support\Carbon $created_at
 * @property-read \Illuminate\Support\Carbon $updated_at
 *
 * @property-read string $profile
 * @property-read bool $is_online
 * @property-read ?string $logined
 */
class User extends Authenticatable implements MustVerifyEmail, ExtensibleContract
{
    use Mutators\UserMutators;
    use Relations\Extensible;
    use Traits\Dataviewer;
    use Traits\hasOnline;
    use Traits\hasPrivileges;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use TwoFactorAuthenticatable;

    public const TABLE = 'users';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = self::TABLE;

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'name' => null,
        'email' => null,
        'email_verified_at' => null,
        'password' => null,
        'remember_token' => null,
        'role' => 'user',
        'avatar' => null,
        'info' => null,
        'location' => null,
        'last_ip' => null,
        'logined_at' => null,
        'banned_until' => null,
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'is_online',
        'logined',
        'profile',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'email' => 'string',
        'email_verified_at' => 'datetime',
        'password' => 'string',
        'remember_token' => 'string',
        'role' => 'string',
        'avatar' => 'string',
        'info' => 'string',
        'location' => 'string',
        'last_ip' => 'string',
        'logined_at' => 'datetime',
        'banned_until' => 'datetime',

        'is_online' => 'bool',
        'logined' => 'string',
        'profile' => 'string',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'info',
        'location',
        'last_ip',
        'logined_at',
        'banned_until',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attributes by which filtering is allowed.
     *
     * @var array
     */
    protected $allowedFilters = [
        //
    ];

    /**
     * The attributes by which sorting is allowed.
     *
     * @var array
     */
    protected $orderableColumns = [
        'id',
        'name',
        'logined_at',
        'role',
    ];

    /**
     * Get all of the articles for the user.
     *
     * @return HasMany
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'user_id', 'id');
    }

    /**
     * Get all of the comments for the user.
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    /**
     * Get all files uploaded by the user.
     *
     * @return HasMany
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class, 'user_id', 'id');
    }

    /**
     * Get all of the notes for the user.
     *
     * @return HasMany
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'user_id', 'id');
    }

    /**
     * Determine that the user is a super administrator.
     *
     * @return boolean
     */
    public function isSuperAdmin(): bool
    {
        return in_array(
            $this->email, (array) config('bixbite.super_admins')
        );
    }
}
