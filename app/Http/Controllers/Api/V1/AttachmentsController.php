<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Attachment\Store as StoreAttachmentRequest;
use App\Http\Requests\Api\V1\Attachment\Update as UpdateAttachmentRequest;
use App\Http\Resources\AttachmentCollection;
use App\Http\Resources\AttachmentResource;
use App\Models\Attachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttachmentsController extends ApiController
{
    /**
     * Создать экземпляр контроллера.
     */
    public function __construct()
    {
        $this->authorizeResource(Attachment::class, 'file');
    }

    /**
     * Отобразить список сущностей с дополнительной фильтрацией,
     * включая связанные сущности.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $attachments = Attachment::with([
            'user',
            'attachable',
        ])
            ->advancedFilter($request->all());

        $collection = new AttachmentCollection($attachments);

        return $collection->response()
            ->setStatusCode(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * Создать и сохранить сущность в хранилище.
     *
     * @param  StoreAttachmentRequest  $request
     * @return JsonResponse
     */
    public function store(StoreAttachmentRequest $request)
    {
        $attachment = app(Attachment::class)
            ->manageUpload($request->file('file'), $request->except('file'));

        $resource = new AttachmentResource($attachment);

        return $resource->additional([
            'message' => __('msg.uploaded_success'),
        ])
            ->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * Отобразить сущность.
     *
     * @param  Attachment  $attachment
     * @return JsonResponse
     */
    public function show(Attachment $attachment)
    {
        $attachment->load([
            'user',
            'attachable',
        ]);

        $resource = new AttachmentResource($attachment);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Обновить сущность в хранилище.
     *
     * @param  UpdateAttachmentRequest  $request
     * @param  Attachment  $attachment
     * @return JsonResponse
     */
    public function update(UpdateAttachmentRequest $request, Attachment $attachment)
    {
        $attachment->update($request->validated());

        $resource = new AttachmentResource($attachment);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Удалить сущность из хранилища.
     *
     * @param  Attachment  $attachment
     * @return JsonResponse
     */
    public function destroy(Attachment $attachment)
    {
        $attachment->delete();

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
