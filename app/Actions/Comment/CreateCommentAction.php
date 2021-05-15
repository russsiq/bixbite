<?php

namespace App\Actions\Comment;

use App\Contracts\Actions\Comment\CreatesComment;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CreateCommentAction extends CommentActionAbstract implements CreatesComment
{
    /**
     * Validate and create a newly comment.
     *
     * @param  Model  $commentable
     * @param  array  $input
     * @param  User|null $user
     * @return Comment
     */
    public function create(Model $commentable, array $input, ?User $user): Comment
    {
        $this->authorize('create', Comment::class);

        $this->commentable = $commentable;
        $this->user = $user;

        $validated = $this->validate(
            $this->prepareForValidation($input)
        );

        $this->comment = $this->commentable->comments()
            ->create($validated)
            ->fresh();

        if ($this->user instanceof User) {
            $this->comment->user = $this->user;
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
