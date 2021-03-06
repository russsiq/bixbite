<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Note model.
 *
 * @property int $id
 * @property int $user_id
 * @property int $image_id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property boolean $is_completed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Database\Factories\NoteFactory factory()
 *
 * @mixin \Illuminate\Database\Query\Builder
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Note extends Model implements
    Contracts\AttachableContract,
    Contracts\BelongsToUserContract
{
    use HasFactory;
    use Relations\AttachableTrait;
    use Relations\BelongsToUserTrait;

    /**
     * The table associated with the model.
     *
     * @const string
     */
    public const TABLE = 'notes';

    /**
     * {@inheritDoc}
     */
    protected $table = self::TABLE;

    /**
     * {@inheritDoc}
     */
    protected $attributes = [
        'user_id' => null,
        'image_id' => null,
        'title' => '',
        'slug' => '',
        'description' => null,
        'is_completed' => false,
    ];

    /**
     * {@inheritDoc}
     */
    protected $appends = [
        'image',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'user_id' => 'integer',
        'image_id' => 'integer',
        'title' => 'string',
        'slug' => 'string',
        'description' => 'string',
        'is_completed' => 'boolean',
    ];

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'user_id',
        'image_id',
        'title',
        'slug',
        'description',
        'is_completed',
    ];

    /**
     * {@inheritDoc}
     */
    protected $with = [
        'user:users.id,users.name',
    ];

    /**
     * Attributes by which filtering is allowed.
     *
     * @var string[]
     */
    protected $allowedFilters = [
        'is_completed',
    ];

    /**
     * The attributes by which sorting is allowed.
     *
     * @var string[]
     */
    protected $orderableColumns = [
        'id',
        'title',
        'created_at',
    ];
}
