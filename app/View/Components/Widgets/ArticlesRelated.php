<?php

namespace App\View\Components\Widgets;

// Сторонние зависимости.
use App\Models\Article;
use App\Support\WidgetAbstract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Компонент виджета `Похожие записи`.
 */
class ArticlesRelated extends WidgetAbstract
{
    /**
     * Входящие параметры виджета.
     * @var array
     */
    protected $parameters = [
        // Заголовок виджета.
        'title' => 'Related Articles',

        // Активность виджета.
        'is_active' => true,

        // Шаблон виджета.
        'template' => 'components.widgets.articles-related',

        // Время кэширования виджета в секундах.
        // По умолчанию раз в неделю.
        'cache_time' => 60 * 60 * 24 * 7,

        // Количество отображаемых записей.
        'limit' => 3,

        // Текущая запись.
        'current_article' => null,

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

            // Текущая запись.
            'current_article' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (! $value instanceof Article) {
                        $fail($attribute.' is invalid.');
                    }
                },

            ],

        ];
    }

    /**
     * Получить коллекцию записей из хранилища.
     * @return Collection
     */
    public function articles(): Collection
    {
        if (empty($this->cacheTime())) {
            return $this->resolveArticles();
        }

        return $this->cache->remember(
            $this->cacheKey(),
            $this->cacheTime(),
            function () {
                return $this->resolveArticles();
            }
        );
    }

    /**
     * Извлечь коллекцию записей из хранилища.
     * @return Collection
     */
    protected function resolveArticles(): Collection
    {
        $article = $this->parameter('current_article');

        return Article::shortArticle()
            ->where('articles.id', '<>', $article->id)
            ->search(teaser($article->title . ' ' . $article->content, 255, ''))
            ->limit($this->parameter('limit'))
            ->get();
    }
}
