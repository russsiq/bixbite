<?php

namespace BBCMS\Http\Controllers\Admin;

use BBCMS\Models\Theme;
use BBCMS\Http\Requests\Admin\TemplateRequest;
use BBCMS\Http\Controllers\Admin\AdminController;

class TemplatesController extends AdminController
{
    protected $model;
    protected $template = 'themes.templates';

    public function __construct(Theme $model)
    {
        parent::__construct();

        $this->model = $model;
    }

    /**
     * Show code editor template to current theme.
     *
     * @param  string $path
     * @return string
     */
    public function index()
    {
        return $this->makeResponse('index', [
            'theme' => app_theme(),
            'templates' => $this->model::getTemplates(),
        ]);
    }

    public function store(TemplateRequest $request)
    {
        $template = $request->template;
        $path = theme_path('views').$template;

        // Block and notify if file is already exists.
        if (\File::exists($path)) {
            return $this->makeRedirect(false, 'admin.templates.index', sprintf(
                __('msg.already_exists'), $template
            ));
        }

        \File::put($path, '', true);
        
        return $this->makeRedirect(true, 'admin.templates.index', sprintf(
            __('msg.created'), $template
        ));
    }


    /**
     * Edit template file by path.
     */
    public function edit(TemplateRequest $request, $template)
    {
        try {
            $template = $request->template;
            $path = theme_path('views').$template;

            return response()->json([
                'status' => true, 'message' => null,
                'file' => [
                    'content' => \File::get($path),
                    'size' => formatBytes(\File::size($path)),
                    'modified' => strftime('%Y-%m-%d %H:%M', \File::lastModified($path))
                ]
            ], 200);

        } catch (ValidationException $e) {
            $message = $e->validator->errors()->first();
            return response()->json(['status' => false, 'message' => $message], 200);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            return response()->json(['status' => false, 'message' => $message], 404 );
        }
    }

    /**
     * Update template file by path.
     */
    public function update(TemplateRequest $request, $template)
    {
        try {
            $template = $request->template;
            $path = theme_path('views').$template;
            $content = $request->content;

            // Notify if file was not changed.
            if (\File::exists($path) and \File::get($path) == $content) {
                return response()->json([
                    'status' => false,
                    'message' => sprintf(__('msg.not_updated'), $template),
                ], 200);
            }

            \File::put($path, $content, true);

            return response()->json([
                'status' => true,
                'message' => sprintf(__('msg.updated'), $template),
                'file' => [
                    'size' => formatBytes(\File::size($path)),
                    'modified' => strftime('%Y-%m-%d %H:%M', \File::lastModified($path))
                ]
            ], 200);

        } catch (ValidationException $e) {
            $message = $e->validator->errors()->first();
            return response()->json(['status' => false, 'message' => $message], 200);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            return response()->json(['status' => false, 'message' => $message], 404 );
        }
    }

    /**
     * Unlink template file by path.
     */
    public function destroy(TemplateRequest $request, $template)
    {
        $template = $request->template;
        $path = theme_path('views').$template;

        // Block and notify if file does not exist.
        if (! \File::exists($path)) {
            return $this->makeRedirect(false, 'admin.templates.index', sprintf(
                __('msg.not_exists'), $template
            ));
        }

        // Block if file not delete.
        if (! \File::delete($path)) {
            return $this->makeRedirect(false, 'admin.templates.index', sprintf(
                __('msg.not_deleted'), $template
            ));
        }
        
        return $this->makeRedirect(true, 'admin.templates.index', sprintf(
            __('msg.deleted'), $template
        ));
    }
}
