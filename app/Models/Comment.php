<?php

namespace BBCMS\Models;

use BBCMS\Models\{BaseModel, Article, User};
use BBCMS\Models\Mutators\CommentMutators;
use BBCMS\Models\Collections\CommentCollection;

class Comment extends BaseModel
{
    use CommentMutators;

    protected $primaryKey = 'id';
    protected $table = 'comments';
    protected $casts = [
        'is_approved' => 'boolean',
        'user_id' => 'integer',
        'parent_id' => 'integer',
        'commentable_type' => 'string',
        'commentable_id' => 'integer',
        'name' => 'string',
        'email' => 'string',
        'content' => 'string',

        'url' => 'string',
        'created' => 'timestamp',
        'updated' => 'timestamp',
        'by_user' => 'string',
        'by_author' => 'string',
    ];
    protected $appends = [
        'url',
        'created',
        'updated',
        'by_user',
        'by_author',
    ];
    protected $fillable = [
        'is_approved',
        'parent_id',
        'user_id',
        'commentable_type',
        'commentable_id',
        'name',
        'email',
        'user_ip',
        'content',
    ];

    // Not used.
    protected $with = [
        // 'commentable'
    ];

    /**
     * All of the relationships to be touched.
     * Automatically "touch" the updated_at timestamp of the owning Forum.
     *
     * @var array
     */
    // protected $touches = ['forum'];

    public function article()
    {
        return $this->belongsTo(Article::class, 'commentable_id', 'id', 'commentable_type');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'user');
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function newCollection(array $models = [])
    {
        return new CommentCollection($models);
    }
}
