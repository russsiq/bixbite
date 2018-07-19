<?php

namespace BBCMS\Models;

use BBCMS\Models\User;
use BBCMS\Models\BaseModel;

use BBCMS\Models\Mutators\ArticleMutators;
use BBCMS\Models\Observers\ArticleObserver;
use BBCMS\Models\Scopes\ArticleScopes;

use BBCMS\Models\Relations\Extensible;
use BBCMS\Models\Relations\Fileable;
use BBCMS\Models\Relations\Taggable;
use BBCMS\Models\Relations\Commentable;
use BBCMS\Models\Relations\Categoryable;

class Article extends BaseModel
{
    use ArticleMutators, ArticleScopes;
    use Extensible, Fileable, Taggable, Commentable, Categoryable;

    protected $primaryKey = 'id';
    protected $table = 'articles';
    public $timestamps = false;
    protected $casts = [
        'title' => 'string',
        'img' => 'array',
        'image_id' => 'integer',
        'on_mainpage' => 'boolean',
        'is_favorite' => 'boolean',
        'is_pinned' => 'boolean',
        'is_catpinned' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    protected $appends = [
        'url',
        'created',
        'updated',
    ];
    protected $fillable = [
        'user_id',
        'image_id',
        'title',
        'slug',
        'teaser',
        'content',
        'description',
        'keywords',
        // Flags
        'allow_com',
        'state',
        'robots',
        'on_mainpage',
        'is_favorite',
        'is_pinned',
        'is_catpinned',
        // Extension
        'views',
        'votes',
        'rating',
        // Dates
        'created_at',
        'updated_at',
    ];

    protected $with = [
        'categories:categories.id,categories.slug,categories.title'
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::observe(ArticleObserver::class);
    }

    /**
    * Get the route key for the model.
    *
    * @return string
    */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'user');
    }

    public function settings()
    {
        return $this->hasMany(Setting::class, 'module_name');
    }
}
