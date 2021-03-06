<?php

/**
 * @see https://www.php.net/manual/ru/language.oop5.traits.php#language.oop5.traits.properties
 * @see https://www.php.net/manual/ru/language.oop5.traits.php#language.oop5.traits.abstract
 * @see https://www.php.net/manual/ru/language.types.declarations.php#language.types.declarations.static
 */

namespace Tests\Concerns;

use Closure;
use Mockery\MockInterface;

trait InteractsWithPolicy
{
    /** @var string[] */
    protected $resourceAbilityMap = [
        'index' => 'viewAny',
        'show' => 'view',
        'create' => 'create',
        'store' => 'create',
        'edit' => 'update',
        'update' => 'update',
        'destroy' => 'delete',
    ];

    /**
     * Mock a partial instance of an object in the container.
     *
     * @param  string  $abstract
     * @param  Closure  $mock
     * @return MockInterface
     */
    abstract protected function partialMock($abstract, Closure $mock);

    /**
     * Allow policy abilities for the test.
     *
     * @param  string  $policy
     * @param  string[]  $abilities
     * @return $this
     */
    protected function allowPolicyAbility(string $policy, array $abilities = []): static
    {
        $abilities = $abilities ?: $this->defaultResourceAbilities();

        $this->partialMock($policy,
            function (MockInterface $mock) use ($abilities) {
                return $mock->shouldReceive(...$abilities)
                    ->once()
                    ->andReturn(true);
            }
        );

        return $this;
    }

    /**
     * Deny policy abilities for the test.
     *
     * @param  string  $policy
     * @param  string[]  $abilities
     * @return $this
     */
    protected function denyPolicyAbility(string $policy, array $abilities = []): static
    {
        $abilities = $abilities ?: $this->defaultResourceAbilities();

        $this->partialMock($policy,
            function (MockInterface $mock) use ($abilities) {
                return $mock->shouldReceive(...$abilities)
                    ->once()
                    ->andReturn(false);
            }
        );

        return $this;
    }

    /**
     * Get the map of resource methods to ability names.
     *
     * @return array
     */
    protected function resourceAbilityMap(): array
    {
        return $this->resourceAbilityMap;
    }

    /**
     * Get the names of the default resource abilities.
     *
     * @return array
     */
    protected function defaultResourceAbilities(): array
    {
        return array_unique(array_values(
            $this->resourceAbilityMap()
        ));
    }
}
