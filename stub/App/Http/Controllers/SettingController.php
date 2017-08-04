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
        $settings = setting();

        return view('settings.form', [
            'settings' => $settings
        ]);
    }

    public function update(UpdateSettingRequest $request)
    {
        $settings = $request->only(Setting::getSupportSettings());
        Setting::updateSettings($settings);

        return ResponseUtil::redirect(route('setting_form'), [
            'message' => 'Update system setting success',
            'message_type' => 'success'
        ]);
    }
}
