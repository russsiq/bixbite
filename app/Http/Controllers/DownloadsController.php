<?php

namespace App\Http\Controllers;

use App\Models\File;

class DownloadsController extends BaseController
{
    public function __construct(File $model)
    {
        $this->model = $model;
    }

    public function download(File $file)
    {
        $file->increment('downloads');

        return response()
            ->download(
                $file->absolute_path,
                $file->title.'.'.$file->extension, [
                    'Content-Type:'.$file->mime_type,
                ]
            );
    }
}
