<?php

namespace BBCMS\Http\Controllers;

use BBCMS\Models\User;
use BBCMS\Models\XField;
use BBCMS\Http\Requests\Admin\UserRequest;

class UsersController extends SiteController
{
    protected $model;
    protected $template = 'users';

    public function __construct(User $model)
    {
        parent::__construct();
        $this->model = $model;
    }

    public function index() {
        $users = $this->model->latest()
            ->paginate(setting('users.paginate', 8));

        pageinfo([
            'title' => setting('users.meta_title', __('users.title')),
            'description' => setting('users.meta_description', __('users.title')),
            'keywords' => setting('users.meta_keywords', __('users.title')),
            'robots' => 'none',
            'url' => route('users.index'),
            'is_index' => true,
        ]);

        return $this->renderOutput('index', compact('users'));
    }

    public function edit(User $user)
    {
        $x_fields = XField::fields($user->getTable());

        pageinfo([
            'title' => __('users.edit_page'),
            'robots' => 'none',
        ]);

        return $this->renderOutput('edit', compact('user', 'x_fields'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \BBCMS\Http\Requests\Admin\UserRequest  $request
     * @param  \BBCMS\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $user->manageFromRequest($request);

        return redirect()->route('profile', $user)->withStatus(__('common.msg.complete'));
    }

    public function destroy(User $user)
    {
        //
    }

    public function profile(int $id)
    {
        $user = $this->model
            ->where('id', (int) $id)
            ->withCount([
                'articles', 'comments', 'posts', 'follows'
            ])
            ->firstOrFail();

        $user->posts = $user->posts_count
            ? $user->posts()->with([
                'user:users.id,users.name,users.email,users.avatar'
            ])->latest()->get()->treated(true) : [];

        $x_fields = XField::fields($user->getTable());

        pageinfo([
            'title' => $user->name,
            'description' => $user->info,
            'robots' => $user->robots ?? 'all',
            'url' => $user->profile,
            'section' => [
                'title' => setting('users.meta_title', __('users.title')),
            ],
            'is_profile' => true,
            'user' => $user,
        ]);

        return $this->renderOutput('profile', compact('user', 'x_fields'));
    }

    public function follow(User $user)
    {
        auth()->user()->follow($user);

        return redirect()->back()->withStatus(
                __('users.msg.followed')
            );
    }

    public function unfollow(User $user)
    {
        auth()->user()->unfollow($user);

        return redirect()->back()->withStatus(
                __('users.msg.unfollowed')
            );
    }
}
