<?php

namespace App\View\Components\Widgets;

// Сторонние зависимости.
use App\Models\Comment;
use App\Support\WidgetAbstract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Компонент виджета `Обсуждения`.
 */
class CommentsLatest extends WidgetAbstract
{
    /**
     * Входящие параметры виджета.
     * @var array
     */
    protected $parameters = [
        // Заголовок виджета.
        'title' => 'Latest Comments',

        // Активность виджета.
        'is_active' => true,

        // Шаблон виджета.
        'template' => 'components.widgets.comments-latest',

        // Время кэширования виджета в секундах.
        // По умолчанию раз в 15 минут.
        'cache_time' => 60 * 15,

        // Количество отображаемых месяцев.
        'limit' => 8,

        // Длина комментария.
        'content_length' => 150,

        // Извлекать комментарии только с указанным отношением.
        'relation' => 'articles',

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

            // Длина комментария.
            'content_length' => [
                'sometimes',
                'required',
                'integer',

            ],

            // Извлекать комментарии только с указанным отношением.
            'relation' => [
                'sometimes',
                'required',
                'string',
                'in:articles,profiles',

            ],

        ];
    }

    /**
     * Получить коллекцию комментариев.
     * @return Collection
     */
    public function comments(): Collection
    {
        if (empty($this->cacheTime())) {
            return $this->resolveComments();
        }

        return $this->cache->remember(
            $this->cacheKey(),
            $this->cacheTime(),
            function () {
                return $this->resolveComments();
            }
        );
    }

    /**
     * Извлечь коллекцию комментариев из хранилища.
     * @return Collection
     */
    protected function resolveComments(): Collection
    {
        return Comment::with([
                'user:users.id,users.name,users.email,users.avatar',
                'article:articles.id,articles.title,articles.slug,articles.state',

            ])
            ->where('commentable_type', $this->parameter('relation'))
            ->where('is_approved', true)
            ->latest()
            ->limit(8)
            ->get()
            ->treated(false);
    }
}
