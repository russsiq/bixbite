<?php

namespace BBCMS\View\Components\Widgets;

// Сторонние зависимости.
use BBCMS\Models\Article;
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
     * Время кэширования виджета.
     * @var string
     */
    public $cacheTime = 24 * 60 *60;

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

    public function items(): Collection
    {
        return Article::without('categories')
            ->selectRaw('
                year(created_at) year,
                monthname(created_at) month,
                count(*) as count
            ')
            ->published()
            ->groupBy('year', 'month')
            ->orderByRaw('min(created_at) desc')
            ->limit($this->parameters['limit'] ?? 6)
            ->get()
            ->transform(function ($item, $key) {
                $item->monthname = __('common.'.$item->month);

                return $item;
            });
    }
}
