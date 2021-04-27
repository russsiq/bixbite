<?php

namespace App\Http\Controllers;

use App\Contracts\Actions\User\UpdatesUserProfileInformation;
use App\Http\Requests\Front\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class UserProfileController extends Controller
{
    /** @var object */
    protected $settings;

    public function __construct()
    {
        $this->settings = (object) setting(User::TABLE);
    }

    /**
     * Show the user profile screen.
     *
     * @param  Request  $request
     * @param  int  $user_id
     * @return Renderable
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

        return view('profile.show', compact('user', 'x_fields'));
    }

    /**
     * Show the user profile screen.
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

        return view('profile.edit', compact('user', 'x_fields'));
    }

    /**
     * Update the user's profile information.
     *
     * @param  Request  $request
     * @param  UpdatesUserProfileInformation  $updater
     * @return RedirectResponse
     */
    public function update(
        Request $request,
        UpdatesUserProfileInformation $updater
    ): RedirectResponse {
        $updater->update($request->user(), $request->all());

        return back()->withStatus(
            'Profile updated successfully.'
        );
    }
}
