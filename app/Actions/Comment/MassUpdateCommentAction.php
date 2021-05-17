<?php

namespace App\Actions\Comment;

use App\Contracts\Actions\Comment\MassUpdatesComment;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Validation\Rule;

class MassUpdateCommentAction extends CommentActionAbstract implements MassUpdatesComment
{
    /**
     * Validate and mass update specific comments.
     *
     * @param  array  $input
     * @return EloquentCollection
     */
    public function massUpdate(array $input): EloquentCollection
    {
        $this->authorize('massUpdate', Comment::class);

        $validated = $this->validate($input);
        $ids = $validated['comments'];
        $attribute = $validated['mass_action'];

        $query = Comment::whereIn('id', $ids);

        switch ($attribute) {
            case 'approved':
                $query->update([
                    'is_approved' => true,
                ]);
                break;
            case 'unapproved':
                $query->update([
                    'is_approved' => false,
                ]);
                break;
        }

        // No need to load relationships.

        return $query->get();
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    protected function messages(): array
    {
        return [
            // 'comments.*' => trans('msg.validate.comments'),
            // 'mass_action.*' => trans('msg.validate.mass_action'),
        ];
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'comments' => [
                'required',
                'array',
            ],

            'comments.*' => [
                'required',
                'integer',
            ],

            'mass_action' => [
                'required',
                'string',
                Rule::in([
                    'approved',
                    'unapproved',
                ]),
            ],
        ];
    }
}
