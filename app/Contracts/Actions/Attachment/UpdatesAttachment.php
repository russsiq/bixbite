<?php

namespace App\Contracts\Actions\Attachment;

use App\Models\Attachment;

interface UpdatesAttachment
{
    /**
     * Validate and update the given attachment.
     *
     * @param  Attachment  $attachment
     * @param  array  $input
     * @return Attachment
     */
    public function update(Attachment $attachment, array $input): Attachment;
}
