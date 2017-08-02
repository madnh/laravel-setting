<?php

namespace DummyControllerNamespace;

use DummyModelNamespace\Setting;
use DummyRequestNamespace\UpdateSettingRequest;

use App\Http\Controllers\Controller;
use MaDnh\LaravelDevHelper\Util\ResponseUtil;

class SettingController extends Controller
{
    public function index()
    {
        $settings = config('setting');

        return view('dashboard.settings.form', [
            'settings' => $settings
        ]);
    }

    public function update(UpdateSettingRequest $request)
    {
        $settings = $request->only(Setting::getSupportSettings());
        Setting::updateSettings($settings);

        return ResponseUtil::redirect(route('dashboard.setting_form'), [
            'message' => trans('message.update_successfully', ['target' => trans('model_setting.model')]),
            'message_type' => 'success'
        ]);
    }
}
