<?php

namespace BBCMS\Http\Controllers;

use BBCMS\Models\User;
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
        $users = $this->model
            // ->withCount('comments')
            // ->with([
            //     'categories:categories.id,categories.title,categories.slug',
            //     'user:users.id,users.name',
            // ])
            ->latest()
            ->paginate(setting('users.paginate', 8));

        pageinfo([
            'title' => setting('users.meta_title', __('users.title')),
            'description' => setting('users.meta_description', __('users.title')),
            'keywords' => setting('users.meta_keywords', __('users.title')),
            'url' => route('users.index'),
            'is_index' => true,
        ]);

        return $this->renderOutput('index', compact('users'));
    }

    public function show(User $user)
    {
        $user = $user->where('id', $user->id)->withCount(['articles', 'comments'])->first();

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

        return $this->renderOutput('profile', compact('user'));
    }

    public function edit(User $user)
    {
        return $this->renderOutput('edit', compact('user'));
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

        return redirect()->route('profile', $user)->withStatus('Update!');
    }

    public function destroy(User $user)
    {
        //
    }
}
