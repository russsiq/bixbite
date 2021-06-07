<?php

namespace App\Contracts\Actions\Attachment;

use App\Models\Attachment;
use Illuminate\Http\UploadedFile;

interface CreatesAttachment
{
    /**
     * Validate and create a newly attachment.
     *
     * @param  array  $input
     * @return Attachment
     */
    public function create(array $input): Attachment;

    /**
     * Gather data for the given uploaded file.
     *
     * @param  UploadedFile  $uploadedFile
     * @return array
     */
    public function gatherData(UploadedFile $uploadedFile): array;
}
