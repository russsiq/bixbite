<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\XField;
use App\Http\Requests\Front\UserRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

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
     * [edit description].
     *
     * @param  User  $user
     * @return Renderable
     */
    public function edit(User $user)
    {
        $x_fields = $user->x_fields;

        pageinfo([
            'title' => trans('users.edit_page'),
            'robots' => 'noindex, follow',

        ]);

        return $this->makeResponse('edit', compact('user', 'x_fields'));
    }

    /**
     * [update description].
     *
     * @param  UserRequest  $request
     * @param  User  $user
     * @return RedirectResponse
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update($request->all());

        return redirect()
            ->route('profile', $user)
            ->withStatus(
                trans('users.msg.profile_updated')
            );
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

    /**
     * Добавить пользователя в закладки.
     *
     * @param  User  $user
     * @return RedirectResponse
     */
    public function follow(User $user)
    {
        auth()->user()->follow($user);

        return redirect()->back()
            ->withStatus(trans('users.msg.followed'));
    }

    /**
     * Убрать пользователя из закладок.
     *
     * @param  User  $user
     * @return RedirectResponse
     */
    public function unfollow(User $user)
    {
        auth()->user()->unfollow($user);

        return redirect()->back()
            ->withStatus(trans('users.msg.unfollowed'));
    }
}
