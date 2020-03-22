<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class ApiController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $advancedAbilityMap = [];
    protected $advancedMethodsWithoutModels = [];

    /**
     * Get the map of resource methods to ability names.
     * Переписанный метод из трейта:
     * `Illuminate\Foundation\Auth\Access\AuthorizesRequests
     *      ->resourceAbilityMap()`
     * @return array
     */
    protected function resourceAbilityMap()
    {
        return array_merge([
            'index' => 'viewAny',
            'show' => 'view',
            'create' => 'create',
            'store' => 'create',
            'edit' => 'update',
            'update' => 'update',
            'destroy' => 'delete',
        ], $this->advancedAbilityMap);
    }

    /**
     * Get the list of resource methods which do not have model parameters.
     *
     * Переписанный метод из трейта:
     * `Illuminate\Foundation\Auth\Access\AuthorizesRequests
     *      ->resourceMethodsWithoutModels()`
     * @return array
     */
    protected function resourceMethodsWithoutModels()
    {
        return array_merge([
            'index',
            'create',
            'store',
        ], $this->advancedMethodsWithoutModels);
    }
}
