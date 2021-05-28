<?php

namespace App\Http\Middleware;

use App\Models\Contracts\MustBeOnlineContract;
use App\Models\User;
use Closure;

/**
 * Writing to the cache information that the user is online.
 */
class MustBeOnlineMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->updateUserInformation($request->user());

        return $next($request);
    }

    /**
     * Update information about the current user.
     *
     * @param  User|null  $user
     * @return void
     */
    protected function updateUserInformation(?User $user): void
    {
        if ($user instanceof MustBeOnlineContract) {
            $user->fixActivity();
        }
    }
}
