<?php

namespace BBCMS\Policies;

use BadMethodCallException;

use BBCMS\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class BasePolicy
{
    use HandlesAuthorization;

    public function __call($method, $parameters)
    {
        // throw new \Exception(...$parameters);

        $user = $parameters[0];

        if ($user instanceof User) {
            return $user->hasRole('owner');
        }

        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }
}
