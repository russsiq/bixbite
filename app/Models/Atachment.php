<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Atachment extends Model
{
    use HasFactory;

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
        'checksum' => 'boolean',
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
