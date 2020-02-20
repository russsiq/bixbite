<?php

namespace BBCMS\Http\Controllers\Api\V1;

use BBCMS\Models\Template;
use BBCMS\Http\Resources\TemplateResource;
use BBCMS\Http\Resources\TemplateCollection;
use BBCMS\Http\Requests\Api\V1\Template\Show as ShowTemplateRequest;
use BBCMS\Http\Requests\Api\V1\Template\Store as StoreTemplateRequest;
use BBCMS\Http\Requests\Api\V1\Template\Update as UpdateTemplateRequest;

use Illuminate\Http\JsonResponse;

class TemplatesController extends ApiController
{
    /**
     * Вся информация о шаблонах формируется в `Request`, поэтому указываем,
     * что в данных методах не нужно создавать экземпляр модели `Template`
     * при проверки прав доступа авторизованного пользователя.
     * @var array
     */
    protected $advancedMethodsWithoutModels = [
        'show',
        'update',
    ];

    public function __construct()
    {
        $this->authorizeResource(Template::class, 'template');
    }

    public function index()
    {
        $templates = Template::all();

        $collection = new TemplateCollection($templates);

        return $collection->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    public function store(StoreTemplateRequest $request)
    {
        $file = $request->template->save($request->all());

        $resource = new TemplateResource($file);

        // Отправляем 200, чтобы во vue-router не было
        // редиректа на страницу редактирования.
        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    public function show(ShowTemplateRequest $request)
    {
        $file = $request->template;

        $resource = new TemplateResource($file);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    public function update(UpdateTemplateRequest $request)
    {
        $file = $request->template->save($request->all());

        $resource = new TemplateResource($file);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * В данной версии не используется.
     * @param  ShowTemplateRequest $request Подгружаем для формирования информации о файле шаблона.
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShowTemplateRequest $request)
    {
        // Template::delete($request->path);

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
