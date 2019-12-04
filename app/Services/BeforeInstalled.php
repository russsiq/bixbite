<?php

namespace BBCMS\Services;

use Russsiq\Assistant\Services\Abstracts\AbstractBeforeInstalled;

use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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

        // ... остальной код.

        // Перенаправляем на страницу регистрации.
        return redirect()->route('register');
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
        return validator($data, $this->rules());
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
            // // Пример возвращаемых данных.
            // 'SOME_VAR' => [
            //     'required',
            //     'string',
            //
            // ],

        ];
    }
}
