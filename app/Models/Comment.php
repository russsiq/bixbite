<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * Comment model.
 *
 * @property int $id
 * @property string $commentable_type
 * @property int $commentable_id
 * @property int $parent_id
 * @property int|null $user_id
 * @property string $content
 * @property string|null $user_name
 * @property string|null $user_email
 * @property string|null $user_ip
 * @property bool $is_approved
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Comment extends Model
{
    use HasFactory;

    const TABLE = 'comments';

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
        'is_approved' => false,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        // 'user_id',
        'parent_id',
        'content',
        'user_name',
        'user_email',
        'user_ip',
        'is_approved',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'parent_id' => 'integer',
        'content' => 'string',
        'user_name' => 'string',
        'user_email' => 'string',
        'user_ip' => 'string',
        'is_approved' => 'boolean',
    ];

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'user')
            ->withDefault(function (User $user, Comment $comment) {
                $user->name = $comment->user_name;
                $user->email = $comment->user_email;
            });
    }
}
