<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Контроллер, управляющий скачиванием файлов с сайта.
 */
class DownloadsController extends BaseController
{
    /**
     * Скачать файл с сайта.
     *
     * @param  Attachment  $attachment
     * @return BinaryFileResponse
     */
    public function __invoke(Attachment $attachment): BinaryFileResponse
    {
        $attachment->increment('downloads');

        return response()
            ->download(
                $attachment->absolute_path,
                $attachment->title.'.'.$attachment->extension, [
                    'Content-Type:'.$attachment->mime_type,
                ]
            );
    }
}
