<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Module;
use App\Models\Setting;
use App\Http\Resources\SettingResource;

use App\Http\Requests\Api\V1\Setting\Store as StoreSettingRequest;
use App\Http\Requests\Api\V1\Setting\Update as UpdateSettingRequest;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SettingsController extends ApiController
{
    protected $advancedAbilityMap = [
        'getModule' => 'getModule',
        'updateModule' => 'updateModule',
    ];

    protected $advancedMethodsWithoutModels = [
        'getModule',
        'updateModule',
    ];

    public function __construct()
    {
        $this->authorizeResource(Setting::class, 'setting');
    }

    /**
     * Отобразить список сущностей с дополнительной фильтрацией.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $settings = Setting::with([
                //
            ])
            ->withCount([
                //
            ])
            ->advancedFilter();

        $collection = SettingResource::collection($settings);

        // Для коллекций устанавливаем 206 статус.
        return $collection->response()
            ->setStatusCode(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * Отобразить список настроек для определенного модуля.
     * @param  Request $request
     * @param  Module  $module
     * @return \Illuminate\Http\JsonResponse
     */
    public function getModule(Request $request, Module $module)
    {
        $settings = $module->settings()->get();

        $collection = SettingResource::collection($settings);

        // Для системных настроек дополнительно загружаем массив
        // с системными папками для выпадающих списков.
        if ('system' === $module->name) {
            $collection->additional([
                'meta' => [
                    'fonts' => select_file('fonts'),
                    'lang' => select_dir(resource_path('lang')),
                    'skins' => select_dir(resource_path('skins')),
                    'themes' => select_dir(resource_path('themes')),
                ]
            ]);
        }

        // Для коллекций устанавливаем 206 статус.
        return $collection->response()
            ->setStatusCode(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * Создать и сохранить сущность в хранилище.
     * @param  StoreSettingRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreSettingRequest $request)
    {
        $setting = Setting::create($request->all());

       $resource = new SettingResource($setting);

       return $resource->response()
           ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * Отобразить сущность.
     * @param  Setting  $setting
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Setting $setting)
    {
        $setting->load([
            //
        ]);

        $resource = new SettingResource($setting);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Обновить сущность в хранилище.
     * @param  UpdateSettingRequest  $request
     * @param  Setting  $setting
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateSettingRequest $request, Setting $setting)
    {
        $setting->update($request->all());

        $resource = new SettingResource($setting);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Обновить список настроек для определенного модуля.
     * @param  Request $request
     * @param  Module  $module
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateModule(Request $request, Module $module)
    {
        $settings = Setting::massUpdateByModule($module, $request->all());

        $collection = SettingResource::collection($settings);

        return $collection->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        $setting->delete();

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
