<?php

namespace BBCMS\View\Components\Widgets;

// Сторонние зависимости.
use BBCMS\Models\Comment;
use Illuminate\View\Component;
use Illuminate\View\View;

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
     * @return View|string
     */
    public function render()
    {
        return view($this->template);
    }
}
