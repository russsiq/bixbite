<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentStoreRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Models\Comment;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Контроллер, управляющий Комментариями сайта.
 */
class CommentsController extends SiteController
{
    /**
     * Модель Комментарий.
     *
     * @var Comment
     */
    protected $model;

    /**
     * Настройки модели Комментарий.
     *
     * @var object
     */
    protected $settings;

    /**
     * Макет шаблонов контроллера.
     *
     * @var string
     */
    protected $template = 'comments';

    /**
     * Создать экземпляр контроллера.
     *
     * @param  Comment  $model
     */
    public function __construct(Comment $model)
    {
        $this->middleware('throttle:5,1')->only([
            'store',
            'update',
            'delete',

        ]);

        $this->model = $model;

        $this->settings = (object) setting($model->getTable());
    }

    /**
     * Отобразить форму редактирования для указанного ресурса.
     *
     * @param  Comment  $comment
     * @return Renderable
     */
    public function edit(Comment $comment): Renderable
    {
        $this->authorize('update', $comment);

        pageinfo([
            'title' => trans('comments.edit_page'),
            'robots' => 'noindex,follow',

        ]);

        return $this->makeResponse('edit', compact('comment'));
    }

    /**
     * Обновить сущность в хранилище.
     *
     * @param  CommentUpdateRequest  $request
     * @param  Comment  $comment
     * @return Response
     */
    public function update(CommentUpdateRequest $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $comment->update($request->only([
            'content',

        ]));

        return $this->makeRedirect(true, $comment->url, trans('comments.msg.update'));
    }

    /**
     * Удалить сущность из хранилища.
     *
     * @param  Comment  $comment
     * @return Response
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $url = $comment->commentable->url;

        $comment->delete();

        if (request()->expectsJson()) {
            return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
        }

        return $this->makeRedirect(true, $url, trans('comments.msg.destroy'));
    }
}
