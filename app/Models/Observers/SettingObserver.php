<?php

namespace App\Models\Observers;

use App\Models\Setting;

class SettingObserver
{
    /**
     * Handle the Setting "retrieved" event.
     *
     * @param  Setting  $setting
     * @return void
     */
    public function retrieved(Setting $setting): void
    {
        // @NB: в типизации Eloquent нет типа `select`, `email`.
        if ($setting->hasPrimitiveCastType()) {
            $setting->mergeCasts([
                'value' => $setting->type,
            ]);
        }
    }

    /**
     * Handle the Setting "saved" event.
     *
     * @param  Setting  $setting
     * @return void
     */
    public function saved(Setting $setting): void
    {
        // Слишком дорогая операция при сохранении массива настроек модуля.
        // $this->langUpdate();
    }

    /**
     * Handle the Setting "massUpdateByModule" event.
     *
     * @param  Setting  $setting
     * @return void
     */
    public function massUpdateByModule(Setting $setting): void
    {
        //
    }

    /**
     * Update setting lang file with format json.
     *
     * @return Setting
     */
    protected function langUpdate(Setting $setting): Setting
    {
        $path = dashboard_path('lang'.DS.$setting->module->name).DS.app_locale().'.json';
        $data = file_exists($path) ? json_decode(file_get_contents($path), true) : [];

        $data = array_filter(array_merge($data, [
            $setting->name => $setting->title,
            $setting->name.'#descr' => $setting->attributes['descr'],
            'section.' . $setting->section => request('section_lang', __('section.' . $setting->section)),
            'legend.' . $setting->fieldset => request('legend_lang', __('legend.' . $setting->fieldset)),
        ]));

        ksort($data);

        $data = json_encode($data, JSON_UNESCAPED_UNICODE);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \RuntimeException(sprintf(
                'Translation file `%s` contains an invalid JSON structure.', $path
            ));
        }

        file_put_contents($path, $data);

        return $setting;
    }
}
