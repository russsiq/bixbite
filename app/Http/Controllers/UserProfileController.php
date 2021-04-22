<?php

namespace App\Http\Controllers;

use App\Http\Requests\Front\UserRequest;
use App\Models\User;
use Illuminate\Contracts\Container\Container as ContainerContract;
use Illuminate\Http\Request;

class UserProfileController extends SiteController
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
     * Show the user profile screen.
     *
     * @param  \Illuminate\Http\ContainerContract  $container
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function show(ContainerContract $container, Request $request)
    {
        return $container->makeWith(UsersController::class)
            ->profile(
                $request->user()->id
            );
    }

    /**
     * [edit description].
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function edit(Request $request)
    {
        $user = $request->user();

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
     * @return RedirectResponse
     */
    public function update(UserRequest $request)
    {
        $user = $request->user();

        $user->update($request->all());

        return redirect()
            ->route('profile', $user)
            ->withStatus(
                trans('users.msg.profile_updated')
            );
    }
}
