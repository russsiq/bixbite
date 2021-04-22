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
     * Модель Пользователь.
     *
     * @var User
     */
    protected $model;

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
     *
     * @param  User  $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;

        $this->settings = (object) setting($model->getTable());
    }

    /**
     * [index description].
     *
     * @return Renderable
     */
    public function index()
    {
        $users = $this->model->latest()
            ->paginate($this->settings->paginate ?? 15);

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

    /**
     * Показать профиль пользователя.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function profile(int $id)
    {
        $user = $this->model
            ->where('id', $id)
            ->withCount([
                'articles',
                'comments',
                'posts',
                'follows',

            ])
            ->firstOrFail();

        $user->posts = $user->posts_count
            ? $user->posts()
                ->with([
                    'user:users.id,users.name,users.email,users.avatar',

                ])->latest()
                ->get()
                ->treated(true)
            : [];

        $x_fields = $user->x_fields;

        pageinfo([
            'title' => $user->name,
            'description' => $user->info,
            'robots' => $user->robots ?? 'all',
            'url' => $user->profile,
            'section' => [
                'title' => $this->settings->meta_title ?? trans('users.title'),
            ],
            'is_profile' => true,
            'is_own_profile' => $user->id === user('id'),
            'user' => $user,

        ]);

        return $this->makeResponse('profile', compact('user', 'x_fields'));
    }
}
