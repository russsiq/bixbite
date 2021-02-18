<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;

/**
 * Article model.
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $slug
 * @property string|null $teaser
 * @property string|null $content
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property string $meta_robots
 * @property bool $on_mainpage
 * @property bool $is_favorite
 * @property bool $is_pinned
 * @property int $views
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
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

    public function atachments(): MorphMany
    {
        return $this->morphMany(Atachment::class, 'attachable');
    }

    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'user');
    }
}
