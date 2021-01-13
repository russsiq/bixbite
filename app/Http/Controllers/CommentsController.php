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
     * Создать и сохранить сущность в хранилище.
     *
     * @param  CommentStoreRequest  $request
     * @return [type]
     */
    public function store(CommentStoreRequest $request)
    {
        $this->authorize('create', Comment::class);

        $comment = $this->model->create($request->all());
        $entity = $comment->commentable;

        // Если добавлен первый комментарий к записи.
        if ('articles' === $request->commentable_type and 1 === $entity->comments()->count()) {
            cache()->forget('articles-single-'.$request->commentable_id);
        }

        // Не нужно сохранять эти данные в БД.
        // Эти данные только для отображения.
        if ($user = user()) {
            // Но, если комментарий оставлен автором записи.
            if ($comment->user_id === $entity->user_id) {
                $comment->update([
                    'is_approved' => true,

                ]);
            }

            $comment->user = $user;
            $comment->name = $user->name;
            $comment->email = $user->email;
        }

        // Temporarily.
        if ($request->expectsJson()) {
            $comment->children = [];
            $comment->html = view(
                    $this->template.'.show',
                    compact('comment', 'entity')
                )->render();

            return response()->json([
                'message' => trans('comments.msg.add_success'),
                'comment' => $comment,

            ], 200);
        }

        return $this->makeRedirect(true, url()->previous().'#comment-'.$comment->id, trans('comments.msg.add_success'));
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
