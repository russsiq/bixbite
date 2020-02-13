<?php

namespace BBCMS\Services\Assistant;

use Installer;

use Russsiq\Assistant\Services\Abstracts\AbstractBeforeInstalled;

use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Foundation\Application;
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
     * Экземпляр приложения.
     *
     * @var Application
     */
    protected $app;

    /**
     * Создать новый экземпляр класса.
     *
     * @param  Application  $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Обработка входящего запроса.
     *
     * @param  Request $request
     *
     * @return RedirectResponse
     */
    public function handle(Request $request): RedirectResponse
    {
        // Всегда валидируем входящие данные.
        $data = $this->validator($request->all())->validate();

        // Копирование темы сайта.
        $this->copyDirectoryTheme($request->all());

        // Регистрация собственника сайта.
        $this->registerOwner($data);

        // Перенаправляем на страницу входа на сайт.
        return redirect()->route('login');
    }

    /**
     * Копирование темы сайта.
     *
     * @param  array  $data Входящие данные
     *
     * @return void
     */
    protected function copyDirectoryTheme(array $data)
    {
        Installer::when(empty($data['original_theme']),
            function ($installer) use ($data) {
                $path = resource_path('themes'.DS);
                $theme = Str::slug($data['APP_NAME']);

                $installer->copyDirectory($path.$data['APP_THEME'], $path.$theme);
            }
        );
    }

    /**
     * Регистрация собственника сайта.
     *
     * @param  array  $data Входящие данные
     *
     * @return void
     */
    protected function registerOwner(array $data)
    {
        \DB::table('users')->insert([
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
     *
     * @param  array  $data
     *
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

            'original_theme' => [
                'sometimes',
                'boolean',

            ],

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
        $auth = is_array($trans = trans('auth')) ? $trans : [];
        $install = is_array($trans = trans('assistant::install.forms.attributes')) ? $trans : [];

        return collect(array_merge($auth, $install))
            ->only(
                array_keys($this->rules())
            )
            ->toArray();
    }
}
