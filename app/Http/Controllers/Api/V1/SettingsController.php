<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Setting\Store as StoreSettingRequest;
use App\Http\Requests\Api\V1\Setting\Update as UpdateSettingRequest;
use App\Http\Resources\SettingResource;
use App\Models\Module;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsController extends ApiController
{
    /**
     * Дополнение к карте сопоставления
     * методов ресурса и методов в классе политик.
     *
     * @var array
     */
    protected $advancedAbilityMap = [
        'getModule' => 'getModule',
        'updateModule' => 'updateModule',

    ];

    /**
     * Массив дополнительных методов, не имеющих
     * конкретной модели в качестве параметра класса политик.
     *
     * @var array
     */
    protected $advancedMethodsWithoutModels = [
        'getModule',
        'updateModule',

    ];

    /**
     * Создать экземпляр контроллера.
     */
    public function __construct()
    {
        $this->authorizeResource(Setting::class, 'setting');
    }

    /**
     * Отобразить список сущностей с дополнительной фильтрацией.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $settings = Setting::with([

        ])
            ->withCount([

            ])
            ->advancedFilter();

        $collection = SettingResource::collection($settings);

        // Для коллекций устанавливаем 206 статус.
        return $collection->response()
            ->setStatusCode(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * Отобразить список настроек для определенного модуля.
     *
     * @param  Request  $request
     * @param  Module  $module
     * @return JsonResponse
     */
    public function getModule(Request $request, Module $module): JsonResponse
    {
        $settings = $module->settings()->get();

        $collection = SettingResource::collection($settings);

        // Для системных настроек дополнительно загружаем массив
        // с системными директориями для выпадающих списков.
        if ('system' === $module->name) {
            $collection->additional([
                'meta' => [
                    'fonts' => select_file('fonts'),
                    'lang' => select_dir(resource_path('lang')),
                    'skins' => select_dir(resource_path('skins')),
                    'themes' => select_dir(resource_path('themes')),

                ],

            ]);
        }

        // Для коллекций устанавливаем 206 статус.
        return $collection->response()
            ->setStatusCode(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * Создать и сохранить сущность в хранилище.
     *
     * @param  StoreSettingRequest  $request
     * @return JsonResponse
     */
    public function store(StoreSettingRequest $request): JsonResponse
    {
        $setting = Setting::create($request->all());

        $resource = new SettingResource($setting);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * Отобразить сущность.
     *
     * @param  Setting  $setting
     * @return JsonResponse
     */
    public function show(Setting $setting): JsonResponse
    {
        $setting->load([

        ]);

        $resource = new SettingResource($setting);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Обновить сущность в хранилище.
     *
     * @param  UpdateSettingRequest  $request
     * @param  Setting  $setting
     * @return JsonResponse
     */
    public function update(UpdateSettingRequest $request, Setting $setting): JsonResponse
    {
        $setting->update($request->all());

        $resource = new SettingResource($setting);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Обновить список настроек для указанного модуля.
     *
     * @param  Request  $request
     * @param  Module  $module
     * @return JsonResponse
     */
    public function updateModule(Request $request, Module $module): JsonResponse
    {
        $settings = Setting::massUpdateByModule($module, $request->all());

        $collection = SettingResource::collection($settings);

        return $collection->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Удалить указанную сущность из хранилища.
     *
     * @param  Setting  $setting
     * @return Response
     */
    public function destroy(Setting $setting): Response
    {
        $setting->delete();

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
