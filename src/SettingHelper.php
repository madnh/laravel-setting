<?php


namespace MaDnh\LaravelSetting;


class SettingHelper
{
    protected $loaded = false;
    protected $repository;


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

    public function clearCacheSettingFile($settingFile = null)
    {
        if (function_exists('opcache_invalidate')) {
            @opcache_invalidate($settingFile ? $settingFile : $this->getSettingFilePath());
        }
    }

    protected function loadSetting()
    {
        if ($this->loaded) {
            return;
        }

        $config = $this->getConfigInstance();

        if ($config->has('setting')) {
            return;
        }

        $settingFile = $this->getSettingFilePath();

        $this->clearCacheSettingFile($settingFile);

        $config->set('setting', require $settingFile);

        //Since setting file is request it may be cached by OPCache
        //Clear it's cache before Config load setting file
        $this->clearCacheSettingFile($settingFile);

        $this->loaded = true;
    }

    /**
     * @return \Illuminate\Config\Repository
     */
    protected function getConfigInstance()
    {
        return app('config');
    }

    protected function getSettingFilePath()
    {
        return config_path('setting.php');
    }

    protected function getSettingFullPath($setting = '')
    {
        return 'setting' . ($setting ? '.' . $setting : '');
    }

    public function get($setting = '', $default = null)
    {
        $this->loadSetting();

        return $this->getConfigInstance()->get($this->getSettingFullPath($setting), $default);
    }

    /**
     * Check if a setting is exists
     * @param string $setting
     * @return bool
     */
    public function has($setting)
    {
        $this->loadSetting();

        return $this->getConfigInstance()->has($this->getSettingFullPath($setting));
    }

    public function refresh($newSettings = null)
    {
        $this->loadSetting();
        $this->loaded = false;

        $this->getConfigInstance()->set($this->getSettingFilePath(), $newSettings);
    }

}