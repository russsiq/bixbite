<?php

namespace App\View\Components\Widgets;

// Сторонние зависимости.
use App\Models\Article;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

/**
 * Компонент виджета соседних записей.
 */
class ArticlesNeighboring extends Component
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
    public $template = 'components.widgets.articles-neighboring';

    /**
     * Время кэширования виджета.
     * @var string
     */
    public $cacheTime = 24 * 60 *60;

    /**
     * Текущая запись.
     * @var Article
     */
    public $article;

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
        // Проверить существование текущей записи.
        if (! pageinfo('is_article') or ! pageinfo('article')->id) {
            throw new \Exception('Widget '.self::class.' not available on this page.');
        }

        $this->article = pageinfo('article');

    }

    /**
     * Получить шаблон / содержимое, представляющее компонент.
     * @return Renderable
     */
    public function render(): Renderable
    {
        return view($this->template);
    }

    /**
     * Получить предыдущую запись.
     * @return Article|null
     */
    public function previous(): ?Article
    {
        return Article::select([
                'articles.id',
                'articles.title',
                'articles.slug',
                'articles.state',
            ])
            ->published()
            ->where('articles.id', '<', $this->article->id)
            ->orderBy('articles.id', 'desc')
            ->limit(1)
            ->get()
            ->first();
    }

    /**
     * Получить следющую запись.
     * @return Article|null
     */
    public function next(): ?Article
    {
        return Article::select([
                'articles.id',
                'articles.title',
                'articles.slug',
                'articles.state',
            ])
            ->published()
            ->where('articles.id', '>', $this->article->id)
            ->orderBy('articles.id', 'asc')
            ->limit(1)
            ->get()
            ->last();
    }
}
