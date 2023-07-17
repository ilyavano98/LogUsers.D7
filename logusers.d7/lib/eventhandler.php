<?php

namespace Logusers\D7;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
use Bitrix\Main\Type;

class EventHandler
{
    public static function onProlog()
    {
        global $USER,$APPLICATION;
        if (\Logusers\D7\SettingsView::getCurrent() && !empty($APPLICATION->GetCurDir()) && stripos($APPLICATION->GetCurDir(),"bitrix") === false
        && $APPLICATION->GetCurDir() !== "/") {
            /* Получение текущего IP адреса из всех доступных для этого заголовков */
            if (isset($_SERVER['HTTP_FORWARDED'])) {
                $currentIp = $_SERVER['HTTP_FORWARDED'];
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $currentIp = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $currentIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
                $currentIp = $_SERVER['HTTP_X_FORWARDED'];
            } elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
                $currentIp = $_SERVER['HTTP_CF_CONNECTING_IP'];
            }
            $request = Application::getInstance()->getContext()->getRequest();

            LogUsersTable::add(array(
                "DATE"=>new Type\Date(date("Y-m-d h:i:s"), 'Y-m-d h:i:s'),
                "DATE_WITH_TIME"=>date("Y-m-d h:i:s"),
                "IP_ADDRESS"=>$currentIp,
                "USER_AGENT"=>$request->getUserAgent(),
                "URL_ADDRESS"=>$APPLICATION->GetCurUri(),
                "USER_ID"=>$USER->GetID(),
            ));
        }
    }
}