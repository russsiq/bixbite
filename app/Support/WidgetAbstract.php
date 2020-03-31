<?php

namespace App\Support;

// Сторонние зависимости.
use Illuminate\Contracts\Cache\Factory as CacheFactoryContract;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\Validation\Factory as ValidatorFactoryContract;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\View\Component;

/**
 * Абстрактный класс виджетов.
 *
 * Создан по двум причинам:
 *  - валидация входящих параметров;
 *  - вывод ошибок валидации;
 *  - кеширование запросов к БД.
 *
 * Не забывай, что все публичные методы и свойства компонентов
 * являются переменными, доступными в шаблоне виджета.
 */
abstract class WidgetAbstract extends Component
{
    /**
     * Шаблон для вывода ошибок валидации виджета.
     * @var string
     */
    const ERROR_TEMPLATE = 'errors.widget';

    /**
     * Маршрут для очистки кеша.
     * @var string
     */
    const ROUTE_CLEAR_CACHE = 'system_care.clearcache';

    /**
     * Экземпляр валидатора входящих параметров.
     * @var Validator
     */
    protected $validator;

    /**
     * Менеджер кеша.
     * @var Repository
     */
    protected $cache;

    /**
     * Входящие параметры виджета.
     * @var array
     */
    protected $parameters = [];

    /**
     * Создать экземпляр компонента.
     * @param  CacheFactoryContract  $cache
     * @param  ValidatorFactoryContract  $validator
     * @param  array  $parameters
     */
    public function __construct(
        CacheFactoryContract $cache,
        ValidatorFactoryContract $validator,
        array $parameters = []
    ) {
        $this->cache = $this->cacheInstance($cache);
        $this->validator = $this->validatorInstance($validator, $parameters);

        $this->configure();
    }

    /**
     * Получить заголовок виджета.
     * @return string
     */
    public function title(): string
    {
        return $this->parameter('title');
    }

    /**
     * Получить ссылку на очистку кеша виджета.
     * @return string
     */
    public function clearCacheUrl(): string
    {
        return route(self::ROUTE_CLEAR_CACHE, [
            'key' => $this->cacheKey(),

        ]);
    }

    /**
     * Получить шаблон / содержимое, представляющее компонент.
     * @return Renderable|null
     */
    public function render(): ?Renderable
    {
        if (! $this->parameter('is_active')) {
            return null;
        }

        if ($this->validator->fails()) {
            return view(self::ERROR_TEMPLATE)
                ->withErrors($this->validator);
        }

        return view($this->parameter('template'));
    }

    /**
     * Получить экземпляр репозитория кеша.
     * @param  CacheFactoryContract  $factory
     * @return Repository
     */
    protected function cacheInstance(
        CacheFactoryContract $factory
    ): Repository {
        return $factory->store('file');
    }

    /**
     * Получить ключ кеша.
     * Дополнительные параметры используются в виджете `Соседние записи`.
     * @param  array  $advanced  Дополнительные параметры для кеширования ключа.
     * @return string
     */
    protected function cacheKey(array $advanced = []): string
    {
        return md5(serialize(array_merge(
            $this->parameters, [
                'widget' => get_class($this),
                // 'app_theme' => app_theme(),
                // 'app_locale' => app_locale(),
                // 'role' => $role,

            ], $advanced
        )));
    }

    /**
     * Получить время кеширования виджета в секундах.
     * @return int
     */
    protected function cacheTime(): int
    {
        return $this->parameter('cache_time');
    }

    /**
     * Получить экземпляр валидатора.
     * @param  ValidatorFactoryContract  $factory
     * @param  array  $parameters
     * @return Validator
     */
    protected function validatorInstance(
        ValidatorFactoryContract $factory,
        array $parameters = []
    ): Validator {
        return $factory->make($parameters, $this->rules());
    }

    /**
     * Получить массив правил валидации,
     * которые будут применены к входящим параметрам.
     * @return array
     */
    abstract protected function rules(): array;

    /**
     * Конфигурирование виджета входящими параметрами.
     * Задаем только те параметры, которые прошли валидацию.
     * @return void
     */
    protected function configure(): void
    {
        foreach ($this->validator->validated() as $key => $value) {
            $this->setParameter($key, $value);
        }
    }

    /**
     * Задать параметр виджета.
     * @param  string  $key
     * @param  mixed  $value
     * @return self
     */
    protected function setParameter(string $key, $value)
    {
        $this->parameters[$key] = value($value);

        return $this;
    }

    /**
     * Получить параметр виджета.
     * @param  string  $key
     * @return mixed
     */
    protected function parameter(string $key)
    {
        return $this->parameters[$key];
    }
}
