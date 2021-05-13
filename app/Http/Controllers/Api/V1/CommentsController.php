<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Comment\MassUpdate as MassUpdateCommentRequest;
use App\Http\Requests\Api\V1\Comment\Store as StoreCommentRequest;
use App\Http\Requests\Api\V1\Comment\Update as UpdateCommentRequest;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
// use Illuminate\Database\Eloquent\Relations\MorphTo;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentsController extends ApiController
{
    /**
     * Дополнение к карте сопоставления
     * методов ресурса и методов в классе политик.
     *
     * @var array
     */
    protected $advancedAbilityMap = [
        'massUpdate' => 'massUpdate',

    ];

    /**
     * Массив дополнительных методов, не имеющих
     * конкретной модели в качестве параметра класса политик.
     *
     * @var array
     */
    protected $advancedMethodsWithoutModels = [
        'massUpdate',

    ];

    /**
     * Создать экземпляр контроллера.
     */
    public function __construct()
    {
        $this->authorizeResource(Comment::class, 'comment');
    }

    /**
     * Отобразить весь список сущностей,
     * включая связанные сущности.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $comments = Comment::with([
            'commentable',
            'user:users.id,users.name',
        ])
            ->advancedFilter($request->all());

        $collection = new CommentCollection($comments);

        return $collection->response()
            ->setStatusCode(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * Отобразить сущность.
     *
     * @param  Comment  $comment
     * @return JsonResponse
     */
    public function show(Comment $comment)
    {
        $comment->load([
            'commentable',
            'user',
        ]);

        $resource = new CommentResource($comment);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Обновить сущность в хранилище.
     *
     * @param  UpdateCommentRequest  $request
     * @param  Comment  $comment
     * @return JsonResponse
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update($request->validated());

        $resource = new CommentResource($comment);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Массово обновить сущности по `id` в хранилище.
     *
     * @param  MassUpdateCommentRequest  $request
     * @return JsonResponse
     */
    public function massUpdate(MassUpdateCommentRequest $request)
    {
        $ids = $request->comments;
        $attribute = $request->mass_action;
        $query = Comment::whereIn('id', $ids);

        switch ($attribute) {
            case 'published':
                $query->update([
                    'is_approved' => true,
                ]);
                break;
            case 'unpublished':
                $query->update([
                    'is_approved' => false,
                ]);
                break;
        }

        // No need to load relationships.
        $comments = Comment::whereIn('id', $ids)->get();

        $collection = new CommentCollection($comments);

        return $collection->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Удалить сущность из хранилища.
     *
     * @param  Comment  $comment
     * @return JsonResponse
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
