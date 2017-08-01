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
        $this->doPublishFile(__DIR__ . '/../Model/Setting.php', app_path('Models/Model/Setting.php'), [
            'namespace MaDnh\LaravelSetting\Model;' => 'namespace App\Models;',
            'use MaDnh\LaravelSetting\Observer\SettingObserver;' => 'use App\Observers\SettingObserver;'
        ]);
    }

    public function publishObserver()
    {
        $this->doPublishFile(__DIR__ . '/../Observer/SettingObserver.php', app_path('Observers/SettingObserver.php'), [
            'namespace MaDnh\LaravelSetting\Observer;' => 'namespace App\Observers;',
            'use MaDnh\LaravelSetting\Model\Setting;' => 'use App\Models\Setting;'
        ]);
    }

    public function publishLocale()
    {
        $this->doPublishFile(__DIR__ . '/../Locale/en.php', resource_path('lang/en/model_setting.php'), [
            'namespace MaDnh\LaravelSetting\Observer;' => 'namespace App\Observers;',
            'use MaDnh\LaravelSetting\Model\Setting;' => 'use App\Models\Setting;'
        ]);
    }

    public function publishMigration()
    {
        $this->doPublishFile(__DIR__ . '/../Migration/2017_08_01_103732_create_settings_table.php', base_path('database/migrations/2017_08_01_103732_create_settings_table.php'));
    }

    public function publishSettingInit()
    {
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