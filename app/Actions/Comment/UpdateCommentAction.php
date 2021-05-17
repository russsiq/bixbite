<?php

namespace App\Actions\Comment;

use App\Contracts\Actions\Comment\UpdatesComment;
use App\Models\Comment;

class UpdateCommentAction extends CommentActionAbstract implements UpdatesComment
{
    /**
     * Validate and update the given comment.
     *
     * @param  Comment  $comment
     * @param  array  $input
     * @return Comment
     */
    public function update(Comment $comment, array $input): Comment
    {
        $this->authorize(
            'update', $this->comment = $comment
        );

        $this->comment->update(
            $this->validate($input)
        );

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
            $this->contentRules(),
        );
    }
}
