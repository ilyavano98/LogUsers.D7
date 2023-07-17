<?php

namespace Logusers\D7;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Application;


class SettingsView
{
    protected static $optionName = 'is_logusers';

    public static function getCurrent(): bool
    {
        $getVal = Option::get(
            'logusers.d7',
            static::$optionName,
            false,
            false
        );
        if (empty($getVal) || $getVal != true)
            return false;

        return true;
    }

    public static function setCurrent(string $val): void
    {
        Option::set(
            'logusers.d7',
            static::$optionName,
            $val,
            false
        );
    }
}
