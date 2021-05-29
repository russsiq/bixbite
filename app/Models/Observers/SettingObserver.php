<?php

namespace App\Models\Observers;

// Сторонние зависимости.
use App\Models\Setting;

/**
 * Наблюдатель модели `Setting`.
 */
class SettingObserver extends BaseObserver
{
    /**
     * Обработать событие `retrieved` модели.
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
     * Обработать событие `saved` модели.
     * @param  Setting  $setting
     * @return void
     */
    public function saved(Setting $setting): void
    {
        // Слишком дорогая операция при сохранении массива настроек модуля.
        // $this->langUpdate();
    }

    /**
     * Обработать событие `massUpdateByModule` модели.
     * @param  Setting  $setting
     * @return void
     */
    public function massUpdateByModule(Setting $setting)
    {
        //
    }

    /**
     * Update setting lang file with format json.
     */
    protected function langUpdate(Setting $setting)
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
