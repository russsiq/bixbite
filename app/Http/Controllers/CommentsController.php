<?php

namespace BBCMS\Http\Controllers;

use BBCMS\Models\Comment;
use BBCMS\Http\Requests\CommentsRequest;

class CommentsController extends SiteController
{
    protected $model;
    protected $template = 'comments';

    public function __construct(Comment $model)
    {
        parent::__construct();
        $this->middleware('throttle:5,1')->only('store');

        $this->model = $model;
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(CommentsRequest $request)
    {
        $comment = $this->model->create($request->all());
        $entity = $comment->commentable;

        // Not save this data in the database because we already
        // have $user->id. This data is for display only.
        // And -1 sql query
        if ($user = auth()->user()) {
            $comment->user = $user;
            $comment->name = $user->name;
            $comment->email = $user->email;
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => __('comments.msg.add_success'),
                'comment' => view($this->template . '.show', compact('comment', 'entity'))->render()
            ], 200);
        }

        return redirect()->to(url()->previous() . '#comment-' . $comment->id)->with('comment_add_success', __('comments.msg.add_success'));
    }

    public function show(Comment $comment)
    {
        // return view($this->template . '.show', compact('comment', 'entity'))->render();
    }

    public function edit(Comment $comment)
    {
        //
    }

    public function update(CommentsRequest $request, Comment $comment)
    {
        //
    }

    public function destroy(Comment $comment)
    {
        //
    }
}
