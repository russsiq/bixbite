<?php

namespace App\Actions\Attachment;

use App\Contracts\Actions\Attachment\FetchesAttachment;
use App\Models\Attachment;
use Illuminate\Contracts\Pagination\Paginator;

class FetchAttachmentAction extends AttachmentActionAbstract implements FetchesAttachment
{
    /**
     * Validate query parameters and return a specified attachment.
     *
     * @param  integer  $id
     * @param  array  $input
     * @return Attachment
     */
    public function fetch(int $id, array $input): Attachment
    {
        $this->attachment = Attachment::findOrFail($id);

        $this->authorize('view', $this->attachment);

        $this->attachment->load([
            'user',
            'attachable',
        ]);

        return $this->attachment;
    }

    /**
     * Validate query parameters and return a collection of attachments.
     *
     * @param  array  $input
     * @return Paginator
     */
    public function fetchCollection(array $input): Paginator
    {
        $this->authorize('viewAny', Attachment::class);

        return Attachment::with([
                'user',
                'attachable',
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
        return [
            //
        ];
    }
}
