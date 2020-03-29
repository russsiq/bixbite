<?php

namespace App\View\Components\Widgets;

// Сторонние зависимости.
use App\Models\Article;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

/**
 * Компонент виджета архива записей.
 */
class ArticlesArchives extends Component
{
    /**
     * Заголовок виджета.
     * @var string
     */
    public $title;

    /**
     * Активность виджета.
     * @var boolean
     */
    public $isActive = false;

    /**
     * Шаблон виджета.
     * @var string
     */
    public $template = 'components.widgets.articles-archives';

    /**
     * Время кэширования виджета в секундах.
     * По умолчанию раз в 30 дней.
     * @var int
     */
    public $cacheTime = 60 * 60 * 24 * 30;

    /**
     * Создать экземпляр компонента.
     */
    public function __construct(
        array $parameters = []
    ) {
        $this->configure($parameters);
    }

    /**
     * Конфигурирование компонента.
     * @param  array  $parameters
     * @return void
     */
    protected function configure(array $parameters): void
    {

    }

    /**
     * Получить шаблон / содержимое, представляющее компонент.
     * @return Renderable
     */
    public function render(): Renderable
    {
        return view($this->template);
    }

    public function months(): Collection
    {
        return Article::without('categories')
            ->selectRaw('
                YEAR(created_at) AS year,
                MONTHNAME(created_at) AS month,
                count(*) AS count
            ')
            ->distinct()
            ->published()
            ->groupBy('year', 'month')
            ->latest()
            ->limit($this->parameters['limit'] ?? 12)
            ->get();
    }
}
