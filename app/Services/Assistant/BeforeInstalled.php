<?php

namespace BBCMS\Services\Assistant;

// Зарегистрированные фасады приложения.
use DB;
use Installer;

// Сторонние зависимости.
use Russsiq\Assistant\Contracts\InstallerContract;
use Russsiq\Assistant\Services\Abstracts\AbstractBeforeInstalled;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Config\Repository as ConfigRepositoryContract;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
     * @var Container
     */
    protected $container;

    /**
     * Экземпляр репозитория конфигураций.
     * @var ConfigRepositoryContract
     */
    protected $config;

    /**
     * Создать новый экземпляр класса.
     * @param  Container  $container
     * @return void
     */
    public function __construct(
        Container $container
    ) {
        $this->container = $container;
        $this->config = $container->make('config');
    }

    /**
     * Обработка входящего запроса.
     * @param  Request $request
     * @return RedirectResponse
     */
    public function handle(Request $request): RedirectResponse
    {
        // Всегда валидируем входящие данные.
        $data = $this->validator($request->all())->validate();

        // Применить тему сайта.
        $this->applyTheme($request);

        // Регистрация собственника сайта.
        $this->registerOwner($data);

        // Перенаправляем на страницу входа на сайт.
        return redirect()->route('login');
    }

    /**
     * Применить тему сайта.
     * @param  Request  $request
     * @return void
     */
    protected function applyTheme(Request $request)
    {
        // Если не была отмечена опция использования оригинальной темы,
        // то копируем выбранную тему сайта под новым именем.
        Installer::when(empty($request->original_theme),
            function (InstallerContract $installer) use ($request) {
                $theme = Str::slug(
                    $this->config->get('app.name', Str::random(8))
                );

                // Копируем выбранную тему сайта под новым названием.
                $installer->copyDirectory(
                    resource_path('themes/'.$request->APP_THEME),
                    resource_path('themes/'.$theme)
                );

                // Меняем название темы сайта для
                // последующей записи в файл окружения.
                $request->merge([
                    'APP_THEME' => $theme,

                ]);
            }
        );

        // Создание ссылки на директорию с темой.
        Installer::createSymbolicLink(
            resource_path('themes/'.$request->APP_THEME.'/public'),
            public_path('themes/'.$request->APP_THEME)
        );
    }

    /**
     * Регистрация собственника сайта.
     * @param  array  $data Входящие данные
     * @return void
     */
    protected function registerOwner(array $data)
    {
        DB::table('users')->insert([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => 'owner',
            'last_ip' => $this->app->request->ip(),
            'created_at' => date('Y-m-d H:i:s'),
            'email_verified_at' => date('Y-m-d H:i:s'),

        ]);
    }

    /**
     * Получить валидатор для проверки входящих данных запроса.
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
     * @return array
     */
    protected function rules(): array
    {
        return [
            // Псевдоним.
            'name' => [
                'required',
                'string',
                'max:255',

            ],

            // Почта.
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',

            ],

            // Пароль.
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',

            ],

            // Маркер использования оригинальной темы.
            'original_theme' => [
                'sometimes',
                'boolean',

            ],

            // Название темы сайта.
            'APP_THEME' => [
                'required',
                'string',

            ],

        ];
    }

    /**
     * Получить сообщения об ошибках валидации.
     * @return array
     */
    protected function messages(): array
    {
        return [

        ];
    }

    /**
     * Получить названия атрибутов об ошибках валидации.
     * @return array
     */
    protected function attributes(): array
    {
        $auth = is_array($trans = trans('auth')) ? $trans : [];
        $install = is_array($trans = trans('assistant::install.forms.attributes')) ? $trans : [];

        return collect(array_merge($auth, $install))
            ->only(
                array_keys($this->rules())
            )
            ->toArray();
    }
}
