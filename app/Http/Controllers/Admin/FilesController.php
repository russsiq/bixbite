<?php

namespace BBCMS\Http\Controllers\Admin;

use BBCMS\Models\File;
use BBCMS\Models\Article;
use BBCMS\Http\Requests\Admin\FileRequest;
use BBCMS\Http\Requests\Admin\FileStoreRequest;
use BBCMS\Http\Controllers\Admin\AdminController;

use Illuminate\Validation\ValidationException;

class FilesController extends AdminController
{
    protected $model;
    protected $template = 'files';

    public function __construct(File $model)
    {
        parent::__construct();
        $this->middleware('auth');
        $this->authorizeResource(File::class);

        $this->model = $model;
    }

    public function upload()
    {
        try {
            $request = app(FileStoreRequest::class);
            return response()->json([
                'status' => true, 'message' => __('msg.uploaded_success'),
                'file' => $this->model->manageUpload(
                    $request->file('file'),
                    $request->except('file')
                ),
            ], 200);

        } catch (ValidationException $e) {
            $message = $e->validator->errors()->first();
            return response()->json(['status' => false, 'message' => $message], 200);
        } catch (\Exception $e) {
            $message = 'owner' == user('role') ? $e->getMessage() : __('msg.uploaded_error');
            return response()->json(['status' => false, 'message' => $message], 200);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->renderOutput('index', [
            'filetype' => request('filetype', false),
            'files' => $this->model
                ->where('user_id', user('id'))
                ->filter(request(['filetype', 'user']))
                ->latest()
                ->paginate(18)
                ->appends(
                    request()->except(['page'])
                )
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->renderOutput('create', [
            'file' => [],
            'articles' => Article::where('user_id', user('id'))->latest()->get() ?? [],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \BBCMS\Http\Requests\Admin\FileStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        try {
            $request = app(FileStoreRequest::class);
            $this->model->manageUpload($request->file('file'), $request->except('file'));
            return redirect()->route('admin.files.index')->withStatus(__('msg.uploaded_success'));
        } catch (ValidationException $e) {
            $message = $e->validator->errors()->first();
            return redirect()->back()->withInput()->withErrors($message);
        } catch (\Exception $e) {
            $message = 'owner' == user('role') ? $e->getMessage() : __('validation.uploaded');
            return redirect()->back()->withInput()->withErrors($message);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \BBCMS\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function show(File $file)
    {
        return $this->renderOutput('show', [
            'file' => $file,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \BBCMS\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function edit(File $file)
    {
        return $this->renderOutput('edit', [
            'file' => $file,
            'articles' => Article::where('user_id', user('id'))->latest()->get() ?? [],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \BBCMS\Http\Requests\Admin\FileRequest  $request
     * @param  \BBCMS\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function update(FileRequest $request, File $file)
    {
        $file->update($request->all());

        return redirect()->route('admin.files.index')->withStatus('store!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \BBCMS\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        $file->delete();

        return redirect()->back()->withStatus('destroy!');
    }
}
