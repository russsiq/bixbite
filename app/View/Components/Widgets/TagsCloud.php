<?php

namespace App\View\Components\Widgets;

// Сторонние зависимости.
use App\Models\Tag;
use App\Support\WidgetAbstract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Компонент виджета `Облако тегов`.
 */
class TagsCloud extends WidgetAbstract
{
    /**
     * Входящие параметры виджета.
     * @var array
     */
    protected $parameters = [
        // Заголовок виджета.
        'title' => 'Tags Cloud',

        // Активность виджета.
        'is_active' => true,

        // Шаблон виджета.
        'template' => 'components.widgets.tags-cloud',

        // Время кэширования виджета в секундах.
        // По умолчанию раз в неделю.
        'cache_time' => 60 * 60 * 24 * 7,

        // Количество отображаемых месяцев.
        'limit' => 8,

        // Извлекать теги только с указанным отношением.
        'relation' => 'articles',

        // Сортировка тегов.
        'order_by' => 'articles_count',

        // Порядок сортировки.
        'direction' => 'desc',

    ];

    /**
     * Получить массив правил валидации,
     * которые будут применены к входящим параметрам.
     * @return array
     */
    protected function rules(): array
    {
        return [
            // Заголовок виджета.
            'title' => [
                'sometimes',
                'required',
                'string',
                'regex:/^[\w\s\.-_]+$/u',

            ],

            // Активность виджета.
            'is_active' => [
                'sometimes',
                'required',
                'boolean',

            ],

            // Шаблон виджета.
            'template' => [
                'sometimes',
                'required',
                'string',

            ],

            // Время кэширования виджета в секундах.
            'cache_time' => [
                'sometimes',
                'required',
                'integer',

            ],

            // Количество отображаемых месяцев.
            'limit' => [
                'sometimes',
                'required',
                'integer',

            ],

            // Извлекать теги только с указанным отношением.
            'relation' => [
                'sometimes',
                'required',
                'string',
                'in:articles,profiles',

            ],

            // Сортировка тегов.
            'order_by' => [
                'sometimes',
                'required',
                'string',
                'in:id,title,created_at,updated_at,articles_count,profiles_count',

            ],

            // Порядок сортировки.
            'direction' => [
                'sometimes',
                'required',
                'string',
                'in:desc,asc',

            ],

        ];
    }

    /**
     * Получить коллекцию тегов.
     * @return Collection
     */
    public function tags(): Collection
    {
        if (empty($this->cacheTime())) {
            return $this->resolveTags();
        }

        return $this->cache->remember(
            $this->cacheKey(),
            $this->cacheTime(),
            function () {
                return $this->resolveTags();
            }
        );
    }

    /**
     * Извлечь коллекцию тегов из хранилища.
     * @return Collection
     */
    protected function resolveTags(): Collection
    {
        $relation = $this->parameter('relation');

        return Tag::select([
                'tags.id',
                'tags.title',
                'tags.slug',
            ])
            ->withCount($relation)
            ->whereHas($relation)
            ->orderBy($this->parameter('order_by'), $this->parameter('direction'))
            ->limit($this->parameter('limit'))
            ->get();
    }
}
