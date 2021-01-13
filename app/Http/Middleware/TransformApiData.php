<?php

namespace App\Http\Middleware;

use App\Exceptions\UnsupportedMiddlewareForRouteException;
use App\Http\Middleware\Transformers\Api\V1\ArticlesTransformer;
use App\Support\Contracts\ResourceRequestTransformer;
use Closure;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use ReflectionClass;

/**
 * Преобразование данных, полученных из API-запросов
 * на создание и сохранение сущностей.
 */
class TransformApiData
{
    /**
     * Карта преобразователей данных для ресурсов.
     *
     * @const array
     */
    const AVAILABLE_TRANSFORMERS = [
        'articles' => ArticlesTransformer::class,

    ];

    /**
     * Разрешенные действия для ресурсов маршрута.
     *
     * @const string[]
     */
    const ALLOWED_ACTIONS = [
        'store',
        'update',
        'massUpdate',

    ];

    /**
     * Экземпляр Контейнера служб.
     *
     * @var Container
     */
    protected $container;

    /**
     * Имя текущего маршрута.
     *
     * @var string
     */
    protected $currentRouteName;

    /**
     * Имя текущей группы маршрута.
     *
     * @var string
     */
    protected $group;

    /**
     * Имя текущего ресурса маршрута.
     *
     * @var string
     */
    protected $resource;

    /**
     * Имя текущего действия для ресурса маршрута.
     *
     * @var string
     */
    protected $action;

    /**
     * Пространство имен для преобразователей данных.
     * `В настоящее время не используется`.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Middleware\Transformers\Api\V1';

    /**
     * Создать новый экземпляр Посредника.
     *
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->detectSegments(
            $this->currentRouteName = Route::currentRouteName()
        );
    }

    /**
     * Получить Имя текущего маршрута.
     *
     * @return string
     */
    public function currentRouteName(): string
    {
        return $this->currentRouteName;
    }

    /**
     * Получить Имя текущей группы маршрута.
     *
     * @return string
     */
    public function group(): string
    {
        return $this->group;
    }

    /**
     * Получить Имя текущего ресурса маршрута.
     *
     * @return string
     */
    public function resource(): string
    {
        return $this->resource;
    }

    /**
     * Получить Имя текущего действия для ресурса маршрута.
     *
     * @return string
     */
    public function action(): string
    {
        return $this->action;
    }

    /**
     * Обработка входящего запроса.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->hasTransformerForCurrentRoute()) {
            $request->merge(
                $this->createTransformer()->{$this->action()}()
            );
        }

        return $next($request);
    }

    /**
     * Имеется ли Преобразователь для текущего маршрута.
     *
     * @return bool
     */
    public function hasTransformerForCurrentRoute(): bool
    {
        if ($this->isAvailableTransformer() && $this->isAllowedAction()) {
            $reflection = new ReflectionClass($this->getTransformerClassName());

            return $reflection->hasMethod($this->action());
        }

        return false;
    }

    /**
     * Создать новый экземпляр Преобразователя входящих данных.
     *
     * @return ResourceRequestTransformer
     */
    protected function createTransformer(): ResourceRequestTransformer
    {
        return $this->container->make($this->getTransformerClassName(), [
            $this->container->make('config'),
        ]);
    }

    /**
     * Определить сегменты для текущего маршрута.
     *
     * @param  string  $name
     * @return void
     */
    protected function detectSegments(string $name): void
    {
        [$this->group, $this->resource, $this->action] = array_pad(explode('.', $name, 3), 3, '');

        $this->ensureIsApiGroupRoute($this->group);
    }

    /**
     * Убедиться, что группа маршрутов принадлежит к группе `api`.
     *
     * @param  string  $group
     * @return void
     *
     * @throws UnsupportedMiddlewareForRouteException
     */
    protected function ensureIsApiGroupRoute(string $group): void
    {
        if ('api' !== $group) {
            throw UnsupportedMiddlewareForRouteException::make(
                get_class($this),
                $group
            );
        }
    }

    /**
     * Undocumented function.
     *
     * @return boolean
     */
    protected function isAvailableTransformer(): bool
    {
        return array_key_exists($this->resource(), self::AVAILABLE_TRANSFORMERS);
    }

    /**
     * Undocumented function.
     *
     * @return boolean
     */
    protected function isAllowedAction(): bool
    {
        return in_array($this->action(), self::ALLOWED_ACTIONS);
    }

    /**
     * Undocumented function.
     *
     * @return string
     */
    protected function getTransformerClassName(): string
    {
        return self::AVAILABLE_TRANSFORMERS[$this->resource()];
    }
}
