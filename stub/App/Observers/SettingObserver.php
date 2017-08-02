<?php

namespace DummyObserverNamespace;


class SettingObserver extends \MaDnh\LaravelSetting\Observer\SettingObserver
{
    protected function getSettingClass()
    {
        return \DummyModelNamespace\Setting::class;
    }
}