<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        // 'user_id',
        'title', 'slug', 'teaser', 'content',
        'meta_description', 'meta_keywords', 'meta_robots',
        'on_mainpage', 'is_favorite', 'is_pinned',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',

        'title' => 'string',
        'slug' => 'string',
        'teaser' => 'string',
        'content' => 'string',

        'meta_description' => 'string',
        'meta_keywords' => 'string',
        'meta_robots' => 'string',

        'on_mainpage' => 'boolean',
        'is_favorite' => 'boolean',
        'is_pinned' => 'boolean',

        'views' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
