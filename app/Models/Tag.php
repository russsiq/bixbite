<?php

namespace BBCMS\Models;

use BBCMS\Models\BaseModel;
use BBCMS\Models\Article;

class Tag extends BaseModel
{
    protected $table = 'tags';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $casts = [
        'title' => 'string',
    ];
    protected $fillable = [
        'title',
    ];

    public function getRouteKeyName()
    {
        return 'title';
    }

    public function articles()
    {
        return $this->morphedByMany(Article::class, 'taggable');
    }

    public function getUrlAttribute()
    {
        return route('tags.tag', $this);
    }

    // Delete unused tags
    public function reIndex()
    {
        $tags = $this->select(['tags.id', 'taggables.tag_id as pivot_tag_id'])
            ->join('taggables', 'tags.id', '=', 'taggables.tag_id')
            ->get()->keyBy('id')->all();

        $this->whereNotIn('id', array_keys($tags))->delete();

        // dd($this->has($model->getMorphClass(), '=', 0)->toSql());
        //
        // The commentable relation on the Comment model will return either a
        // Post or Video instance, depending on which type of model owns the comment.
        // $commentable = $comment->commentable;
    }
}
