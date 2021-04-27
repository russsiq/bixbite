<?php

namespace App\Http\Controllers;

use App\Http\Requests\Front\UserRequest;
use App\Models\User;
use Illuminate\Contracts\Container\Container as ContainerContract;
use Illuminate\Http\Request;

class UserProfileController extends SiteController
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

    public function __construct()
    {
        $this->settings = (object) setting(User::TABLE);
    }

    /**
     * Show the user profile screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $user_id
     * @return \Illuminate\View\View
     */
    public function show(Request $request, int $user_id = null)
    {
        $user = is_null($user_id)
            ? $request->user()
            : User::where('id', $user_id)
                ->firstOrFail();

        $user->loadCount([
            'articles',
            'comments',
        ]);

        $x_fields = $user->x_fields;

        pageinfo([
            'title' => $user->name,
            'description' => $user->info,
            'robots' => 'noindex, follow',
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
            ->route('profile.show', $user)
            ->withStatus(
                trans('users.msg.profile_updated')
            );
    }
}
