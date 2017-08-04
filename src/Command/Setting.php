<?php


namespace MaDnh\LaravelSetting\Command;


use MaDnh\LaravelSetting\Model\Setting as SettingModel;
use MaDnh\LaravelDevHelper\Command\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Setting extends BaseCommand
{
    protected $name = 'app:setting';

    protected $description = 'Work with system settings';

    protected $actions = [
        'dump' => ['Show settings detail', 'dumpSetting'],
        'post' => ['Post settings from setting file (default is `config/setting.php`) to DB', 'postSetting'],
        'make' => ['Write settings from DB to setting file (default is `config/setting.php`)', 'makeConfig'],
        'init' => ['Clean DB, do `post` then `make`', 'initSetting'],
    ];

    public function handle()
    {
        $action = $this->argument('action');

        if (!array_key_exists($action, $this->actions)) {
            throw new \Exception("Invalid action, only supports:\n" . strip_tags($this->actionList()));
        }

        call_user_func([$this, $this->actions[$action][1]]);

        $this->info("\n\nComplete!");
    }

    protected function actionList()
    {
        return implode("\n", array_map(function ($value, $key) {
            return '- <info>' . $key . '</info>: ' . $value[0];
        }, $this->actions, array_keys($this->actions)));
    }


    protected function getArguments()
    {
        return [
            ['action', InputArgument::OPTIONAL, "Action to do, optional. Supports:\n" . $this->actionList() . "\n", 'dump']
        ];
    }

    protected function getOptions()
    {
        return [
            ['name', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Setting name, use with "<comment>dump</comment>" action. If not specified then dump all of settings'],
            ['file', null, InputOption::VALUE_REQUIRED, 'File to load init settings, use in "<comment>init</comment>" and <comment>make</comment> actions']
        ];
    }


    protected function dumpSetting()
    {
        $this->softBanner('System settings');

        $name = $this->option('name');

        if (empty($name)) {
            dump(setting());

            return;
        }

        foreach ($name as $setting) {
            if (!hasSetting($setting)) {
                try {
                    throw new \Exception('Setting not exists: ' . $setting);
                } catch (\Exception $e) {
                    //
                }
            }

            dump(setting($setting));
        }
    }


    protected function initSetting()
    {
        $this->softBanner($this->actions['init'][0]);

        $setting_file = $this->getInitSettingFile();

        $default_settings = require $setting_file;
        SettingModel::updateSettings($default_settings, true, true);

        $this->info('Complete');
    }

    protected function getInitSettingFile()
    {
        return $this->getOptionFile(env('INIT_SETTING_FILE', config_path('setting_init.php')));
    }

    protected function getOptionFile($default = null)
    {
        $file = $this->option('file');

        if (empty($file)) {
            return $default;
        }
        if (!file_exists($file)) {
            $file = base_path(DIRECTORY_SEPARATOR . ltrim($file, '\\/'));
        }
        if (!file_exists($file)) {
            throw new \Exception('Setting file not found');
        }

        return $file;
    }

    protected function postSetting()
    {
        $this->softBanner($this->actions['post'][0]);

        SettingModel::query()->truncate();
        SettingModel::postSettings();

        $this->info('Complete');
    }

    protected function makeConfig()
    {
        $this->softBanner($this->actions['make'][0]);

        $setting_file = $this->getOptionFile(config_path('setting.php'));
        SettingModel::makeConfig($setting_file);

        $this->info('Complete');
    }
}