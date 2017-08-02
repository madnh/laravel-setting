<?php


namespace MaDnh\LaravelSetting\Observer;

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


    protected function getSettingClass()
    {
        return \MaDnh\LaravelSetting\Model\Setting::class;
    }

    protected function makeConfig()
    {
        $class = $this->getSettingClass();
        
        if ($class::$temp_make_config) {
            $class::makeConfig();
        }
    }
}