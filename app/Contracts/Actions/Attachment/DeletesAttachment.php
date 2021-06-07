<?php

namespace App\Contracts\Actions\Attachment;

use App\Models\Attachment;

interface DeletesAttachment
{
    /**
     * Delete the given attachment.
     *
     * @param  Attachment  $attachment
     * @return int  Remote attachment ID.
     */
    public function delete(Attachment $attachment): int;
}
