<?php

namespace App\Http\Controllers;

use App\Http\Requests\Front\UserRequest;
use App\Models\User;
use App\Models\XField;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Контроллер, управляющий Пользователями сайта.
 */
class UsersController extends SiteController
{
    /**
     * Настройки модели Комментарий.
     *
     * @var object
     */
    protected $settings;

    /**
     * Макет шаблонов контроллера.
     *
     * @var string
     */
    protected $template = 'users';

    /**
     * Создать экземпляр контроллера.
     */
    public function __construct()
    {
        $this->settings = (object) setting(User::TABLE);
    }

    /**
     * [index description].
     *
     * @return Renderable
     */
    public function index()
    {
        $users = User::latest()
            ->paginate(
                $this->settings->paginate ?? 15
            );

        pageinfo([
            'title' => $this->settings->meta_title ?? trans('users.title'),
            'description' => $this->settings->meta_description ?? trans('users.title'),
            'keywords' => $this->settings->meta_keywords ?? trans('users.title'),
            'robots' => 'noindex, follow',
            'url' => route('users.index'),
            'is_index' => true,

        ]);

        return $this->makeResponse('index', compact('users'));
    }
}
