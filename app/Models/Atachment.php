<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * Atachment model.
 *
 * @property int $id
 * @property string $attachment_type
 * @property int $attachment_id
 * @property int|null $user_id
 * @property string|null $title
 * @property string|null $description
 * @property string $disk
 * @property string $folder
 * @property string $type
 * @property string $name
 * @property string $extension
 * @property string $mime_type
 * @property int $filesize
 * @property string $checksum
 * @property array $properties
 * @property int $downloads
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Atachment extends Model
{
    use HasFactory;

    const TABLE = 'atachments';

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
        'title' => '',
        'description' => '',
        'disk' => 'public',
        'folder' => 'public',
        'type' => 'other',
        'filesize' => 0,
        'properties' => [],
        'downloads' => 0,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        // 'user_id',
        'title', 'description',
        // 'disk', 'folder',
        // 'downloads',
        // 'type', 'name', 'extension', 'mime_type',
        // 'filesize', 'checksum', 'properties',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',

        'title' => 'string',
        'description' => 'string',
        'disk' => 'string',
        'folder' => 'string',

        'type' => 'string',
        'name' => 'string',
        'extension' => 'string',
        'mime_type' => 'string',
        'filesize' => 'integer',
        'checksum' => 'string',
        'properties' => 'array',

        'downloads' => 'integer',
    ];

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'user');
    }
}
