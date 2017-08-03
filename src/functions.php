<?php

if (!function_exists('setting')) {
    /**
     * Get setting in setting file
     * @param string $setting_path Path in setting config file
     * @param mixed $default
     * @return mixed
     */
    function setting($setting_path = '', $default = null)
    {
        static $cache;

        if (!$cache) {
            $config_path = config_path('setting.php');

            if (!file_exists($config_path)) {
                return config('setting' . ($setting_path ? '.' : '') . $setting_path, $default);
            }

            $cache = require $config_path;
        }
        if (empty($setting_path)) {
            return $cache;
        }


        return array_get($cache, $setting_path, $default);
    }
}
if (!function_exists('hasSetting')) {
    /**
     * Check if a setting is exists
     * @param string $settingName
     * @return bool
     */
    function hasSetting($settingName)
    {
        $specialValue = time() . str_random(5);

        return $specialValue !== setting($settingName, $specialValue);
    }
}