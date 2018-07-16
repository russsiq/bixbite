<?php

namespace BBCMS\Models;

use BBCMS\Models\Article;
use BBCMS\Models\BaseModel;

use BBCMS\Models\Mutators\CategoryMutators;
use BBCMS\Models\Observers\CategoryObserver;
use BBCMS\Models\Collections\CategoryCollection;

use BBCMS\Models\Relations\Fileable;
use BBCMS\Models\Relations\Imageable;

class Category extends BaseModel
{
    use CategoryMutators;
    use Fileable, Imageable;

    protected $primaryKey = 'id';
    protected $table = 'categories';
    protected $casts = [
        'image_id' => 'integer',
        'root' => 'boolean',
        'show_in_menu' => 'boolean',
        'url' => 'string',
    ];
    protected $appends = [
        'root', 'url',
    ];
    protected $fillable = [
        'parent_id', 'position', 'image_id', 'title', 'slug', 'alt_url', 'description', 'keywords',
        'info', 'img', 'show_in_menu', 'paginate', 'order_by', 'direction',
        'template',
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

    public function getRouteKeyName()
    {
        return 'slug';
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
                ])
                ->orderByRaw('ISNULL(`position`), `position` ASC')
                ->get();
        });
    }

    public function getCachedNavigationCategories()
    {
        return cache()->rememberForever('navigation', function () {
            return $this->getCachedCategories()->nested();
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
            $this->where('id', $item['id'])->update(['parent_id' => $parent_id, 'position' => $m_order]);
            if (array_key_exists('children', $item)) {
                $this->_saveList(
                    $item['children'],
                    $item['id'],
                    $m_order
                );
            }
        }
    }
}
