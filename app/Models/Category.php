<?php

namespace App\Models;

// Сторонние зависимости.
use App\Models\Article;
use App\Models\Collections\CategoryCollection;

class Category extends BaseModel
{
    use Mutators\CategoryMutators,
        Relations\Extensible,
        Relations\Fileable,
        Scopes\CategoryScopes;

    /**
     * Таблица БД, ассоциированная с моделью.
     * @var string
     */
    protected $table = 'categories';

    /**
     * Первичный ключ таблицы БД.
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Значения по умолчанию для атрибутов модели.
     * @var array
     */
    protected $attributes = [
        'image_id' => null,
        'parent_id' => 0,
        'position' => 1,
        'title' => '',
        'slug' => '',
        'alt_url' => null,
        'description' => '',
        'keywords' => '',
        'info' => '',
        'show_in_menu' => true,
        'paginate' => 8,
        'order_by' => 'id',
        'direction' => 'desc',
        'template' => null,

    ];

    /**
     * Аксессоры, добавляемые при сериализации модели.
     * @var array
     */
    protected $appends = [
        'root',
        'url',

    ];

    /**
     * Атрибуты, которые должны быть типизированы.
     * @var array
     */
    protected $casts = [
        'image_id' => 'integer',
        'parent_id' => 'integer',
        'position' => 'integer',
        'title' => 'string',
        'slug' => 'string',
        'description' => 'string',
        'keywords' => 'string',
        'info' => 'string',
        'show_in_menu' => 'boolean',
        'paginate' => 'integer',
        'order_by' => 'string',
        'direction' => 'string',
        'template' => 'string',
        // Прикрепляемые поля.
        'root' => 'boolean',
        'url' => 'string',
        // Необязательные поля.
        // 'alt_url' => null,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',

    ];

    /**
     * Атрибуты, для которых разрешено массовое присвоение значений.
     * @var array
     */
    protected $fillable = [
        'image_id',
        'parent_id',
        'position',
        'title',
        'slug',
        'alt_url',
        'description',
        'keywords',
        'info',
        'show_in_menu',
        'paginate',
        'order_by',
        'direction',
        'template',
        // Dates
        'created_at',
        'updated_at',

    ];

    // Polymorphic relation with articles
    public function articles()
    {
        return $this->morphedByMany(Article::class, 'categoryable');
    }

    public function newCollection(array $models = [])
    {
        return new CategoryCollection($models);
    }

    // return count changed categories
    public function positionReset()
    {
        return $this->where('parent_id', '>', 0)
            ->orWhere('position', '>', 0)
            ->update([
                'parent_id' => 0,
                'position' => null,
            ]);
    }

    public function positionUpdate(object $data)
    {
        \DB::transaction(function () use ($data) {
            $this->_saveList($data->list);
        });

        return true;
    }

    protected function _saveList(array $list, int $parent_id = 0, int &$m_order = 0)
    {
        foreach ($list as $item) {
            $m_order++;

            $this->where('id', $item['id'])
                ->update([
                    'parent_id' => $parent_id,
                    'position' => $m_order,
                ]);

            if (array_key_exists('children', $item)) {
                $this->_saveList($item['children'], $item['id'], $m_order);
            }
        }
    }
}
