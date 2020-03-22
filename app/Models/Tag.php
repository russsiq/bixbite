<?php

namespace App\Models;

// Сторонние зависимости.
use App\Models\Article;

class Tag extends BaseModel
{
    /**
     * Таблица БД, ассоциированная с моделью.
     * @var string
     */
    protected $table = 'tags';

    /**
     * Первичный ключ таблицы БД.
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Следует ли обрабатывать временные метки модели.
     * @var bool
     */
    public $timestamps = false;

    /**
     * Атрибуты, которые должны быть приведены к базовым типам.
     * @var array
     */
    protected $casts = [
        'title' => 'string',

    ];

    /**
     * Атрибуты, для которых разрешено массовое присвоение значений.
     * @var array
     */
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
