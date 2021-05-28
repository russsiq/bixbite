<?php

namespace App\Models;

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
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property string $role
 * @property ?string $avatar
 * @property ?string $info
 * @property ?string $location
 * @property ?string $last_ip
 * @property \Illuminate\Support\Carbon|null $logined_at
 * @property \Illuminate\Support\Carbon|null $banned_until
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Database\Factories\UserFactory factory()
 *
 * @mixin \Illuminate\Database\Query\Builder
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class User extends Authenticatable implements
    Contracts\CustomizableContract,
    Contracts\ExtensibleContract,
    Contracts\MustBeOnlineContract,
    MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use Mutators\UserMutators;
    use Relations\CustomizableTrait;
    use Relations\ExtensibleTrait;
    use Traits\Dataviewer;
    use Traits\MustBeOnlineTrait;
    use Traits\hasPrivileges;

    /**
     * The table associated with the model.
     *
     * @const string
     */
    public const TABLE = 'users';

    /**
     * {@inheritDoc}
     */
    protected $table = self::TABLE;

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    protected $appends = [
        'is_online',
        'logined',
        'profile',
    ];

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
