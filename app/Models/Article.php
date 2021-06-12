<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Article model.
 *
 * @property int $id
 * @property int $user_id
 * @property ?int $image_id
 * @property int $state
 * @property string $title
 * @property string $slug
 * @property ?string $teaser
 * @property ?string $content
 * @property ?string $meta_description
 * @property ?string $meta_keywords
 * @property string $meta_robots
 * @property bool $on_mainpage
 * @property bool $is_favorite
 * @property bool $is_pinned
 * @property bool $is_catpinned
 * @property int $allow_com
 * @property int $views
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Database\Factories\ArticleFactory factory()
 *
 * @mixin \Illuminate\Database\Query\Builder
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Article extends Model implements
    Contracts\AttachableContract,
    Contracts\BelongsToUserContract,
    Contracts\CategoryableContract,
    Contracts\CommentableContract,
    Contracts\CustomizableContract,
    Contracts\ExtensibleContract,
    Contracts\TaggableContract
{
    use HasFactory;
    use Mutators\ArticleMutators;
    use Relations\AttachableTrait;
    use Relations\BelongsToUserTrait;
    use Relations\CategoryableTrait;
    use Relations\CommentableTrait;
    use Relations\CustomizableTrait;
    use Relations\ExtensibleTrait;
    use Relations\TaggableTrait;
    use Scopes\ArticleScopes;
    use Traits\Dataviewer;
    use Traits\FullTextSearch;

    /**
     * The state of the article's publicity.
     *
     * @const array
     */
    public const STATE = [
        'draft' => 0,
        'published' => 1,
        'unpublished' => 2,
    ];

    /**
     * The default table associated with the model.
     *
     * @const string
     */
    public const TABLE = 'articles';

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
        'state' => 0,
        'title' => '',
        'slug' => '',
        'teaser' => null,
        'content' => '',
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
     * {@inheritDoc}
     */
    protected $appends = [
        'comment_store_url',
        'created',
        'edit_page_url',
        'is_published',
        // 'raw_content',
        'updated',
        'url',
    ];

    /**
     * {@inheritDoc}
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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',

        'url' => 'string',
        'edit_page_url' => 'string',
        'is_published' => 'boolean',
        'comment_store_url' => 'string',
    ];

    /**
     * {@inheritDoc}
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
        'created_at',
        'updated_at',
    ];

    /**
     * {@inheritDoc}
     */
    protected $with = [
        'categories:categories.id,categories.parent_id,categories.title,categories.slug,categories.template',
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
        'updated_at',
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
        'updated_at',
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
}
