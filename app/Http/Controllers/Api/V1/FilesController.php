<?php

namespace BBCMS\Http\Controllers\Api\V1;

use BBCMS\Models\File;

use BBCMS\Http\Resources\FileResource;
use BBCMS\Http\Resources\FileCollection;

use BBCMS\Http\Requests\Api\V1\File\Store as StoreFileRequest;
use BBCMS\Http\Requests\Api\V1\File\Update as UpdateFileRequest;

use Illuminate\Http\JsonResponse;

class FilesController extends ApiController
{
    public function __construct()
    {
        $this->authorizeResource(File::class, 'file');
    }

    /**
     * Отобразить список сущностей с дополнительной фильтрацией,
     * включая связанные сущности.
     * @return JsonResponse
     */
    public function index()
    {
        $files = File::with([
                'user',
                'attachment',
            ])
            ->advancedFilter();

        $collection = new FileCollection($files);

        return $collection->response()
            ->setStatusCode(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * Создать и сохранить сущность в хранилище.
     * @param  StoreFileRequest  $request
     * @return JsonResponse
     */
    public function store(StoreFileRequest $request)
    {
        $file = app(File::class)
            ->manageUpload($request->file('file'), $request->except('file'));

        $resource = new FileResource($file);

        return $resource->additional([
                'message' => __('msg.uploaded_success'),
            ])
            ->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * Отобразить сущность.
     * @param  File   $file
     * @return JsonResponse
     */
    public function show(File $file)
    {
        $file->load([
            'user',
            'attachment',
        ]);

        $resource = new FileResource($file);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Обновить сущность в хранилище.
     * @param  UpdateFileRequest $request
     * @param  File $file
     * @return JsonResponse
     */
    public function update(UpdateFileRequest $request, File $file)
    {
        $file->update($request->all());

        $resource = new FileResource($file);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Удалить сущность из хранилища.
     * @param  File $file
     * @return JsonResponse
     */
    public function destroy(File $file)
    {
        $file->delete();

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
