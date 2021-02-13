<?php

namespace App\View\Components;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\View\Component;
use Symfony\Component\HttpFoundation\InputBag;

/**
 * Компонент панели, оповещающей пользователя об использовании куков на сайте.
 */
class ConsentCookie extends Component
{
    const COOKIE_KEY = 'consent_cookie';

    const COOKIE_ACCEPT = 'accept';

    protected InputBag $cookies;

    /**
     * Create a new component instance.
     *
     * @param  Request  $request
     */
    public function __construct(Request $request)
    {
        $this->cookies = $request->cookies;
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
        return ! $this->isAccepted();
    }

    public function cookieKey(): string
    {
        return self::COOKIE_KEY;
    }

    public function cookieAccept(): string
    {
        return self::COOKIE_ACCEPT;
    }

    public function isAccepted(): bool
    {
        return $this->cookieAccept() === $this->cookies->get(
            $this->cookieKey()
        );
    }
}
