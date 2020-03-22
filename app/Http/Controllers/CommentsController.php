<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\CommentStoreRequest;
use App\Http\Requests\CommentUpdateRequest;

class CommentsController extends SiteController
{
    protected $model;
    protected $template = 'comments';

    public function __construct(Comment $model)
    {
        parent::__construct();

        $this->middleware('throttle:5,1')->only([
            'store',
            'update',
            'delete',
        ]);

        $this->model = $model;
    }

    public function store(CommentStoreRequest $request)
    {
        $this->authorize('create', Comment::class);

        $comment = $this->model->create($request->all());
        $entity = $comment->commentable;

        // Temporarily.
        if ('articles' == $request->commentable_type and 1 === $entity->comments()->count()) {
            cache()->forget('articles-single-'.$request->commentable_id);
        }

        // Not save this data in the database because we already
        // have $user->id. This data is for display only.
        if ($user = user()) {
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
            $comment->html = view($this->template . '.show', compact('comment', 'entity'))->render();

            return response()->json([
                'message' => __('comments.msg.add_success'),
                'comment' => $comment
            ], 200);
        }

        return $this->makeRedirect(true, url()->previous().'#comment-'.$comment->id, __('comments.msg.add_success'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        $this->authorize('update', $comment);

        pageinfo([
            'title' => __('comments.edit_page'),
            'robots' => 'noindex,follow',
        ]);

        return $this->makeResponse('edit', compact('comment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CommentUpdateRequest  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(CommentUpdateRequest $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $comment->update($request->only(['content']));

        return $this->makeRedirect(true, $comment->url, __('comments.msg.update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $url = $comment->commentable->url;

        $comment->delete();

        if (request()->expectsJson()) {
            return response()->json(null, 204);
        }

        return $this->makeRedirect(true, $url, __('comments.msg.destroy'));
    }
}
