<?php

namespace App\Models;

// Сторонние зависимости.
use App\Models\Article;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Модель Тега.
 */
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
     * Атрибуты, которые должны быть типизированы.
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

    /**
     * Получить ключ маршрута для модели.
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'title';
    }

    /**
     * Получить все записи, которым присвоен данный тег.
     * @return MorphToMany
     */
    public function articles(): MorphToMany
    {
        return $this->morphedByMany(
            /* $related */ Article::class,
            /* $name */ 'taggable',
            /* $table */ 'taggables',
            /* $foreignPivotKey */ 'tag_id',
            /* $relatedPivotKey */ 'taggable_id'
        );
    }

    /**
     * Получить атрибут `url`.
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return route('tags.tag', $this);
    }

    /**
     * Удалить неиспользуемые теги.
     * @return void
     */
    public function reIndex(): void
    {
        // $tags = $this->select([
        //         'tags.id',
        //         'taggables.tag_id as pivot_tag_id',
        //     ])
        //     ->join('taggables', 'tags.id', '=', 'taggables.tag_id')
        //     ->get()
        //     ->modelKeys();
        //
        // $this->diff($tags)->delete();

        $tags = $this->select([
                'tags.id',
                'taggables.tag_id as pivot_tag_id',
            ])
            ->join('taggables', 'tags.id', '=', 'taggables.tag_id')
            ->get()
            ->keyBy('id')
            ->all();

        $this->whereNotIn('id', array_keys($tags))
            ->delete();
    }
}
