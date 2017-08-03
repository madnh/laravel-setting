<?php


namespace MaDnh\LaravelSetting;


class SettingHelper
{
    private $cache = null;
    protected static $instance;

    /**
     * @return self
     */
    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function get($path, $default = null)
    {
        if (is_null($this->cache)) {
            $config_path = config_path('setting.php');

            if (!file_exists($config_path)) {
                $this->cache = config('setting' . ($path ? '.' : '') . $path, $default);
            } else {
//                $config_backup_file = $this->getBackupFile();

//                if (file_exists($config_backup_file)) {
//                    $this->cache = require($config_backup_file);
//                    unlink($config_backup_file);
//                } else {
//                    $this->cache = require $config_path;
//                }

                $this->cache = require $config_path;
            }
        }
        if (empty($path)) {
            return $this->cache;
        }


        return array_get($this->cache, $path, $default);
    }

    /**
     * Check if a setting is exists
     * @param string $setting
     * @return bool
     */
    public function has($setting)
    {
        $specialValue = time() . str_random(10);

        return $specialValue !== $this->get($setting, $specialValue);
    }

    public function clearCache($newSettings = null)
    {
        $this->cache = $newSettings;
    }

    public function getBackupFile()
    {
        return storage_path('logs' . DIRECTORY_SEPARATOR . 'setting_backup.php');
    }
}