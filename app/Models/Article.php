<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Article model.
 *
 * @property-read int $id
 * @property-read int $user_id
 * @property-read ?int $image_id
 * @property-read int $state
 * @property-read string $title
 * @property-read string $slug
 * @property-read ?string $teaser
 * @property-read ?string $content
 * @property-read ?string $meta_description
 * @property-read ?string $meta_keywords
 * @property-read string $meta_robots
 * @property-read bool $on_mainpage
 * @property-read bool $is_favorite
 * @property-read bool $is_pinned
 * @property-read bool $is_catpinned
 * @property-read int $allow_com
 * @property-read int $views
 * @property-read \Illuminate\Support\Carbon $published_at
 * @property-read \Illuminate\Support\Carbon $created_at
 * @property-read \Illuminate\Support\Carbon $updated_at
 *
 * @property-read string $url
 * @property-read ?string $edit_page
 * @property-read bool $is_published
 * @property-read string $comment_store_url
 *
 * @method \Illuminate\Database\Eloquent\Builder favorites()
 * @method \Illuminate\Database\Eloquent\Builder filter(array $filters)
 * @method \Illuminate\Database\Eloquent\Builder drafts()
 * @method \Illuminate\Database\Eloquent\Builder published()
 * @method \Illuminate\Database\Eloquent\Builder unPublished()
 * @method \Illuminate\Database\Eloquent\Builder visibleOnMainpage(bool $isVisible = true)
 * @method static \Illuminate\Database\Eloquent\Builder shortArticle()
 */
class Article extends Model
{
    use Mutators\ArticleMutators;
    use Relations\Attachable;
    use Relations\Categoryable;
    use Relations\Commentable;
    use Relations\Extensible;
    use Relations\Taggable;
    use Scopes\ArticleScopes;
    use Traits\Dataviewer;
    use Traits\FullTextSearch;
    use HasFactory;

    public const TABLE = 'articles';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = self::TABLE;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    // public $timestamps = false;

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'user_id' => null,
        'image_id' => null,
        'state' => 0,
        'title' => '',
        'slug' => '',
        'teaser' => null,
        'content' => null,
        'meta_description' => null,
        'meta_keywords' => null,
        'meta_robots' => 'all',
        'on_mainpage' => true,
        'is_favorite' => false,
        'is_pinned' => false,
        'is_catpinned' => false,
        'allow_com' => 2,
        'views' => 0,
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'url',
        'edit_page',
        'is_published',
        'comment_store_url',
        'created',
        'updated',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'image_id' => 'integer',
        'state' => 'integer',
        'content' => 'string',
        'on_mainpage' => 'boolean',
        'is_favorite' => 'boolean',
        'is_pinned' => 'boolean',
        'is_catpinned' => 'boolean',
        'allow_com' => 'integer',
        'views' => 'integer',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',

        'url' => 'string',
        'edit_page' => 'string',
        'is_published' => 'boolean',
        'comment_store_url' => 'string',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'image_id',
        'state',
        'title',
        'slug',
        'teaser',
        'content',
        'meta_description',
        'meta_keywords',
        'meta_robots',
        'on_mainpage',
        'is_favorite',
        'is_pinned',
        'is_catpinned',
        'allow_com',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'categories:categories.id,categories.title,categories.slug,categories.template',
    ];

    /**
     * Attributes by which filtering is allowed.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'state',
        'title',
        'content',
        'views',
        'created_at',
        'attachments.count',
        'categories.id',
        'comments.content',
        'comments.is_approved',
        'comments.count',
        'comments.created_at',
    ];

    /**
     * The attributes by which sorting is allowed.
     *
     * @var array
     */
    protected $orderableColumns = [
        'id',
        'state',
        'title',
        'views',
        'created_at',
    ];

    /**
     * Attributes to be used for full text search.
     *
     * @var array
     */
    protected $searchable = [
        'title',
        'content',
    ];

    /**
     * Get the user that owns the article.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'user');
    }

    /**
     * Get the settings for the article.
     *
     * @return BelongsTo
     */
    public function settings(): HasMany
    {
        return $this->hasMany(Setting::class, 'module_name');
    }

    /**
     * Область запроса, содержащая только избранные записи.
     *
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopeFavorites(Builder $builder): Builder
    {
        return $builder->addSelect('articles.is_favorite')
            ->where('is_favorite', true);
    }

    /**
     * Фильтрация записей по часто используемым критериям.
     *
     * @param  Builder  $builder
     * @param  array  $filters
     * @return Builder
     */
    public function scopeFilter(Builder $builder, array $filters): Builder
    {
        return $builder->when($filters['month'], function(Builder $builder, string $month) {
            $builder->addSelect('articles.created_at')
                ->whereMonth('created_at', Carbon::parse($month)->month);
        })
        ->when($filters['year'], function(Builder $builder, int $year) {
            $builder->addSelect('articles.created_at')
                ->whereYear('created_at', $year);
        })
        ->when($filters['user_id'], function(Builder $builder, int $user_id) {
            $builder->addSelect('articles.user_id')
                ->where('user_id', $user_id);
        });
    }

    /**
     * Область запроса, содержащая только черновики.
     *
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopeDrafts(Builder $builder): Builder
    {
        return $builder->where('articles.state', 0);
    }

    /**
     * Область запроса, содержащая только опубликованные записи.
     *
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopePublished(Builder $builder): Builder
    {
        // if (! $builder->getQuery()->distinct) {
        //     $builder->addSelect('articles.state');
        // }

        return $builder->where('articles.state', 1);
    }

    /**
     * Область запроса, содержащая только неопубликованные записи.
     *
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopeUnPublished(Builder $builder): Builder
    {
        return $builder->where('articles.state', 2);
    }

    /**
     * Область запроса записей, отображаемых / не отображаемых на главной странице.
     *
     * @param  Builder  $builder
     * @param  boolean  $isVisible
     * @return Builder
     */
    public function scopeVisibleOnMainpage(Builder $builder, bool $isVisible = true): Builder
    {
        return $builder->where('articles.on_mainpage', $isVisible);
    }
}
