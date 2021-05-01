<?php

namespace App\Models;

use App\Models\Collections\CategoryCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Category model.
 *
 * @property-read int $id
 * @property-read ?int $image_id
 * @property-read int $parent_id
 * @property-read int $position
 * @property-read string $title
 * @property-read string $slug
 * @property-read ?string $alt_url
 * @property-read ?string $info
 * @property-read ?string $meta_description
 * @property-read ?string $meta_keywords
 * @property-read string $meta_robots
 * @property-read bool $show_in_menu
 * @property-read string $order_by
 * @property-read string $direction
 * @property-read int $paginate
 * @property-read ?string $template
 *
 * @property-read string $url
 * @property-read ?string $edit_page
 * @property-read bool $is_root
 *
 * @method \Illuminate\Database\Eloquent\Builder short()
 */
class Category extends Model
{
    use Mutators\CategoryMutators;
    use Relations\Extensible;
    use Relations\Attachable;
    use Scopes\CategoryScopes;
    use HasFactory;

    public const TABLE = 'categories';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = self::TABLE;

    /**
     * The model's attributes.
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
        'info' => null,
        'meta_description' => null,
        'meta_keywords' => null,
        'meta_robots' => 'all',
        'show_in_menu' => true,
        'order_by' => 'id',
        'direction' => 'desc',
        'paginate' => 8,
        'template' => null,
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var string[]
     */
    protected $appends = [
        'url',
        'edit_page',
        'is_root',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'image_id' => 'integer',
        'parent_id' => 'integer',
        'position' => 'integer',
        'show_in_menu' => 'boolean',
        'paginate' => 'integer',
        'url' => 'string',
        'edit_page' => 'string',
        'is_root' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'image_id',
        'parent_id',
        'position',
        'title',
        'slug',
        'alt_url',
        'info',
        'meta_description',
        'meta_keywords',
        'meta_robots',
        'show_in_menu',
        'order_by',
        'direction',
        'paginate',
        'template',
    ];

    /**
     * Получить все записи, относящиеся к данной категории.
     *
     * @return MorphToMany
     */
    public function articles()
    {
        return $this->morphedByMany(
            /* $related */ Article::class,
            /* $name */ 'categoryable',
            /* $table */ 'categoryables',
            /* $foreignPivotKey */ 'category_id',
            /* $relatedPivotKey */ 'categoryable_id'
        );
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return CategoryCollection
     */
    public function newCollection(array $models = []): CategoryCollection
    {
        return new CategoryCollection($models);
    }

    /**
     * Сбросить позиции для всех категорий и
     * сохранить обновленные модели в базу данных.
     * @return bool
     */
    public function positionReset(): bool
    {
        return $this->where('parent_id', '>', 0)
            ->orWhere('position', '>', 0)
            ->update([
                'parent_id' => 0,
                'position' => null,

            ]);
    }

    /**
     * Обновить позиции для всех категорий и
     * сохранить обновленные модели в базу данных.
     *
     * @return bool
     */
    public function positionUpdate(object $data): bool
    {
        DB::transaction(function () use ($data) {
            $this->_saveList($data->list);
        });

        return true;
    }

    /**
     * Обновить позиции для каждой категорий из списка и
     * сохранить обновленные модели в базу данных.
     *
     * @param  array  $list
     * @param  integer  $parent_id
     * @param  integer  $m_order
     * @return void
     */
    protected function _saveList(array $list, int $parent_id = 0, int &$m_order = 0): void
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
