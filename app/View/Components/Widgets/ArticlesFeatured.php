<?php

namespace App\View\Components\Widgets;

// Сторонние зависимости.
use App\Models\Article;
use App\Support\WidgetAbstract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Компонент виджета `Рекомендуемые записи`.
 * В данный момент отображаем только опубликованные.
 */
class ArticlesFeatured extends WidgetAbstract
{
    /**
     * Входящие параметры виджета.
     * @var array
     */
    protected $parameters = [
        // Заголовок виджета.
        'title' => 'Featured Articles',

        // Активность виджета.
        'is_active' => true,

        // Шаблон виджета.
        'template' => 'components.widgets.articles-featured',

        // Время кэширования виджета в секундах.
        // По умолчанию раз в сутки.
        'cache_time' => 60 * 60 * 24,

        // Количество отображаемых записей.
        'limit' => 8,

        // Количество пропускаемых записей.
        'skip' => 0,

        // Сортировка записей.
        'order_by' => 'views',

        // Порядок сортировки.
        'direction' => 'desc',

        // Состояние публикации.
        'state' => 1,

        // Размещение на главной странице.
        'on_mainpage' => true,

        // За последние дни.
        'sub_days' => 0,

        // Записи только с указанными идентификаторами.
        'ids' => [],

        // Записи только указанных авторов.
        'user_ids' => [],

        // Записи только из указанных категорий.
        'categories' => [],

        // Записи только с указанными тегами.
        'tags' => [],

        // Записи, содержащие только указанные доп. поля.
        'x_fields' => [],

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

            // Количество отображаемых записей.
            'limit' => [
                'sometimes',
                'required',
                'integer',

            ],

            // Количество пропускаемых записей.
            'skip' => [
                'sometimes',
                'required',
                'integer',

            ],

            // Сортировка записей.
            'order_by' => [
                'sometimes',
                'required',
                'string',
                'in:id,title,created_at,updated_at,views,comments_count,votes,rating',

            ],

            // Порядок сортировки.
            'direction' => [
                'sometimes',
                'required',
                'string',
                'in:desc,asc',

            ],

            // Состояние публикации.
            'state' => [
                'sometimes',
                'required',
                'integer',
                'in:0,1,2',

            ],

            // Размещение на главной странице.
            'on_mainpage' => [
                'sometimes',
                'required',
                'boolean',

            ],

            // За последние дни.
            'sub_days' => [
                'sometimes',
                'integer',

            ],

            // Записи только с указанными идентификаторами.
            'ids' => [
                'sometimes',
                'required',
                'array',

            ],

            'ids.*' => [
                'required',
                'integer',

            ],

            // Записи только указанных авторов.
            'user_ids' => [
                'sometimes',
                'required',
                'array',

            ],

            'user_ids.*' => [
                'required',
                'integer',

            ],

            // Записи только из указанных категорий.
            'categories' => [
                'sometimes',
                'required',
                'array',

            ],

            'categories.*' => [
                'required',
                'integer',

            ],

            // Записи только с указанными тегами.
            'tags' => [
                'sometimes',
                'required',
                'array',

            ],

            'tags.*' => [
                'required',
                'string',

            ],

            // Записи, содержащие только указанные доп. поля.
            'x_fields' => [
                'sometimes',
                'required',
                'array',

            ],

            'x_fields.*' => [
                'required',
                'array',

            ],

            'x_fields.*.*' => [
                'required',
                'string',

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
        return Article::shortArticle()
            ->includeXFieldsNames()
            ->published()
            // ->where('articles.state', $this->parameter('state'))
            ->visibleOnMainpage($this->parameter('on_mainpage'))
            ->when($this->parameter('sub_days'), function (Builder $builder, int $subDays) {
                $this->setParameter('sub_days', new \DateTime("-{$subDays} day"));

                $builder->where(function (Builder $builder) {
                    $builder->where('created_at', '>=', $this->parameter('sub_days'))
                        ->orWhere('updated_at', '>=', $this->parameter('sub_days'));
                });
            })
            ->when($this->parameter('ids'), function (Builder $builder, array $ids) {
                $builder->whereIn('id', $ids);
            })
            ->when($this->parameter('user_ids'), function (Builder $builder, array $user_ids) {
                $builder->whereIn('user_id', $user_ids);
            })
            ->when($this->parameter('categories'), function (Builder $builder, array $categories) {
                $builder->whereHas('categories', function (Builder $builder) {
                    $builder->whereIn('categories.id', $this->parameter('categories'));
                });
            })
            ->when($this->parameter('tags'), function (Builder $builder, array $tags) {
                $builder->whereHas('tags', function (Builder $builder) {
                    $builder->whereIn('tags.title', $this->parameter('tags'));
                });
            })
            ->when($this->parameter('x_fields'), function (Builder $builder, array $x_fields) {
                // $builder->whereColumn($x_fields);

                foreach ($x_fields as $where) {
                    $builder->where(...$where);
                }
            })
            ->orderBy($this->parameter('order_by'), $this->parameter('direction'))
            ->when($this->parameter('skip'), function (Builder $builder, int $skip) {
                $builder->skip($skip);
            })
            ->limit($this->parameter('limit'))
            ->get();
    }
}
