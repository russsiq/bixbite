<?php

namespace App\Models;

use App\Models\Collections\CommentCollection;
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

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'commentable_id', 'id', 'commentable_type');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'user');
    }

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
}
