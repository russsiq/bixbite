<?php

namespace App\Http\Middleware;

// Исключения.
use App\Exceptions\UnsupportedMiddlewareForRouteException;

// Базовые расширения PHP.
use Closure;
use ReflectionClass;

// Сторонние зависимости.
use App\Http\Middleware\Transformers\Api\V1\ArticlesTransformer;
use App\Support\Contracts\ResourceRequestTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Преобразование данных, полученных из API-запросов
 * на создание и сохранение сущностей.
 *
 * @TODO Рассмотреть варианты с биндингом преобразователя в контейнер,
 *       либо использования рефлексии (ReflectionClass),
 *       чтобы избежать создания ненужных классов.
 */
class TransformApiData
{
    /**
     * Карта преобразователей данных для ресурсов.
     * @const string[]
     */
    const AVAILABLE_TRANSFORMERS = [
        'articles' => ArticlesTransformer::class,

    ];

    /**
     * Разрешенные действия для ресурсов маршрута.
     * @const string[]
     */
    const ALLOWED_ACTIONS = [
        'store',
        'update',
        'massUpdate',

    ];

    /**
     * Имя текущего маршрута.
     * @var string
     */
    protected $currentRouteName;

    /**
     * Имя текущей группы маршрута.
     * @var string
     */
    protected $group;

    /**
     * Имя текущего ресурса маршрута.
     * @var string
     */
    protected $resource;

    /**
     * Имя текущего действия для ресурса маршрута.
     * @var string
     */
    protected $action;

    /**
     * Пространство имен для преобразователей данных.
     * `В настоящее время не используется`.
     * @var string
     */
    protected $namespace = 'App\Http\Middleware\Transformers\Api\V1';

    /**
     * Создать новый экземпляр Посредника.
     */
    public function __construct()
    {
        $this->detectSegments(
            $this->currentRouteName = Route::currentRouteName()
        );
    }

    /**
     * Получить Имя текущего маршрута.
     * @return string
     */
    public function currentRouteName(): string
    {
        return $this->currentRouteName;
    }

    /**
     * Получить Имя текущей группы маршрута.
     * @return string
     */
    public function group(): string
    {
        return $this->group;
    }

    /**
     * Получить Имя текущего ресурса маршрута.
     * @return string
     */
    public function resource(): string
    {
        return $this->resource;
    }

    /**
     * Получить Имя текущего действия для ресурса маршрута.
     * @return string
     */
    public function action(): string
    {
        return $this->action;
    }

    /**
     * Обработка входящего запроса.
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->hasTransformerForCurrentRoute()) {
            $transformer = $this->createTransformer($request);

            if (method_exists($transformer, $this->action())) {
                $request->merge($transformer->{$this->action()}());
            }
        }

        return $next($request);
    }

    /**
     * Имеется ли Преобразователь для текущего маршрута.
     * @return bool
     */
    public function hasTransformerForCurrentRoute(): bool
    {
        return array_key_exists($this->resource(), self::AVAILABLE_TRANSFORMERS)
            && in_array($this->action(), self::ALLOWED_ACTIONS);
    }

    /**
     * Создать новый экземпляр Преобразователя входящих данных.
     * @param  Request  $request
     * @return ResourceRequestTransformer
     */
    protected function createTransformer(Request $request): ResourceRequestTransformer
    {
        $class = self::AVAILABLE_TRANSFORMERS[$this->resource()];

        return new $class($request);
    }

    /**
     * Определить сегменты для текущего маршрута.
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
}
