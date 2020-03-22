<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\XField;
use App\Http\Requests\Front\UserRequest;

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
            ->paginate(setting('users.paginate', 15));

        pageinfo([
            'title' => setting('users.meta_title', __('users.title')),
            'description' => setting('users.meta_description', __('users.title')),
            'keywords' => setting('users.meta_keywords', __('users.title')),
            'robots' => 'noindex, follow',
            'url' => route('users.index'),
            'is_index' => true,
        ]);

        return $this->makeResponse('index', compact('users'));
    }

    public function edit(User $user)
    {
        $x_fields = $user->x_fields;

        pageinfo([
            'title' => __('users.edit_page'),
            'robots' => 'noindex, follow',
        ]);

        return $this->makeResponse('edit', compact('user', 'x_fields'));
    }

    public function update(UserRequest $request, User $user)
    {
        $user->update($request->all());

        return redirect()->route('profile', $user)->withStatus(
            __('users.msg.profile_updated')
        );
    }

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

        if ($user->posts_count) {
            $user->posts = $user->posts()
                ->with([
                    'user:users.id,users.name,users.email,users.avatar'
                ])->latest()
                ->get()
                ->treated(true);
        } else {
            $user->posts = [];
        }

        $x_fields = $user->x_fields;

        pageinfo([
            'title' => $user->name,
            'description' => $user->info,
            'robots' => $user->robots ?? 'all',
            'url' => $user->profile,
            'section' => [
                'title' => setting('users.meta_title', __('users.title')),
            ],
            'is_profile' => true,
            'is_own_profile' => $user->id === user('id'),
            'user' => $user,
        ]);

        return $this->makeResponse('profile', compact('user', 'x_fields'));
    }

    public function follow(User $user)
    {
        auth()->user()->follow($user);

        return redirect()->back()
            ->withStatus(__('users.msg.followed'));
    }

    public function unfollow(User $user)
    {
        auth()->user()->unfollow($user);

        return redirect()->back()
            ->withStatus(__('users.msg.unfollowed'));
    }
}
