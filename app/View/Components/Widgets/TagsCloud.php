<?php

namespace App\View\Components\Widgets;

// Сторонние зависимости.
use App\Models\Tag;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

/**
 * Компонент виджета облака тегов.
 */
class TagsCloud extends Component
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
    public $template = 'components.widgets.tags-cloud';

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

    /**
     * Получить коллекцию тегов.
     * @return Collection
     */
    public function tags(): Collection
    {
        return Tag::select([
                'tags.id',
                'tags.title',
            ])
            ->withCount('articles')
            ->whereHas('articles')
            ->orderBy('articles_count', 'desc')
            ->limit(8)
            ->get();
    }
}
