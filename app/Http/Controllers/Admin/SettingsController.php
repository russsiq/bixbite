<?php

namespace BBCMS\Http\Controllers\Admin;

use BBCMS\Models\{Module, Setting};
use BBCMS\Http\Controllers\Admin\AdminController;
use BBCMS\Http\Requests\Admin\{SettingRequest, SettingModuleRequest};

class SettingsController extends AdminController
{
    protected $model;
    protected $modules;
    protected $field_types;
    protected $template = 'settings';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Setting $model, Module $modules)
    {
        parent::__construct();
        // Общий посредник - Просмотр и сохранение настроек модулей
        $this->middleware(['can:admin.settings.details']);

        $this->model = $model;
        $this->modules = $modules;
        $this->field_types = $this->model::getAllowedFieldTypes();
    }

    /**
     * Display a listing of the modules.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = $this->model
            ->with(['module'])
            ->get();
        
        return $this->makeResponse('index', compact('settings'));
    }

    /**
     * Show the form for creating a new $setting.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! empty($this->module_name = (string) request('module_name'))) {
            \Lang::addJsonPath(skin_path('lang' . DS . $this->module_name));
        }

        return $this->makeResponse('create', [
            'setting' => (object) [ // collect
                'module_name' => $this->module_name ?? null,
            ],
            'modules' => $this->modules->all(),
            'field_types' => $this->field_types,
            'datalist' => (object) [
                'sections' => $this->module_name
                    ? $this->model::getSections($this->module_name) : [],
                'fieldsets' => $this->module_name
                    ? $this->model::getFieldsets($this->module_name) : [],
            ],
        ]);
    }

    /**
     * Store a newly created $setting in storage.
     *
     * @param  \BBCMS\Http\Requests\Admin\SettingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SettingRequest $request)
    {
        $setting = $this->model->create($request->all());
        
        return $this->makeRedirect(true, ['admin.settings.module', $setting->module], __('msg_store'));
    }

    /**
     * Show the form for editing the $setting.
     *
     * @param  \BBCMS\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        $this->module_name = (string) $setting->module_name;
        \Lang::addJsonPath(skin_path('lang' . DS . $this->module_name));

        return $this->makeResponse('edit', [
            'setting' => $setting,
            'modules' => $this->modules->all(),
            'field_types' => $this->field_types,
            'datalist' => (object) [
                'sections' => $this->model::getSections($this->module_name),
                'fieldsets' => $this->model::getFieldsets($this->module_name),
            ],
        ]);
    }

    /**
     * Update the $setting in storage.
     *
     * @param  \BBCMS\Http\Requests\Admin\SettingRequest  $request
     * @param  \BBCMS\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(SettingRequest $request, Setting $setting)
    {
        $setting->update($request->all());
        
        return $this->makeRedirect(true, ['admin.settings.module', $setting->module], __('msg_update'));
    }

    /**
     * Remove the $setting from storage.
     *
     * @param  \BBCMS\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }

    /**
     * Show the form for $module settings (config page).
     *
     * @param  \BBCMS\Models\Module $module
     * @return \Illuminate\Http\Response
     */
    public function module(Module $module)
    {
        $vars = $this->model::generate_page($module, 'setting');
        
        return $this->makeResponse('module', $vars);
    }

    /**
     * Update the $module setting in storage.
     *
     * @param  \BBCMS\Http\Requests\Admin\SettingModuleRequest  $request
     * @param  \BBCMS\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function moduleUpdate(SettingModuleRequest $request, Module $module)
    {
        $this->model::moduleUpdate($module, $request->all());
        
        $route = \Route::has('admin.'.$module->name.'.index') ? 'admin.'.$module->name.'.index' : 'dashboard';
        
        return $this->makeRedirect(true, $route, __('msg_update'));
    }
}
