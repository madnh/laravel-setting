<?php

namespace MaDnh\LaravelSetting;


use Illuminate\Support\ServiceProvider;

class LaravelSettingServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        $this->commands([
            \MaDnh\LaravelSetting\Command\Setting::class,
            \MaDnh\LaravelSetting\Command\PublishSetting::class
        ]);
    }
}