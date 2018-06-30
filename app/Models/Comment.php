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
    ];
    protected $appends = [
        'created', 'updated', 'by_user'
    ];
    protected $fillable = [
        'name','email','site','content','user_id','article_id','parent_id', 'commentable_type', 'commentable_id',
    ];

    /**
     * All of the relationships to be touched.
     * Automatically "touch" the updated_at timestamp of the owning Forum
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
