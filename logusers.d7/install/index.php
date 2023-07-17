<?php

use Bitrix\Main\Event;
use Bitrix\Main\EventResult;
use \Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

if(class_exists("logusers_d7")) return;

Class logusers_d7 extends CModule
{
    var $MODULE_ID = "logusers.d7";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $errors = [];

    public function __construct()
    {
        $arModuleVersion = [];

        include(__DIR__.'/version.php');

        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion))
        {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }

        $this->MODULE_NAME = Loc::getMessage('LOGUSER_D7_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('LOGUSER_D7_MODULE_DESCRIPTION');
    }

    public function DoInstall()
    {
        global $APPLICATION;
        if ($this->isVersionD7()) {
            $this->InstallDB();
            $this->InstallEvents();

            $GLOBALS["errors"] = $this->errors;
            \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);
            $APPLICATION->IncludeAdminFile(Loc::getMessage("LOGUSER_D7_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/logusers.d7/install/step1.php");
        } else {
            $APPLICATION->ThrowException(Loc::getMessage("LOGUSER_D7_INSTALL_ERROR_VERSION"));
        }
    }

    public function DoUninstall()
    {
        global $APPLICATION;

        $this->UnInstallEvents();
        $this->UnInstallDB();

        \CAgent::RemoveModuleAgents('logusers_d7');
        \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);
        $GLOBALS["errors"] = $this->errors;
        $APPLICATION->IncludeAdminFile(Loc::getMessage("LOGUSER_D7_UNINSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/logusers.d7/install/unstep1.php");

        return true;
    }

    public function InstallDB()
    {
        \Bitrix\Main\Application::getConnection()->queryExecute("CREATE TABLE IF NOT EXISTS `logusers_d7` (
        `ID` int NOT NULL AUTO_INCREMENT,
        `DATE` date NOT NULL,
        `DATE_WITH_TIME` varchar(255) NOT NULL,
        `IP_ADDRESS` varchar(255) NOT NULL,
        `USER_AGENT` varchar(255) NOT NULL,
        `URL_ADDRESS` varchar(255) NOT NULL,
        `USER_ID` int,
        PRIMARY KEY(`ID`))"
        );
    }

    public function UnInstallDB($arParams = Array())
    {
        \Bitrix\Main\Application::getConnection()->queryExecute("DROP TABLE IF EXISTS logusers_d7");
    }

    /**
     * Проверка текущей версии ядра битрикса
     * @return bool
     */
    public function isVersionD7()
    {
       return CheckVersion(\Bitrix\Main\ModuleManager::getVersion("main"), "14.00.00");
    }

    public function InstallEvents()
    {
        $eventManager = \Bitrix\Main\EventManager::getInstance();
        $eventManager->registerEventHandler(
            "main",
            "OnProlog",
            $this->MODULE_ID,
            "Logusers\D7\EventHandler",
            "onProlog"
        );

        return true;
    }

    public function UnInstallEvents()
    {
        $eventManager = \Bitrix\Main\EventManager::getInstance();
        $eventManager->unRegisterEventHandler(
            "main",
            "OnProlog",
            $this->MODULE_ID,
            "Logusers\D7\EventHandler",
            "onProlog"
        );

        return true;
    }
}
?>