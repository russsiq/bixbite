<?php

namespace BBCMS\Http\Controllers\Admin;

use BBCMS\Models\Theme;
use BBCMS\Http\Requests\Admin\ThemeRequest;
use BBCMS\Http\Controllers\Admin\AdminController;

class ThemesController extends AdminController
{
    protected $theme;
    protected $themes;
    protected $app_theme;
    protected $template = 'themes';

    public function __construct(Theme $model)
    {
        parent::__construct();

        $this->model = $model;

        $this->app_theme = app_theme();
        $themes = collect(select_dir('themes'))->map('theme_version')->filter();
        $this->theme = $themes->firstWhere('name', $this->app_theme);
        $this->themes = $themes->except([$this->app_theme])->prepend($this->theme);
    }

    public function index()
    {
        return $this->renderOutput('index', [
            'app_theme' => $this->app_theme,
            'theme' => $this->theme,
            'themes' => $this->themes,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(ThemeRequest $request)
    {
        //
    }

    public function show(Theme $theme)
    {
        //
    }

    public function edit(Theme $theme)
    {
        //
    }

    public function update(ThemeRequest $request, Theme $theme)
    {
        //
    }

    public function destroy(Theme $theme)
    {
        //
    }
}
