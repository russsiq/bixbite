<?php

namespace BBCMS\Http\Controllers;

use BBCMS\Models\File;

class DownloadsController extends BaseController
{
    public function __construct(File $model)
    {
        $this->model = $model;
    }

    public function download(int $id)
    {
        $file = $this->model->whereId($id)->firstOrFail();

        $file->increment('downloads');

        return response()->download(
            $file->absolute_path,
            $file->title.'.'.$file->extension, [
                'Content-Type:'.$file->mime_type,
            ]);
    }
}
