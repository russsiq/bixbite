<?php

namespace App\Http\Controllers\Api\V1;

// Сторонние зависимости.
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Абстрактный базовый класс для контроллеров API.
 */
abstract class ApiController extends BaseController
{
    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    /**
     * Дополнение к карте сопоставления
     * методов ресурса и методов в классе политик.
     * @var array
     */
    protected $advancedAbilityMap = [];

    /**
     * Массив дополнительных методов, не имеющих
     * конкретной модели в качестве параметра класса политик.
     * @var array
     */
    protected $advancedMethodsWithoutModels = [];

    /**
     * Получить карту сопоставления
     * методов ресурса и методов в классе политик.
     * @return array
     *
     * @NOTE: Переписанный метод `resourceAbilityMap` из трейта
     * `Illuminate\Foundation\Auth\Access\AuthorizesRequests.
     */
    protected function resourceAbilityMap(): array
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
     * Получить список методов ресурса, которые не имеют
     * конкретной модели в качестве параметра класса политик.
     * @return array
     *
     * @NOTE: Переписанный метод `resourceMethodsWithoutModels` из трейта
     * `Illuminate\Foundation\Auth\Access\AuthorizesRequests.
     */
    protected function resourceMethodsWithoutModels(): array
    {
        return array_merge([
            'index',
            'create',
            'store',
        ], $this->advancedMethodsWithoutModels);
    }
}
