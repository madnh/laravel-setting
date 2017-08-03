<?php

if (!function_exists('setting')) {
    /**
     * Get setting in setting file
     * @param string $setting Path in setting config file
     * @param mixed $default
     * @return mixed
     */
    function setting($setting = '', $default = null)
    {
        return \MaDnh\LaravelSetting\SettingHelper::instance()->get($setting, $default);
    }
}
if (!function_exists('hasSetting')) {
    /**
     * Check if a setting is exists
     * @param string $setting
     * @return bool
     */
    function hasSetting($setting)
    {
        return \MaDnh\LaravelSetting\SettingHelper::instance()->has($setting);
    }
}