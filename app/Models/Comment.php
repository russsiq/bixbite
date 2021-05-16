<?php

namespace App\Models;

use App\Models\Collections\CommentCollection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Comment model.
 *
 * @property-read int $id
 * @property-read ?int $user_id
 * @property-read int $parent_id
 * @property-read int $commentable_id
 * @property-read string $commentable_type
 * @property-read string $content
 * @property-read ?string $author_name
 * @property-read ?string $author_email
 * @property-read ?string $author_ip
 * @property-read bool $is_approved
 * @property-read \Illuminate\Support\Carbon $created_at
 * @property-read \Illuminate\Support\Carbon $updated_at
 *
 * @property-read object $author
 * @property-read bool $by_user
 * @property-read string $url
 *
 * @method \Illuminate\Database\Eloquent\Builder approved(bool $isApproved = true)
 */
class Comment extends Model
{
    use Mutators\CommentMutators;
    use Traits\Dataviewer;
    use HasFactory;

    public const TABLE = 'comments';

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
        'parent_id' => 0,
        'content' => null,
        'author_name' => null,
        'author_email' => null,
        'author_ip' => null,
        'is_approved' => false,
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'author',
        'by_user',
        'url',
        'created',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'parent_id' => 'integer',
        'is_approved' => 'boolean',
        'author' => 'object',
        'by_user' => 'boolean',
        'url' => 'string',
        'created' => 'string',
    ];

    /**
     * Атрибуты, для которых разрешено массовое присвоение значений.
     * @var array
     */
    protected $fillable = [
        'user_id',
        'parent_id',
        'commentable_id',
        'commentable_type',
        'content',
        'author_name',
        'author_email',
        'author_ip',
        'is_approved',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'user:users.id,users.name,users.email,users.avatar',
    ];

    /**
     * Attributes by which filtering is allowed.
     *
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
     * The attributes by which sorting is allowed.
     *
     * @var array
     */
    protected $orderableColumns = [
        'id',
        'created_at',
    ];

    /**
     * Get the user who created the comment.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'user');
    }

    /**
     * Get the parent commentable model.
     *
     * @return MorphTo
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return CommentCollection
     */
    public function newCollection(array $models = []): CommentCollection
    {
        return new CommentCollection($models);
    }

    /**
     * Scope a query to only include approved / unapproved comments.
     *
     * @param  Builder  $builder
     * @param  boolean  $isApproved
     * @return Builder
     */
    public function scopeApproved(Builder $builder, bool $isApproved = true): Builder
    {
        return $builder->where('comments.is_approved', $isApproved);
    }
}
