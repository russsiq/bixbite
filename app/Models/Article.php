<?php

namespace BBCMS\Models;

use BBCMS\Models\BaseModel;
use BBCMS\Models\User;
use BBCMS\Models\Mutators\ArticleMutators;
use BBCMS\Models\Scopes\FilterScope;
use BBCMS\Models\Scopes\PublishedScope;

use BBCMS\Models\Relations\Fileable;
use BBCMS\Models\Relations\Imageable;

use BBCMS\Models\Relations\Taggable;
use BBCMS\Models\Relations\Commentable;
use BBCMS\Models\Relations\Categoryable;

class Article extends BaseModel
{
    use ArticleMutators;
    use FilterScope, PublishedScope; // ArchiveScope,
    use Taggable, Fileable, Imageable, Commentable, Categoryable;

    // protected $with = ['media'];

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
        'url', 'created', 'updated',
    ];
    protected $fillable = [
        'user_id', 'image_id', 'title', 'slug', 'teaser', 'content', 'description', 'keywords',
        // Flags ?
        'allow_com', 'state', 'robots', 'on_mainpage', 'is_favorite', 'is_pinned', 'is_catpinned',
        // Extension
        'views', 'votes', 'rating',
        // Dates
        'created_at', 'updated_at',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($article) {
            $article->tags()->detach();
            $article->categories()->detach();
            $article->comments()->get(['id'])->each->delete();
            $article->image()->get()->each->delete();
            $article->files()->get()->each->delete();
        });

        // // Order by name ASC
        // static::addGlobalScope('order', function (Builder $builder) {
        //     $builder->orderBy('name', 'asc');
        // });
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

    public function scopeShortArticle($query, ...$filters)
    {
        return $query
            ->select([
                'articles.id','articles.user_id','articles.image_id',
                'articles.slug','articles.title','articles.content',
                'articles.created_at','articles.updated_at',
                'articles.views',
            ])
            ->with([
                'image',
                'categories:categories.id,categories.slug,categories.title',
                'user:users.id,users.name', // ,users.email,users.avatar
            ])
            ->withCount(['comments'])
            ->where('articles.state', 'published');
    }

    public function scopeFullArticle($query, $article_id)
    {
        return $query
            ->with([
                'categories:categories.id,categories.title,categories.slug',
                'user:users.id,users.name,users.email,users.avatar',
            ])
            ->withCount(['comments', 'tags'])
            ->where('articles.state', 'published')
            ->where('articles.id', $article_id);
    }

    public function scopeSearchByKeyword($query, $keyword) // ToDo Add new xfields
    {
        return $query
            ->where('title', 'like', '%' . $keyword . '%')
            ->orWhere('content', 'like', '%' . $keyword . '%');
    }
}
