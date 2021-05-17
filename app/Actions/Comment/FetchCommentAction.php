<?php

namespace App\Actions\Comment;

use App\Contracts\Actions\Comment\FetchesComment;
use App\Models\Comment;
use Illuminate\Contracts\Pagination\Paginator;

class FetchCommentAction extends CommentActionAbstract implements FetchesComment
{
    /**
     * Validate query parameters and return a specified comment.
     *
     * @param  integer  $id
     * @param  array  $input
     * @return Comment
     */
    public function fetch(int $id, array $input): Comment
    {
        $this->comment = Comment::findOrFail($id);

        $this->authorize('view', $this->comment);

        $this->comment->load([
            'commentable',
        ]);

        return $this->comment;
    }

    /**
     * Validate query parameters and return a collection of comments.
     *
     * @param  array  $input
     * @return Paginator
     */
    public function fetchCollection(array $input): Paginator
    {
        $this->authorize('viewAny', Comment::class);

        return Comment::with([
                'commentable',
            ])
            ->advancedFilter($input);
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    protected function rules(): array
    {
        return array_merge(
            //
        );
    }
}
