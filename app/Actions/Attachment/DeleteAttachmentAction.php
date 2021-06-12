<?php

namespace App\Actions\Attachment;

use App\Contracts\Actions\Attachment\DeletesAttachment;
use App\Models\Attachment;

class DeleteAttachmentAction extends AttachmentActionAbstract implements DeletesAttachment
{
    /**
     * Delete the given attachment.
     *
     * @param  Attachment  $attachment
     * @return int  Remote attachment ID.
     */
    public function delete(Attachment $attachment): int
    {
        $this->authorize(
            __FUNCTION__, $this->attachment = $attachment->fresh()
        );

        $id = $attachment->id;

        $attachment->delete();

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
