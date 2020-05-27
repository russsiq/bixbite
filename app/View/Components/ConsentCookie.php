<?php

namespace App\View\Components;

// Сторонние зависимости.
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\View\Component;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Компонент панели, оповещающей пользователя об использовании куков на сайте.
 */
class ConsentCookie extends Component
{
    /**
     * Имя куки согласия использовать куки на сайте.
     * @const string
     */
    const ACCEPTED_NAME = 'consent_cookie';

    /**
     * Значение куки согласия использовать куки на сайте.
     * Используется точное совпадение.
     * @const string
     */
    const ACCEPTED_VALUE = 'accept';

    /**
     * Шаблон компонента.
     * @var string
     */
    protected $template = 'components.consent-cookie';

    /**
     * Репозиторий с куками.
     * @var ParameterBag
     */
    protected $cookies;

    /**
     * Создать экземпляр компонента.
     * @param  Request  $request
     */
    public function __construct(
        Request $request
    ) {
        $this->cookies = $request->cookies;
    }

    /**
     * Получить шаблон / содержимое, представляющее компонент.
     * @return Renderable
     */
    public function render(): Renderable
    {
        return view($this->template);
    }

    /**
     * Проверить, что пользователь уже дал согласие на обработку cookies.
     * @return bool
     */
    protected function isAlreadyAccepted(): bool
    {
        return self::ACCEPTED_VALUE === $this->cookies->get(self::ACCEPTED_NAME);
    }

    /**
     * Переопределяемый родительский метод,
     * указывающий, что компонент должен быть скомпилирован.
     * @return bool
     */
    public function shouldRender(): bool
    {
        return ! $this->isAlreadyAccepted();
    }
}
