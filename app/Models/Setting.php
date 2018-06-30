<?php

namespace BBCMS\Models;

use DB;
use Artisan;
use Storage;

use BBCMS\Models\Module;
use BBCMS\Models\BaseModel;
use BBCMS\Models\Mutators\SettingMutators;
use BBCMS\Models\Collections\SettingCollection;

class Setting extends BaseModel
{
    use SettingMutators;

    protected $primaryKey = 'id';
    protected $table = 'settings';
    public $timestamps = false;
    protected $casts = [
        'params' => 'array',
    ];
    protected $fillable = [
        'module_name', 'action', 'section', 'fieldset',
        'name', 'type', 'value', 'params', 'class', 'html_flags',
        'title', 'descr',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($setting) {
            $setting->langUpdate();
        });

        static::updating(function ($setting) {
            $setting->langUpdate();
        });
    }

    // Relation
    public function module()
    {
        return $this->belongsTo(Module::class, 'module_name', 'name', 'module');
    }

    // As method makeFormFields().
    public function newCollection(array $models = [])
    {
        return new SettingCollection($models);
    }

    /**
     * Update setting lang file with format json.
     */
    public function langUpdate()
    {
        $path = skin_path('lang'.DS.$this->module->name).DS.app_locale().'.json';
        $data = file_exists($path) ? json_decode(file_get_contents($path), true) : [];

        $data = array_filter(array_merge($data, [
            $this->name => $this->title,
            $this->name.'#descr' => $this->attributes['descr'],
            'section.' . $this->section => request('section_lang', __('section.' . $this->section)),
            'legend.' . $this->fieldset => request('legend_lang', __('legend.' . $this->fieldset)),
        ]));

        ksort($data);

        $data = json_encode($data, JSON_UNESCAPED_UNICODE);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \RuntimeException(sprintf(
                'Translation file `%s` contains an invalid JSON structure.', $path
            ));
        }

        file_put_contents($path, $data);

        return $this;
    }

    public static function moduleUpdate(Module $module, array $attributes)
    {
        // 1 update in DB.
        DB::transaction(function () use ($module, $attributes) {
            foreach ($attributes as $name => $value) {
                $module->settings()->where('settings.name', $name)->update(['settings.value' => $value]);
            }
        });

        // 2 Update in config/settings path.
        file_put_contents(
            config_path('settings').DS.$module->name.'.php',
            '<?php return ' . var_export($attributes, true) . ';'
        );

        // 3 Clear and cache config. But we will not see flash message.
        Artisan::call('config:cache');
    }

    /**
     * Automatic config screen generator.
     *
     * @param  \BBCMS\Models\Module  $module
     * @param  string  $action
     * @return array
     */
    public static function generate_page(Module $module, string $action)
    {
        $settings = static::query()->where('module_name', $module->name)
            ->where('action', $action)->get()->makeFormFields();

        return [
            'module' => $module,
            'sections' => $settings->pluck('section')->unique(),
            'fieldsets' => $settings->pluck('fieldset')->unique(),
            'fields' => $settings->groupBy(['section', 'fieldset']),
        ];
    }

    public static function getAllowedFieldTypes()
    {
        return [
            'string', 'text', 'text-inline', 'bool', 'float', 'integer',
            'select', 'select-with', 'select-lang', 'select-skins', 'select-themes', 'select-font', // 'select-dir',
            'hidden', 'email', 'password', 'url', 'search', 'datetime-local',
            'file', 'button', 'submit', 'html', 'manual', 'captcha',
        ];
    }

    /**
     * [getSections description]
     *
     * @param  int    $module_name
     * @return array
     */
    public static function getSections(string $module_name)
    {
        return static::query()->select('section')->distinct()->where('module_name', $module_name)->pluck('section');
    }

    /**
     * [getFieldsets description]
     *
     * @param  int    $module_name
     * @return array
     */
    public static function getFieldsets(string $module_name)
    {
        return  static::query()->select('fieldset')->distinct()->where('module_name', $module_name)->pluck('fieldset');
    }
}

// <input type="button">
// <input type="checkbox">
// <input type="color">
// <input type="date">
// <input type="datetime">
// <input type="datetime-local">
// <input type="email">
// <input type="file">
// <input type="hidden">
// <input type="image">
// <input type="month">
// <input type="number">
// <input type="password">
// <input type="radio">
// <input type="range">
// <input type="reset">
// <input type="search">
// <input type="submit">
// <input type="tel">
// <input type="text">
// <input type="time">
// <input type="url">
// <input type="week">
