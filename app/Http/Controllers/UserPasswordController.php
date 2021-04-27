<?php

namespace App\Http\Controllers;

use App\Contracts\Actions\User\UpdatesUserPasswords;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UserPasswordController extends Controller
{
    /**
     * Update the user's password.
     *
     * @param  Request  $request
     * @param  UpdatesUserPasswords  $updater
     * @return RedirectResponse
     */
    public function update(Request $request, UpdatesUserPasswords $updater): RedirectResponse
    {
        $updater->update($request->user(), $request->all());

        return back()->withStatus(
            'Password changed successfully.'
        );
    }
}
