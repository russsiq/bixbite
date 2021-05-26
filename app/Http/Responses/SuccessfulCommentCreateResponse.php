<?php

namespace App\Http\Responses;

use App\Contracts\Responses\SuccessfulCommentCreateResponseContract;
use App\Models\Comment;
use App\Models\Contracts\CommentableContract;
use Illuminate\Contracts\Translation\Translator;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class SuccessfulCommentCreateResponse implements SuccessfulCommentCreateResponseContract
{
    protected Translator $translator;

    protected Comment $comment;

    protected CommentableContract $commentable;

    /**
     * Create a new response instance.
     *
     * @param  string  $status
     * @return void
     */
    public function __construct(Translator $translator, Comment $comment, CommentableContract $commentable)
    {
        $this->translator = $translator;
        $this->comment = $comment;
        $this->commentable = $commentable;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  Request  $request
     * @return SymfonyResponse
     */
    public function toResponse($request): SymfonyResponse
    {
        if ($request->expectsJson()) {
            $this->comment->children = [];
            $this->comment->html = view('comments.show', [
                    'comment' => $this->comment,
                    'commentable' => $this->commentable,
                ])->render();

            return response()->json([
                'message' => $this->statusMessage(),
                'comment' => $this->comment,
            ], SymfonyResponse::HTTP_CREATED);
        }

        return redirect()->to($this->comment->url)
            ->withStatus($this->statusMessage());
    }

    /**
     * Get the response status message.
     *
     * @return string
     */
    public function statusMessage(): string
    {
        return $this->translator->get(static::STATUSES[
            (int) $this->comment->is_approved
        ]);
    }
}
