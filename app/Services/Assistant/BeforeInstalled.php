<?php

namespace App\Services\Assistant;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Config\Repository as ConfigRepositoryContract;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Russsiq\Assistant\Services\Abstracts\AbstractBeforeInstalled;

/**
 * Класс, который выполняется на финальной стадии,
 * перед тем как приложение будет отмечено как "установленное".
 *
 * Позволяет пользователю пакета определить свою логику валидации данных,
 * которые будут внесены в файл переменных окружения,
 * а также логику регистрации собственника сайта.
 */
class BeforeInstalled extends AbstractBeforeInstalled
{
    /**
     * Экземпляр контейнера приложения.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * Экземпляр репозитория конфигураций.
     *
     * @var ConfigRepositoryContract
     */
    protected $config;

    /**
     * Создать новый экземпляр класса.
     *
     * @param  Container  $container
     */
    public function __construct(
        Container $container
    ) {
        $this->container = $container;
        $this->config = $container->make('config');
    }

    /**
     * Обработка входящего запроса.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function handle(Request $request): RedirectResponse
    {
        // Всегда валидируем входящие данные.
        $data = $this->validator(
            $request->all()
        )->validate();

        // Регистрация собственника сайта.
        $this->registerOwner($request);

        // Перенаправляем на страницу входа на сайт.
        return redirect()->route('login');
    }

    /**
     * Регистрация собственника сайта.
     *
     * @param  Request  $request
     * @return void
     */
    protected function registerOwner(Request $request): void
    {
        $request->merge(
            [
                // Соглашаемся с условиями использования.
                'terms' => true,
            ]
        );

        // Регистрируем собственника сайта.
        $user = $this->container->make(CreateNewUser::class)
            ->create($request->all());

        // Подтверждаем почту.
        if ($user instanceof MustVerifyEmail) {
            $user->markEmailAsVerified();
        }
    }

    /**
     * Получить валидатор для проверки входящих данных запроса.
     *
     * @param  array  $data
     * @return ValidatorContract
     */
    protected function validator(array $data): ValidatorContract
    {
        return validator(
            $data,
            $this->rules(),
            $this->messages(),
            $this->attributes()
        );
    }

    /**
     * Получить правила валидации,
     * применяемые к входящим данным запроса.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [

        ];
    }

    /**
     * Получить сообщения об ошибках валидации.
     *
     * @return array
     */
    protected function messages(): array
    {
        return [

        ];
    }

    /**
     * Получить названия атрибутов об ошибках валидации.
     *
     * @return array
     */
    protected function attributes(): array
    {
        return [

        ];
    }
}
