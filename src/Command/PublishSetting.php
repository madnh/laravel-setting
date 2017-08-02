<?php

namespace MaDnh\LaravelSetting\Command;


use MaDnh\LaravelDevHelper\Command\BasePublish;
use MaDnh\LaravelSetting\LaravelSettingServiceProvider;

class PublishSetting extends BasePublish
{
    protected $signature = 'app:setting.publish 
    {methods?* : Publish methods} 
    {--force : Overwrite any existing files}
    {--tags= : Publish tags when publish by vendor method}
    {--subns= : Sub namespace of setting parts}';
    protected $description = 'Publish setting assets';

    protected $serviceProviderClass = LaravelSettingServiceProvider::class;
    protected $baseReplace = null;
    protected $subNamespace = null;

    protected function getSubNamespace()
    {
        if (is_null($this->subNamespace)) {
            $this->subNamespace = $this->option('subns');
        }
        if (!empty($this->subNamespace)) {
            return '\\' . studly_case($this->subNamespace);
        }

        return $this->subNamespace;
    }

    protected function getBaseReplace()
    {
        if (!is_array($this->baseReplace)) {
            $replaces = [];
            $httpNamespace = 'App\\Http';
            $subNamespace = $this->getSubNamespace();

            $replaces['DummyControllerNamespace'] = $httpNamespace . '\\Controllers' . $subNamespace;
            $replaces['DummyRequestNamespace'] = $httpNamespace . $subNamespace;
            $replaces['DummyModelNamespace'] = $httpNamespace . '\\Models' . $subNamespace;
            $replaces['DummyObserverNamespace'] = 'App\\Observers' . $subNamespace;

            $this->baseReplace = $replaces;
        }


        return $this->baseReplace;
    }

    public function publishModel()
    {
        $this->softTitle('Publish "<info>models</info>"');

        $this->doPublishFile(
            __DIR__ . '/../../stub/App/Models/Setting.php',
            app_path('Models' . $this->getSubNamespace().'/Setting.php'),
            $this->getBaseReplace());
    }

    public function publishObserver()
    {
        $this->softTitle('Publish "<info>model observers</info>"');
        $this->doPublishFile(
            __DIR__ . '/../../stub/App/Observers/SettingObserver.php',
            app_path('Observers' . $this->getSubNamespace().'/SettingObserver.php'),
            $this->getBaseReplace()
        );
    }

    public function publishLocale()
    {
        $this->softTitle('Publish "<info>locale</info>"');
        $this->doPublishFile(
            __DIR__ . '/../Locale/en.php',
            resource_path('lang/en/model_setting.php'),
            $this->getBaseReplace()
        );
    }

    public function publishMigration()
    {
        $this->softTitle('Publish "<info>migration</info>"');
        $this->doPublishFile(
            __DIR__ . '/../Migration/2017_08_01_103732_create_settings_table.php',
            base_path('database/migrations/2017_08_01_103732_create_settings_table.php')
        );
    }

    public function publishSettingInit()
    {
        $this->softTitle('Publish "<info>init settings</info>"');
        $this->doPublishFile(
            __DIR__ . '/../../stub/setting_init.php',
            config_path('setting_init.php')
        );
    }

    public function publishController()
    {
        $this->softTitle('Publish "<info>controller</info>"');
        $this->doPublishFile(
            __DIR__ . '/../../stub/App/Http/Controllers/SettingController.php',
            app_path('Http/Controllers' . $this->getSubNamespace() . '/SettingController.php'),
            $this->getBaseReplace()
        );
    }

    public function publishRequest()
    {
        $this->softTitle('Publish "<info>request</info>"');
        $this->doPublishFile(
            __DIR__ . '/../../stub/App/Http/Requests/UpdateSettingRequest.php',
            app_path('Http/Requests' . $this->getSubNamespace().'/UpdateSettingRequest.php'),
            $this->getBaseReplace()
        );
    }
}