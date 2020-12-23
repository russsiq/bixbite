<?php

namespace App\Http\Controllers\Rss;

use App\Support\Facades\CacheFile;
use Illuminate\Contracts\Cache\Factory as CacheFactoryContract;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

/**
 * Абстрактный класс-контроллер генерации RSS лент.
 *
 * @NB: Need chunk to articles.
 * Возможно, что вместо `chunk` нужна постраничка,
 * но в этом случае нужно генерировать основную страницу,
 * на которой будут отображены ссылки на дочерние страницы.
 * Либо ограничиться взятием из БД определенного количества записей.
 */
abstract class BaseController
{
    /**
     * Менеджер кеша.
     *
     * @var Repository
     */
    protected $cache;

    /**
     * Шаблон ленты.
     *
     * @var string
     */
    protected $template;

    /**
     * Вероятная частота изменения информации на сайте.
     * Алгоритмов поисковых роботов не знаем,
     * поэтому несколько сокращаем периоды для месяца и года.
     *
     * @var array
     */
    protected $changefreq = [
        // Постоянно изменяется. Не использовать кеш.
        'always' => null,

        // Каждый час.
        'hourly' => 60,

        // Ежедневно.
        'daily' => 60 * 24,

        // Еженедельно.
        'weekly' => 60 * 24 * 7,

        // Бухгалтерский месяц.
        'monthly' => 60 * 24 * 7 * 21,

        // Венерианский год.
        'yearly' => 60 * 24 * 224,

        // Никогда не изменяется. кешировать навсегда.
        'never' => 0,

    ];

    /**
     * Массив дат с информацией о последних измененных данных сайта.
     *
     * @var array
     */
    protected static $lastmods = [];

    /**
     * Создать новый экземпляр генератора ленты.
     *
     * @param  CacheFactoryContract  $cache
     */
    public function __construct(
        CacheFactoryContract $cache
    ) {
        $this->cache = $cache->store('file');
    }

    /**
     * По умолчанию генераторы RSS лент
     * являются контроллерами одиночного действия.
     *
     * @return Response
     */
    public function __invoke(): Response
    {
        return response($this->content(), 200)
            ->withHeaders([
                'Content-Type' => 'text/xml',

            ]);
    }

    /**
     * Получить скомпилированное XML-строковое содержимое ленты.
     *
     * @return string
     */
    protected function content(): string
    {
        $cacheTime = $this->cacheTime();

        if (is_null($cacheTime)) {
            return $this->view();
        }

        if ($this->isExpired()) {
            $this->cache->forget($this->cacheKey());
        }

        $cacheTime = $cacheTime * 60;

        if (0 === $cacheTime) {
            return $this->cache->rememberForever($this->cacheKey(), function () {
                return $this->prepareForCache($this->view());
            });
        }

        return $this->cache->remember($this->cacheKey(), $cacheTime, function () {
            return $this->prepareForCache($this->view());
        });
    }

    /**
     * Получить ключ кеша ленты.
     *
     * @return string
     */
    abstract protected function cacheKey(): string;

    /**
     * Получить время кеширования ленты.
     * По умолчанию возвращаем ежедневное кеширование ленты.
     *
     * @return int|null
     */
    protected function cacheTime(): ?int
    {
        return $this->changefreq['daily'];
    }

    /**
     * Проверить просроченность кеширования ленты.
     *
     * @return bool
     */
    protected function isExpired(): bool
    {
        return $this->lastmod() > $this->created();
    }

    /**
     * Получить дату последнего изменения информации,
     * которая будет представлена в текущей ленте.
     *
     * @return Carbon|null
     */
    abstract protected function lastmod(): ?Carbon;

    /**
     * Получить дату создания текущей ленты.
     *
     * @return Carbon|null
     */
    protected function created(): ?Carbon
    {
        return CacheFile::created($this->cacheKey());
    }

    /**
     * Получить компилируемое представление ленты.
     *
     * @return Renderable
     */
    abstract protected function view(): Renderable;

    /**
     * Подготовить XML-строковое представление к кешированию.
     *
     * @return string
     */
    protected function prepareForCache(string $view): string
    {
        return trim(preg_replace('/(\s|\r|\n)+</', '<', $view));
    }
}
