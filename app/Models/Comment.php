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
 * @property int $id
 * @property ?int $user_id
 * @property int $parent_id
 * @property int $commentable_id
 * @property string $commentable_type
 * @property string $content
 * @property ?string $author_name
 * @property ?string $author_email
 * @property ?string $author_ip
 * @property bool $is_approved
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\User $author Get the author who created the comment.
 * @property-read \Illuminate\Database\Eloquent\Model $commentable Get the parent commentable model.
 *
 * @method static \Database\Factories\CommentFactory factory()
 *
 * @mixin \Illuminate\Database\Query\Builder
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Comment extends Model implements
    Contracts\BelongsToUserContract,
    Contracts\CustomizableContract
{
    use HasFactory;
    use Mutators\CommentMutators;
    use Relations\BelongsToUserTrait;
    use Relations\CustomizableTrait;
    use Scopes\CommentScopes;
    use Traits\Dataviewer;

    /**
     * The table associated with the model.
     *
     * @const string
     */
    public const TABLE = 'comments';

    /**
     * {@inheritDoc}
     */
    protected $table = self::TABLE;

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    protected $appends = [
        'by_user',
        'url',
        'created',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'parent_id' => 'integer',
        'is_approved' => 'boolean',
        'by_user' => 'boolean',
        'url' => 'string',
        'created' => 'string',
    ];

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    protected $with = [
        'author:users.id,users.name,users.email,users.avatar',
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
     * Get the author who created the comment.
     *
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->user()
            ->withDefault(function (User $user, Comment $comment) {
                $user->exists = false;
                $user->id = null;
                $user->name = $comment->author_name;
                $user->email = $comment->author_email;
                $user->profile = null;
                $user->is_online = false;
            });
    }

    /**
     * Get the parent commentable model.
     *
     * @return MorphTo
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo(
            'commentable',      // $name
            'commentable_type', // $type
            'commentable_id',   // $id
            'id',               // $ownerKey
        );
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
