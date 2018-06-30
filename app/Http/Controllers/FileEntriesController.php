<?php

namespace BBCMS\Http\Controllers;

use BBCMS\Models\FileEntry;
// use BBCMS\Http\Requests\CommentsRequest;

use Illuminate\Http\Request;

class FileEntriesController extends SiteController
{
    protected $model;
    protected $template = 'files';

    public function __construct(FileEntry $model)
    {
        parent::__construct();

        $this->model = $model;
    }

    public function index() {
        $files = $this->model->all();

        return $this->renderOutput('index', compact('files'));
    }

    public function create() {
        return $this->renderOutput('create', []);
    }

    public function uploadFile(Request $request) {
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();

        $path = hash( 'sha256', time());

        if(\Storage::disk('uploads')->put($path.DS.$filename,  \File::get($file))) {

            $input = [
                // 'user_id' => user('id'),
                // 'attachment_id' => $else['attachment_id'] ?? null,
                // 'attachment_type' => $else['attachment_type'] ?? null,
                //
                // 'disk' => $else['disk'] ?? 'public',
                // 'category' => $else['category'] ?? 'default',
                // 'type' => $this->getFileType($path),
                // 'name' => time().'_'.(str_slug($name)),
                // 'extension' => $this->getFileExtension($path),
                // 'filesize' => $this->getFileSize($path),
                // 'checksum' => $this->getFileChecksum($path),
                //
                // 'title' => $name,
                // 'description' => $else['description'] ?? null,
                // 'properties' => $this->getFileProperties($path),

                'filename' => $filename,
                'mime' => $file->getClientMimeType(),
                'path' => $path,
                'size' => $file->getClientSize(),
            ];

            $file = $this->model->create($input);

            return response()->json([
                'success' => true,
                'id' => $file->id
            ], 200);
        }

        return response()->json([
            'success' => false
        ], 500);
    }
}
