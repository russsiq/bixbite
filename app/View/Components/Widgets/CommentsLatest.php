<?php

namespace App\View\Components\Widgets;

// Сторонние зависимости.
use App\Models\Comment;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

/**
 * Компонент виджета последних комментариев.
 */
class CommentsLatest extends Component
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
    public $template = 'components.widgets.comments-latest';

    /**
     * Время кэширования виджета в секундах.
     * По умолчанию раз в 15 минут.
     * @var int
     */
    public $cacheTime = 60 * 15;

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

    /**
     * Получить коллекцию комментариев.
     * @return Collection
     */
    public function comments(): Collection
    {
        return Comment::with([
                'user:users.id,users.name,users.email,users.avatar',
                'article:articles.id,articles.title,articles.slug,articles.state',
            ])
            ->where('commentable_type', 'articles')
            ->where('is_approved', true)
            ->latest()
            ->limit(8)
            ->get()
            ->treated(false);
    }
}
