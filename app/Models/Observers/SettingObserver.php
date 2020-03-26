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
        $setting->value = $this->castAttribute($setting);
    }

    /**
     * Обработать событие `saving` модели.
     * @param  Setting  $setting
     * @return void
     */
    public function saving(Setting $setting): void
    {
        $setting->value = $this->castAttribute($setting);
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
        $path = skin_path('lang'.DS.$setting->module->name).DS.app_locale().'.json';
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

    /**
     * Cast an attribute to a native PHP type.
     * @param  Setting  $setting
     * @return mixed
     *
     * @source Illuminate\Database\Eloquent\Concerns\HasAttributes
     */
    protected function castAttribute(Setting $setting)
    {
        $value = $setting->value;

        if (is_null($value)) {
            return $value;
        }

        switch ($setting->type) {
            case 'int':
            case 'integer':
                return (int) $value;
            case 'real':
            case 'float':
            case 'double':
                return (float) $value;
            case 'string':
                return (string) $value;
            case 'bool':
            case 'boolean':
                return (bool) $value;
            case 'object':
                return $this->fromJson($value, true);
            case 'array':
            case 'json':
                return $this->fromJson($value);
            // case 'collection':
            //     return new BaseCollection($this->fromJson($value));
            // case 'date':
            //     return $this->asDate($value);
            // case 'datetime':
            // case 'custom_datetime':
            //     return $this->asDateTime($value);
            // case 'timestamp':
            //     return $this->asTimestamp($value);
            default:
                return $value;
        }
    }

    /**
     * Decode the given JSON back into an array or object.
     * @param  string  $value
     * @param  bool  $asObject
     * @return mixed
     *
     * @source Illuminate\Database\Eloquent\Concerns\HasAttributes
     */
    protected function fromJson($value, $asObject = false)
    {
        return json_decode($value, ! $asObject);
    }
}
