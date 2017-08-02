<?php

namespace DummyModelNamespace;


class Setting extends \MaDnh\LaravelSetting\Model\Setting
{
    public static $cast_settings = [
        //'app__name' => 'string',
        //'email__send_from_address' => 'string',
        //'app__allow_register' => 'bool',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        //'email__send_from_address' => 'nullable|string|email',
        //'app__allow_register' => 'nullable|boolean',
    ];
}