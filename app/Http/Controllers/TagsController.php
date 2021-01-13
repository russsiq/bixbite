<?php

namespace App\Http\Controllers;

// Сторонние зависимости.
use App\Models\Tag;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Контроллер управляющий Тегами сайта.
 */
class TagsController extends SiteController
{
    /**
     * Модель Тег.
     *
     * @var Tag
     */
    protected $model;

    /**
     * Настройки модели Тег.
     *
     * @var object
     */
    protected $settings;

    /**
     * Макет шаблонов контроллера.
     *
     * @var string
     */
    protected $template = 'tags';

    /**
     * Создать экземпляр контроллера.
     *
     * @param  Tag  $model
     */
    public function __construct(
        Tag $model
    ) {
        $this->model = $model;

        $this->settings = (object) setting($model->getTable());
    }

    /**
     * Отобразить список ресурса.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function index(Request $request): Renderable
    {
        $related = 'articles';

        $tags = $this->model->select([
            'tags.id',
            'tags.title',

        ])
            ->when($related, function (Builder $builder, string $related) {
                $builder->whereHas($related)
                    ->withCount($related)
                    ->orderBy(
                        $this->settings->order_by ?? "{$related}_count",
                        $this->settings->direction ?? 'desc'
                    );
            })
            ->paginate($this->settings->paginate ?? 8);

        pageinfo([
            'title' => $this->settings->meta_title ?? 'Tags',
            'description' => $this->settings->meta_description ?? 'All tags on site.',
            'keywords' => $this->settings->meta_keywords ?? 'tags',
            'robots' => 'noindex, follow',
            'url' => route('tags.index'),
            'is_index' => true,

        ]);

        return $this->renderOutput(__FUNCTION__, compact('tags'));
    }
}
