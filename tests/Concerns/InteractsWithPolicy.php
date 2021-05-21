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
     * Allow policy abilities for the test.
     *
     * @param  string  $policy
     * @param  string[]  $abilities
     * @param  int  $limit
     * @return $this
     */
    protected function allowPolicyAbility(string $policy, array $abilities = [], int $limit = 1): static
    {
        $abilities = $abilities ?: $this->defaultResourceAbilities();

        /** @var \Tests\TestCase $this */
        $this->partialMock(
            $policy,
            fn (MockInterface $mock) => $mock->shouldReceive(...$abilities)
                ->times($limit)
                ->andReturn(true)
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
    protected function denyPolicyAbility(string $policy, array $abilities = [], int $limit = 1): static
    {
        $abilities = $abilities ?: $this->defaultResourceAbilities();

        /** @var \Tests\TestCase $this */
        $this->partialMock(
            $policy,
            fn (MockInterface $mock) => $mock->shouldReceive(...$abilities)
                ->times($limit)
                ->andReturn(false)
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
