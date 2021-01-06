<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Template\Show as ShowTemplateRequest;
use App\Http\Requests\Api\V1\Template\Store as StoreTemplateRequest;
use App\Http\Requests\Api\V1\Template\Update as UpdateTemplateRequest;
use App\Http\Resources\TemplateCollection;
use App\Http\Resources\TemplateResource;
use App\Models\Template;
use Illuminate\Http\JsonResponse;

class TemplatesController extends ApiController
{
    /**
     * Массив дополнительных методов, не имеющих
     * конкретной модели в качестве параметра класса политик.
     *
     * Вся информация о шаблонах формируется в `Request`, поэтому указываем,
     * что в данных методах не нужно создавать экземпляр модели `Template`
     * при проверки прав доступа авторизованного пользователя.
     *
     * @var array
     */
    protected $advancedMethodsWithoutModels = [
        'show',
        'update',

    ];

    /**
     * Создать экземпляр контроллера.
     */
    public function __construct()
    {
        $this->authorizeResource(Template::class, 'template');
    }

    /**
     * Отобразить список сущностей.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $templates = Template::all();

        $collection = new TemplateCollection($templates);

        return $collection->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Создать и сохранить сущность в хранилище.
     *
     * @param  StoreTemplateRequest  $request
     * @return JsonResponse
     */
    public function store(StoreTemplateRequest $request)
    {
        $file = $request->template->save($request->all());

        $resource = new TemplateResource($file);

        // Отправляем 200, чтобы во vue-router не было
        // редиректа на страницу редактирования.
        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Отобразить сущность.
     *
     * @param  ShowTemplateRequest  $request
     * @return JsonResponse
     */
    public function show(ShowTemplateRequest $request)
    {
        $file = $request->template;

        $resource = new TemplateResource($file);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Обновить сущность в хранилище.
     *
     * @param  UpdateTemplateRequest  $request
     * @return JsonResponse
     */
    public function update(UpdateTemplateRequest $request)
    {
        $file = $request->template->save($request->all());

        $resource = new TemplateResource($file);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Удалить сущность из хранилища.
     *
     * В данной версии не используется.
     *
     * @param  ShowTemplateRequest  $request  Подгружаем для формирования информации о файле шаблона.
     * @return JsonResponse
     */
    public function destroy(ShowTemplateRequest $request)
    {
        // Template::delete($request->path);

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
