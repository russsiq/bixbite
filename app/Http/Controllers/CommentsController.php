<?php

namespace BBCMS\Http\Controllers;

use BBCMS\Models\Comment;
use BBCMS\Http\Requests\CommentStoreRequest;
use BBCMS\Http\Requests\CommentUpdateRequest;

class CommentsController extends SiteController
{
    protected $model;
    protected $template = 'comments';

    public function __construct(Comment $model)
    {
        parent::__construct();
        $this->middleware('throttle:1,1')->only(['store', 'update', 'delete']);

        $this->model = $model;
    }

    public function store(CommentStoreRequest $request)
    {
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
                $comment->update(['is_approved' => true]);
            }
            $comment->user = $user;
            $comment->name = $user->name;
            $comment->email = $user->email;
        }

        if ($request->ajax()) {
            $comment->children = [];
            return response()->json([
                'status' => true,
                'message' => __('comments.msg.add_success'),
                'comment' => view($this->template . '.show', compact('comment', 'entity'))->render()
            ], 200);
        }

        return redirect()->to(url()->previous().'#comment-'.$comment->id)->with('comment_add_success', __('comments.msg.add_success'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \BBCMS\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        $this->authorize($comment);

        if (request()->ajax()) {
            return response()->json([
                'status' => true,
                'content' => $comment->content,
            ], 200);
        }

        pageinfo([
            'title' => __('comments.edit_page'),
            'robots' => 'noindex,follow',
        ]);

        return $this->renderOutput('edit', compact('comment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \BBCMS\Http\Requests\CommentUpdateRequest  $request
     * @param  \BBCMS\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(CommentUpdateRequest $request, Comment $comment)
    {
        $this->authorize($comment);

        $comment->update($request->only(['content']));

        return redirect()->to($comment->url)->withStatus(__('comments.msg.update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \BBCMS\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $this->authorize($comment);

        $url = $comment->commentable->url;

        $comment->delete();

        return redirect()->to($url)->withStatus(__('comments.msg.destroy'));
    }
}
