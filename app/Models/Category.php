<?php

namespace BBCMS\Models;

use BBCMS\Models\Article;
use BBCMS\Models\BaseModel;

use BBCMS\Models\Mutators\CategoryMutators;
use BBCMS\Models\Observers\CategoryObserver;
use BBCMS\Models\Collections\CategoryCollection;

use BBCMS\Models\Relations\Extensible;
use BBCMS\Models\Relations\Fileable;

class Category extends BaseModel
{
    use CategoryMutators;
    use Extensible, Fileable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The model's default values for attributes.
     *
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
     * The attributes that should be cast to native types.
     *
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
    ];

    /**
     * The attributes that are mass assignable.
     *
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
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'root',
        'url',
    ];

    protected $allowedFilters = [
        //
    ];

    protected $orderableColumns = [
        //
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::observe(CategoryObserver::class);
    }

    // Polymorphic relation with articles
    public function articles()
    {
        return $this->morphedByMany(Article::class, 'categoryable');
    }

    public function newCollection(array $models = [])
    {
        return new CategoryCollection($models);
    }

    public function getCachedCategories()
    {
        return cache()->rememberForever('categories', function () {
            return $this->select([
                    'categories.id',
                    'categories.title',
                    'categories.slug',
                    'categories.alt_url',
                    'categories.parent_id',
                    'categories.show_in_menu',
                ])
                ->orderByRaw('ISNULL(`position`), `position` ASC')
                ->get();
        });
    }

    public function getCachedNavigationCategories()
    {
        return cache()->rememberForever('navigation', function () {
            return $this->getCachedCategories()
                ->filter(function ($category) {
                    return $category->show_in_menu;
                })
                ->nested();
        });
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
