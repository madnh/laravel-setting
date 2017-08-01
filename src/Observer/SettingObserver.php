<?php


namespace MaDnh\LaravelSetting\Observer;


use MaDnh\LaravelSetting\Model\Setting;

class SettingObserver
{

    public function created()
    {
        $this->makeConfig();
    }
    public function updated()
    {
        $this->makeConfig();
    }
    public function deleted()
    {
        $this->makeConfig();
    }

    protected function makeConfig()
    {
        if (Setting::$temp_make_config) {
            Setting::makeConfig();
        }
    }
}