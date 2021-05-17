<?php

namespace App\Actions\Comment;

use App\Contracts\Actions\Comment\DeletesComment;
use App\Models\Comment;

class DeleteCommentAction extends CommentActionAbstract implements DeletesComment
{
    /**
     * Delete the given comment.
     *
     * @param  Comment  $comment
     * @return int  Remote comment ID.
     */
    public function delete(Comment $comment): int
    {
        $this->authorize(
            'delete', $this->comment = $comment->fresh()
        );

        $id = $comment->id;

        $comment->delete();

        return $id;
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            //
        ];
    }
}
