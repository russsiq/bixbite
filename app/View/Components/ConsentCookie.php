<?php

namespace App\View\Components;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\View\Component;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Компонент панели, оповещающей пользователя об использовании куков на сайте.
 */
class ConsentCookie extends Component
{
    const COOKIE_NAME = 'consent_cookie';

    const COOKIE_VALUE = 'accept';

    protected ParameterBag $cookies;

    /**
     * Create a new component instance.
     *
     * @param  Request  $request
     */
    public function __construct(Request $request)
    {
        $this->cookies = $request->cookies;
    }

    public function cookieName(): string
    {
        return self::COOKIE_NAME;
    }

    public function cookieValue(): string
    {
        return self::COOKIE_VALUE;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Renderable
     */
    public function render(): Renderable
    {
        return view('components.consent-cookie');
    }

    /**
     * Determine if the component should be rendered.
     *
     * @return bool
     */
    public function shouldRender(): bool
    {
        return self::COOKIE_VALUE !== $this->cookies->get(self::COOKIE_NAME);
    }
}
