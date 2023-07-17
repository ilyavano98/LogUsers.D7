<?php

namespace Logusers\D7;

use Bitrix\Main\Entity;

class LogUsersTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return "logusers_d7";
    }

    public static function getMap()
    {
        return array(
            new Entity\IntegerField("ID", array(
                "primary" => true,
                "autocomplete" => true
            )),
            new Entity\DateField("DATE"),
            new Entity\StringField("DATE_WITH_TIME"),
            new Entity\StringField("IP_ADDRESS"),
            new Entity\StringField("USER_AGENT"),
            new Entity\StringField("URL_ADDRESS", array(
                "required" => true
            )),
            new Entity\IntegerField("USER_ID"),
        );
    }

}