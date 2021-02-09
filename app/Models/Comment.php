<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        // 'user_id',
        'parent_id',
        'content',
        'name',
        'email',
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
        'name' => 'string',
        'email' => 'string',
        'user_ip' => 'string',
        'is_approved' => 'boolean',
    ];

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
}
