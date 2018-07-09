<?php

namespace BBCMS\Http\Controllers\Admin;

use BBCMS\Models\Comment;
use BBCMS\Http\Requests\CommentUpdateRequest;
use BBCMS\Http\Requests\Admin\CommentsRequest;
use BBCMS\Http\Controllers\Admin\AdminController;

class CommentsController extends AdminController
{
    protected $model;
    protected $template = 'comments';

    public function __construct(Comment $model)
    {
        parent::__construct();
        $this->authorizeResource(Comment::class);

        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize($this->model);

        return $this->renderOutput('index', [
            'comments' => $this->model->with(['commentable', 'user'])->orderBy('id', 'desc')->paginate(25),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \BBCMS\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        $this->authorize($this->model);

        if (request()->ajax()) {
            return response()->json([
                'status' => true,
                'content' => $comment->content,
            ], 200);
        }

        return $this->renderOutput('edit', [
            'comment' => $comment,
        ]);
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
        $this->authorize($this->model);

        $comment->update($request->only(['content']));

        return redirect()->route('admin.comments.index')->withStatus(sprintf(
                __('msg.update'), $comment->url, route('admin.comments.edit', $comment)
            ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \BBCMS\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $this->authorize($this->model);

        $comment->delete();

        return redirect()->route('admin.comments.index')->withStatus(__('msg.destroy'));
    }

    /**
     * Mass updates to Comment.
     *
     * @param  \BBCMS\Http\Requests\Admin\CommentsRequest  $request
     * @param  \BBCMS\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function massUpdate(CommentsRequest $request)
    {
        $this->authorize('otherUpdate', $this->model);

        $comments = $this->model->whereIn('id', $request->comments);
        $messages = [];

        switch ($request->mass_action) {
            case 'published':
                if (! $comments->update(['is_approved' => true])) {
                    $messages[] = 'unable to published';
                }
                break;
            case 'unpublished':
                if (! $comments->update(['is_approved' => false])) {
                    $messages[] = 'unable to unpublished';
                }
                break;
            case 'delete':
                if (! $comments->get()->each->delete()) {
                    $messages[] = 'unable to delete';
                }
                break;
        }

        if (! empty($messages)) {
            // return redirect()->back()->withErrors($messages);
            return redirect()->route('admin.comments.index')->withStatus('msg.complete_but_null');
        } else {
            return redirect()->route('admin.comments.index')->withStatus('msg.complete');
        }
    }
}
