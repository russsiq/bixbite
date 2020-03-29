<?php

namespace App\View\Components\Widgets;

// Сторонние зависимости.
use App\Models\Article;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

/**
 * Компонент виджета записей.
 */
class ArticlesFeatured extends Component
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
    public $template = 'components.widgets.articles-featured';

    /**
     * Время кэширования виджета в секундах.
     * По умолчанию раз в сутки.
     * @var int
     */
    public $cacheTime = 60 * 60 * 24;

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
        if (isset($parameters['template'])) {
            $this->template = $parameters['template'];
        }

        $this->parameters = $parameters;
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
            // ->when($this->parameters['categories'], function ($query) {
            //     $query->whereHas('categories', function ($query) {
            //         $query->whereIn('categories.id', $this->parameters['categories']);
            //     });
            // })
            // ->when($this->parameters['tags'], function ($query) {
            //     $query->whereHas('tags', function ($query) {
            //         $query->whereIn('tags.title', $this->parameters['tags']);
            //     });
            // })
            // ->when($this->parameters['sub_day'], function ($query) {
            //     $query->where(function ($query) {
            //         $query->where('created_at', '>=', $this->parameters['sub_day'])
            //             ->orWhere('updated_at', '>=', $this->parameters['sub_day']);
            //     });
            // })
            // ->when($this->parameters['id'], function ($query) {
            //     $query->whereIn('id', $this->parameters['id']);
            // })
            // ->when($this->parameters['user_id'], function ($query) {
            //     $query->whereIn('user_id', $this->parameters['user_id']);
            // })
            // ->where('articles.on_mainpage', $this->parameters['on_mainpage'])
            // ->where('articles.state', $this->parameters['state'])
            // ->orderBy($this->parameters['order_by'], $this->parameters['direction'])
            ->when($this->parameters['skip'] ?? false, function ($query) {
                $query->skip($this->parameters['skip']);
            })
            ->limit($this->parameters['limit'] ?? 8)
            ->get();
    }
}
