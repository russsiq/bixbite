<?php

namespace App\Actions\Comment;

use App\Contracts\Actions\Comment\CreatesComment;
use App\Models\Comment;
use App\Models\Contracts\CommentableContract;
use App\Models\User;

class CreateCommentAction extends CommentActionAbstract implements CreatesComment
{
    /**
     * Validate and create a newly comment.
     *
     * @param  CommentableContract  $commentable
     * @param  array  $input
     * @return Comment
     */
    public function create(CommentableContract $commentable, array $input): Comment
    {
        $this->authorize('create', Comment::class);

        $this->commentable = $commentable;
        $this->author = $this->user();

        $validated = $this->validate(
            $this->prepareForValidation($input)
        );

        $this->comment = $this->commentable->comments()
            ->create($validated)
            ->fresh();

        if ($this->author instanceof User) {
            $this->comment->user = $this->author;
        }

        return $this->comment;
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    protected function rules(): array
    {
        return array_merge(
            $this->userIdRules(),
            $this->parentIdRules(),
            $this->contentRules(),
            $this->authorNameRules(),
            $this->authorEmailRules(),
            $this->authorIpRules(),
            $this->isApprovedRules(),
            $this->recaptchaRules(),
        );
    }
}
