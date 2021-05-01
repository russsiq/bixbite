<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Russsiq\EnvManager\Facades\EnvManager;

/**
 * Setting model.
 *
 * @property-read int $id
 * @property-read string $module_name
 * @property-read string $name
 * @property-read string $type
 * @property-read string $value
 * @property-read \Illuminate\Support\Carbon $created_at
 * @property-read \Illuminate\Support\Carbon $updated_at
 */
class Setting extends Model
{
    use Mutators\SettingMutators;
    use Traits\Dataviewer;
    use HasFactory;

    public const TABLE = 'settings';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = self::TABLE;

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'module_name' => '',
        'name' => '',
        'type' => 'string',
        'value' => '',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'module_name' => 'string',
        'name' => 'string',
        'type' => 'string',
        'value' => 'string',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'module_name',
        'name',
        'type',
        'value',
    ];

    /**
     * Attributes by which filtering is allowed.
     *
     * @var array
     */
    protected $allowedFilters = [
        //
    ];

    /**
     * The attributes by which sorting is allowed.
     *
     * @var array
     */
    protected $orderableColumns = [
        'id',
        'module_name',
        'name',
        'created_at',
    ];

    /**
     * Разрешенные переменные для сохранения в файл переменных окружения.
     *
     * @var string[]
     */
    protected static $allowedVariablesForEnv = [
        'APP_DASHBOARD',
        'APP_ENV',
        'APP_LOCALE',
        'APP_NAME',
        'APP_THEME',

        'ORG_ADDRESS_LOCALITY',
        'ORG_ADDRESS_STREET',
        'ORG_CONTACT_EMAIL',
        'ORG_CONTACT_TELEPHONE',
        'ORG_NAME',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_name', 'name', 'module');
    }

    /**
     * Обновить настройки модуля, пришедшие от пользователя.
     *
     * @param  Module  $module
     * @param  array  $attributes
     * @return Collection
     */
    public static function massUpdateByModule(Module $module, array $attributes): Collection
    {
        // 1 Извлечь все настройки модуля.
        $settings = $module->settings()->get();

        // 2 Пробежаться по коллекции, изменить значения настроек.
        $settings->each(function ($setting, $key) use ($attributes) {
            $setting->value = $attributes[$setting->name];
        });

        // 4 Сохранить настройки модуля в БД.
        $module->settings()->saveMany($settings);

        // $this->fireModelEvent('massUpdateByModule', false);

        // 5 Получить коллекцию обновленных настроек для сохранения в файл.
        $updated = $settings->pluck('value', 'name');

        // 6 Обновление настроек в директории `config/settings`.
        self::updateSettingsFile($module->name, $updated);

        // 8 Записать переменные окружения в файл.
        self::pushToEnvFile($updated);

        // 9 Очистить и закешировать настройки.
        Artisan::call('config:cache');

        // 10 Возвращаем измененные настройки для модуля.
        return $settings;
    }

    /**
     * Записать доступные переменные окружения в файл.
     *
     * @param  BaseCollection  $updated
     * @return void
     */
    protected static function pushToEnvFile(BaseCollection $updated): void
    {
        $updated = $updated->mapWithKeys(function ($value, $name) {
                return [
                    strtoupper($name) => $value
                ];
            })
            ->filter(function ($value, $name) {
                return in_array($name, self::$allowedVariablesForEnv);
            });

        if ($updated->isNotEmpty()) {
            EnvManager::setMany($updated->toArray())->save();
        }
    }

    /**
     * Обновить настройки в файл настроек модуля.
     *
     * @param  string  $modulename
     * @param  BaseCollection  $updated
     * @return void
     */
    protected static function updateSettingsFile(string $modulename, BaseCollection $updated): void
    {
        $path = config_path('settings');
        $file = $path.DS.$modulename.'.php';
        $content = '<?php return '.var_export($updated->toArray(), true).';';

        File::ensureDirectoryExists($path);
        File::put($file, $content, true);
    }

    /**
     * Получить список встроенных примитивных типизаторов.
     *
     * @return array
     */
    public function primitiveCastTypes(): array
    {
        return static::$primitiveCastTypes;
    }

    /**
     * Определить, что переданный тип относится к примитивной типизации.
     *
     * @param  string $castType
     * @return bool
     */
    public function isPrimitiveCastTypes(string $castType): bool
    {
        return in_array($castType, $this->primitiveCastTypes());
    }
}
