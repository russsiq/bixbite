<?php

namespace App\View\Components\Widgets;

// Сторонние зависимости.
use App\Models\Article;
use App\Support\WidgetAbstract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Компонент виджета `Соседние записи`.
 */
class ArticlesNeighboring extends WidgetAbstract
{
    /**
     * Входящие параметры виджета.
     * @var array
     */
    protected $parameters = [
        // Заголовок виджета.
        'title' => 'Neighboring Articles',

        // Активность виджета.
        'is_active' => true,

        // Шаблон виджета.
        'template' => 'components.widgets.articles-neighboring',

        // Время кэширования виджета в секундах.
        // По умолчанию раз в неделю.
        'cache_time' => 60 * 60 * 24 * 7,

        // Текущая запись.
        'current_article' => null,

        // Учитывать категории текущей записи.
        'is_same_categories' => false,

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

            // Текущая запись.
            'current_article' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (! $value instanceof Article) {
                        $fail($attribute.' is invalid.');
                    }
                },

            ],

            // Учитывать категории текущей записи.
            'is_same_categories' => [
                'sometimes',
                'required',
                'boolean',

            ],

        ];
    }

    /**
     * Получить предыдущую запись из хранилища.
     * @return Article|null
     */
    public function previous(): ?Article
    {
        if (empty($this->cacheTime())) {
            return $this->resolvePrevious();
        }

        return $this->cache->remember(
            $this->cacheKey([
                'previous' => true,

            ]),

            $this->cacheTime(),

            function () {
                return $this->resolvePrevious();
            }
        );
    }

    /**
     * Извлечь предыдущую запись из хранилища.
     * @return Article|null
     */
    protected function resolvePrevious(): ?Article
    {
        $article = $this->parameter('current_article');

        return Article::select([
                'articles.id',
                'articles.title',
                'articles.slug',

            ])
            ->published()
            ->when($this->parameter('is_same_categories'), function (Builder $builder) use ($article) {
                $builder->whereHas('categories', function (Builder $builder) use ($article) {
                    $builder->whereIn('categories.id', $article->categories->pluck('id')->toArray());
                });
            })
            ->where('articles.id', '<', $article->id)
            ->orderBy('articles.id', 'desc')
            ->limit(1)
            ->get()
            ->first();
    }

    /**
     * Получить следющую запись из хранилища.
     * @return Article|null
     */
    public function next(): ?Article
    {
        if (empty($this->cacheTime())) {
            return $this->resolveNext();
        }

        return $this->cache->remember(
            $this->cacheKey([
                'next' => true,

            ]),

            $this->cacheTime(),

            function () {
                return $this->resolveNext();
            }
        );
    }

    /**
     * Извлечь следющую запись из хранилища.
     * @return Article|null
     */
    protected function resolveNext(): ?Article
    {
        $article = $this->parameter('current_article');

        return Article::select([
                'articles.id',
                'articles.title',
                'articles.slug',

            ])
            ->published()
            ->when($this->parameter('is_same_categories'), function (Builder $builder) use ($article) {
                $builder->whereHas('categories', function (Builder $builder) use ($article) {
                    $builder->whereIn('categories.id', $article->categories->pluck('id')->toArray());
                });
            })
            ->where('articles.id', '>', $article->id)
            ->orderBy('articles.id', 'asc')
            ->limit(1)
            ->get()
            ->last();
    }
}
