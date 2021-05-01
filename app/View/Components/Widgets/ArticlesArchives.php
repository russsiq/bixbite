<?php

namespace App\View\Components\Widgets;

// Сторонние зависимости.
use App\Models\Article;
use App\Support\WidgetAbstract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Компонент виджета `Архив записей`.
 */
class ArticlesArchives extends WidgetAbstract
{
    /**
     * Входящие параметры виджета.
     * @var array
     */
    protected $parameters = [
        // Заголовок виджета.
        'title' => 'Articles Archives',

        // Активность виджета.
        'is_active' => true,

        // Шаблон виджета.
        'template' => 'components.widgets.articles-archives',

        // Время кэширования виджета в секундах.
        // По умолчанию раз в 30 дней.
        'cache_time' => 60 * 60 * 24 * 30,

        // Количество отображаемых месяцев.
        'limit' => 12,

        // Подсчет количества записей за месяц.
        'has_count' => true,

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

            // Подсчет количества записей за месяц.
            'has_count' => [
                'sometimes',
                'required',
                'boolean',

            ],

        ];
    }

    /**
     * Получить коллекцию месяцев из хранилища.
     * @return Collection
     */
    public function months(): Collection
    {
        if (empty($this->cacheTime())) {
            return $this->resolveMonths();
        }

        return $this->cache->remember(
            $this->cacheKey(),
            $this->cacheTime(),
            function () {
                return $this->resolveMonths();
            }
        );
    }

    /**
     * Извлечь коллекцию месяцев из хранилища.
     * @return Collection
     */
    protected function resolveMonths(): Collection
    {
        return Article::without('categories')
            ->selectRaw('
                YEAR(created_at) AS year,
                MONTHNAME(created_at) AS month
            ')
            ->distinct()
            ->when($this->parameter('has_count'), function (Builder $builder) {
                $builder->selectRaw('count(*) AS count');
            })
            ->published()
            ->visibleOnMainpage()
            ->groupBy('year', 'month')
            ->latest()
            ->limit($this->parameter('limit'))
            ->get();
    }
}
