<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use MaDnh\LaravelSetting\Model\Setting;

class UpdateSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return Setting::$rules;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return Setting::labels();
    }
}
