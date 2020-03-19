<?php

namespace BBCMS\View\Components\Widgets;

// Сторонние зависимости.
use BBCMS\Models\Article;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

/**
 * Компонент виджета похожих записей.
 */
class ArticlesRelated extends Component
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
    public $template = 'components.widgets.articles-related';

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
     * Получить коллекцию записей.
     * @return Collection
     */
    public function articles(): Collection
    {
        return Article::shortArticle()
            ->where('articles.id', '<>', $this->article->id)
            ->search(teaser($this->article->title . ' ' . $this->article->content, 255, ''))
            ->limit(3)
            ->get();
    }
}
