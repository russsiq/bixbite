<?php

namespace App\Http\Middleware;

// Исключения.
use InvalidArgumentException;

// Базовые расширения PHP.
use Closure;

// Сторонние зависимости.
use App\Support\Contracts\ResourceRequestTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Преобразование данных, полученных из API-запросов
 * на создание и сохранение сущностей.
 */
class TransformApiData
{
    /**
     * [$currentRouteName description]
     * @var string
     */
    protected $currentRouteName;

    /**
     * [$group description]
     * @var string
     */
    protected $group;

    /**
     * [$resource description]
     * @var string
     */
    protected $resource;

    /**
     * [$action description]
     * @var string
     */
    protected $action;

    /**
     * [$allowedActions description]
     * @var array
     */
    protected $allowedActions = [
        'store',
        'update',
        'massUpdate',

    ];

    /**
     * [$transformers description]
     * @var array
     */
    protected $transformers = [
        'articles' => \App\Http\Middleware\Transformers\Api\V1\ArticlesTransformer::class,

    ];

    /**
     * [$namespace description]
     * @var string
     */
    protected $namespace = 'App\Http\Middleware\Transformers\Api\V1';

    /**
     * [__construct description]
     */
    public function __construct()
    {
        $this->detectSegments(
            $this->currentRouteName = Route::currentRouteName()
        );
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

            if (method_exists($transformer, $this->action)) {
                $request->merge($transformer->{$this->action}());
            }
        }

        return $next($request);
    }

    /**
     * [hasTransformerForCurrentRoute description]
     * @return bool
     */
    public function hasTransformerForCurrentRoute(): bool
    {
        return array_key_exists($this->resource, $this->transformers)
            && in_array($this->action, $this->allowedActions);
    }

    /**
     * [createTransformer description]
     * @param  Request  $request
     * @return ResourceRequestTransformer
     */
    protected function createTransformer(Request $request): ResourceRequestTransformer
    {
        return new $this->transformers[$this->resource]($request);
    }

    /**
     * [detectSegments description]
     * @param  string  $name [description]
     * @return void
     */
    protected function detectSegments(string $name): void
    {
        [$this->group, $this->resource, $this->action] = explode('.', $name, 3);

        $this->ensureIsApiGroupRoute($this->group);
    }

    /**
     * [ensureIsApiGroupRoute description]
     * @param  string  $group
     * @return void
     */
    protected function ensureIsApiGroupRoute(string $group): void
    {
        if ('api' !== $this->group) {
            throw new InvalidArgumentException;
        }
    }
}
