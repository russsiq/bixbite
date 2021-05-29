<?php

namespace App\Models;

use App\Models\Collections\CategoryCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Category model.
 *
 * @property int $id
 * @property ?int $image_id
 * @property int $parent_id
 * @property int $position
 * @property string $title
 * @property string $slug
 * @property ?string $alt_url
 * @property ?string $info
 * @property ?string $meta_description
 * @property ?string $meta_keywords
 * @property string $meta_robots
 * @property bool $show_in_menu
 * @property string $order_by
 * @property string $direction
 * @property int $paginate
 * @property ?string $template
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $articles Get all of the articles for the category.
 *
 * @method static \Database\Factories\CategoryFactory factory()
 *
 * @mixin \Illuminate\Database\Query\Builder
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Category extends Model implements
    Contracts\AttachableContract,
    Contracts\CustomizableContract,
    Contracts\ExtensibleContract
{
    use HasFactory;
    use Mutators\CategoryMutators;
    use Relations\AttachableTrait;
    use Relations\CustomizableTrait;
    use Relations\ExtensibleTrait;
    use Scopes\CategoryScopes;

    /**
     * The default table associated with the model.
     *
     * @const string
     */
    public const TABLE = 'categories';

    /**
     * {@inheritDoc}
     */
    protected $table = self::TABLE;

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    protected $appends = [
        'url',
        'edit_page_url',
        'is_root',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'image_id' => 'integer',
        'parent_id' => 'integer',
        'position' => 'integer',
        'show_in_menu' => 'boolean',
        'paginate' => 'integer',
        'url' => 'string',
        'edit_page_url' => 'string',
        'is_root' => 'boolean',
    ];

    /**
     * {@inheritDoc}
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
     * Get a set of relationship counts onto the `categoryable` relationship model.
     *
     * @return integer
     */
    public function getCategoryableCount(): int
    {
        if (! $this->exists) {
            return 0;
        }

        return $this->query()
            ->select([
                'categories.id',
                'categoryables.category_id as pivot_category_id',
            ])
            ->join('categoryables', 'categories.id', '=', 'categoryables.category_id')
            ->where('categories.id', $this->id)
            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Дальше идет мешанина, которой самое место в Действиях, например, `MassUpdate`.
    |--------------------------------------------------------------------------
    */

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
