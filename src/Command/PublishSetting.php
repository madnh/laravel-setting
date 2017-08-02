<?php

namespace MaDnh\LaravelSetting\Command;


use MaDnh\LaravelDevHelper\Command\BasePublish;
use MaDnh\LaravelSetting\LaravelSettingServiceProvider;

class PublishSetting extends BasePublish
{
    protected $signature = 'app:setting.publish {tag?* : Publish tags} {--force : Overwrite any existing files}';
    protected $description = 'Publish setting assets';

    protected $serviceProviderClass = LaravelSettingServiceProvider::class;

    public function publishModel()
    {
        $this->softTitle('Publish "<info>models</info>"');

        $this->doPublishDir(__DIR__.'/../../stub/App/Models', app_path('Models'));
    }

    public function publishObserver()
    {
        $this->softTitle('Publish "<info>model observers</info>"');
        $this->doPublishDir(__DIR__.'/../../stub/App/Observers', app_path('Observers'));
    }

    public function publishLocale()
    {
        $this->softTitle('Publish "<info>locale</info>"');
        $this->doPublishFile(__DIR__ . '/../Locale/en.php', resource_path('lang/en/model_setting.php'), [
            'namespace MaDnh\LaravelSetting\Observer;' => 'namespace App\Observers;',
            'use MaDnh\LaravelSetting\Model\Setting;' => 'use App\Models\Setting;'
        ]);
    }

    public function publishMigration()
    {
        $this->softTitle('Publish "<info>migration</info>"');
        $this->doPublishFile(__DIR__ . '/../Migration/2017_08_01_103732_create_settings_table.php', base_path('database/migrations/2017_08_01_103732_create_settings_table.php'));
    }

    public function publishSettingInit()
    {
        $this->softTitle('Publish "<info>init settings</info>"');
        $this->doPublishFile(__DIR__ . '/../setting_init.php', config_path('setting_init.php'));
    }

    public function publishAll()
    {
        $this->publishModel();
        $this->publishObserver();
        $this->publishLocale();
        $this->publishMigration();
        $this->publishSettingInit();
    }
}