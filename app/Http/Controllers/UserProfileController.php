<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Container\Container as ContainerContract;

class UserProfileController extends Controller
{
    /**
     * Show the user profile screen.
     *
     * @param  \Illuminate\Http\ContainerContract  $container
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function show(ContainerContract $container, Request $request)
    {
        // return view('profile.show', [
        //     'user' => $request->user(),
        // ]);

        return $container->makeWith(UsersController::class)
            ->profile(
                $request->user()->id
            );
    }
}
