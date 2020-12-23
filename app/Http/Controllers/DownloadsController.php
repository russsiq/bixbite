<?php

namespace App\Http\Controllers;

use App\Models\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Контроллер, управляющий скачиванием файлов с сайта.
 */
class DownloadsController extends BaseController
{
    /**
     * Модель Файл.
     *
     * @var File
     */
    protected $model;

    /**
     * Создать экземпляр контроллера.
     *
     * @param  File  $model
     */
    public function __construct(File $model)
    {
        $this->model = $model;
    }

    /**
     * Скачать файл с сайта.
     *
     * @param  File  $file
     * @return BinaryFileResponse
     */
    public function __invoke(File $file): BinaryFileResponse
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
