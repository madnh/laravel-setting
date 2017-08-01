<?php

namespace MaDnh\LaravelSetting\Model;

use MaDnh\LaravelDevHelper\Helper;
use MaDnh\LaravelSetting\Observer\SettingObserver;
use File;
use Config;
use Illuminate\Database\Eloquent\Model;
use MaDnh\LaravelModelLabels\LabelsTrait;

class Setting extends Model
{
    use LabelsTrait;


    protected $table = 'settings';

    protected $primaryKey = 'name';

    public $timestamps = false;

    protected $fillable = ['name', 'value'];

    /**
     * Flag make config when update, make this value to false when set settings without make config.
     *
     * @var bool
     */
    public static $temp_make_config = false;

    public static $cast_settings = [
        //'app__name' => 'string',
        //'email__send_from_address' => 'string',
        //'app__allow_register' => 'bool',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        //'email__send_from_address' => 'nullable|string|email',
        //'app__allow_register' => 'nullable|boolean',
    ];

    /**
     * @var static
     */
    protected static $instance;

    /**
     * @return Setting
     */
    protected static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }


    /**
     * Boot the permission model
     * Attach event listener to remove the many-to-many records when trying to delete
     * Will NOT delete any records if the permission model uses soft deletes.
     *
     * @return void|bool
     */
    public static function boot()
    {
        parent::boot();

        static::observe(SettingObserver::class);
    }

    public static function getCastSettingName($name)
    {
        $name = strtolower($name);

        return array_get(static::$cast_settings, $name, 'string');
    }

    /**
     * Get support settings that can update
     * @return array
     */
    public static function getSupportSettings()
    {
        return array_keys(static::$rules);
    }

    /**
     * Get all of settings from DB
     * @return array
     */
    public static function settings()
    {
        $query = static::query();
        $settings = $query->pluck('value', 'name');

        return $settings->toArray();
    }

    /**
     * Write settings to config file
     * @param string $path Config file, default is config/setting.php
     * @return bool
     */
    public static function makeConfig($path = null)
    {
        $settings = static::settings();
        $settings = static::getStoreSettingsValue($settings);

        $settings_content = ["<?php"];
        $settings_content[] = '//DO NOT EDIT THIS FILE, IT JUST A CACHED VERSION OF SETTINGS TABLE';
        $settings_content[] = "return " . var_export($settings, true) . ';';

        return false !== File::put($path ?: config_path('setting.php'), implode("\n", $settings_content));
    }

    protected static function getStoreSettingsValue($settings)
    {
        $casted_settings = [];

        foreach ($settings as $name => $value) {
            $casted_settings[$name] = static::castValue($name, $value);
        }

        $result = [];

        foreach ($casted_settings as $name => $value) {
            array_set($result, $name, $value);
        }


        return $result;
    }

    /**
     * Get setting from config, if not found then get from DB
     * @param string $name
     * @param null $default
     * @return mixed|null
     */
    public static function getSetting($name, $default = null)
    {
        $name = strtolower($name);
        $config_name = 'setting.' . $name;

        if (Config::has($config_name)) {
            return static::castValue($name, Config::get($config_name, $default));
        }

        $setting = static::query()->where('name', $name)->first();

        if (!empty($setting)) {
            return static::castValue($name, $setting['value']);
        }

        return $default;
    }

    /**
     * Cast a setting to a native PHP type.
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    protected static function castValue($name, $value)
    {
        $instance = static::getInstance();
        $target = static::getCastSettingName($name);

        switch ($target) {
            case 'datetime':
                return $instance->asDateTime($value);
            case 'timestamp':
                return $instance->asTimeStamp($value);
            default:
                return Helper::cast($value, $target);
        }
    }


    /**
     * Check if a setting is exists
     * @param string $name
     * @return bool
     */
    public static function has($name)
    {
        $config_name = 'setting.' . $name;

        if (Config::has($config_name)) {
            return true;
        }

        return static::query()->where('name', $name)->exists();
    }

    /**
     * Update config to DB
     * @param $settings
     * @param bool $makeConfig Take settings to config file after update
     * @param bool $flush Remove all settings first, useful when create first time of settings
     */
    public static function updateSettings($settings, $makeConfig = true, $flush = false)
    {
        ksort($settings);

        if (!$makeConfig) {
            static::$temp_make_config = false;
        }
        if ($flush) {
            static::query()->truncate();

            foreach ($settings as $name => $value) {
                $var = static::getStoreValue($value);

                static::create([
                    'name' => strtolower($name),
                    'value' => strval($var)
                ]);
            }
        } else {
            foreach ($settings as $name => $value) {
                $val = static::getStoreValue($value);

                static::updateOrCreate(
                    ['name' => $name],
                    ['value' => strval($val)]
                );
            }
        }

        if ($makeConfig) {
            static::makeConfig();
        }

        \Artisan::call('config:clear');

        static::$temp_make_config = true;
    }

    protected static function getStoreValue($value)
    {
        $instance = static::getInstance();

        if (is_array($value)) {
            return $instance->asJson($value);
        }
        return $value;
    }

    /**
     * Update settings, do not make config
     * @param string|array $setting
     * @param mixed $value
     */
    public static function set($setting, $value = null)
    {
        $settings = [];

        if (is_array($setting)) {
            $settings = $setting;
        } else {
            $settings[$setting] = $value;
        }

        return static::updateSettings($settings, false, false);
    }

    /**
     * Create settings and make setting config file
     * @param array $settings
     */
    public static function createSettings($settings)
    {
        foreach ($settings as $name => $value) {
            $var = static::getStoreValue($value);

            static::create([
                'name' => $name,
                'value' => strval($var)
            ]);
        }

        static::makeConfig();
    }

    /**
     * Post settings from config file to DB
     */
    public static function postSettings()
    {
        $config = (array)config('setting', []);

        if (empty($config)) {
            return;
        }

        $settings = array_dot($config);

        static::updateSettings($settings, false, true);
    }
}
