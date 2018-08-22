<?php
/**
 * Created by PhpStorm.
 * User: Vadim Epifanov
 * Date: 17.08.2018
 * Time: 12:44
 */

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\IO\Directory;

/**
 * Class ylab_webinar
 * (модуль) позволяет выводить список пользователей и добавлять новых пользователей с использованием ORM.
 */
class ylab_webinar extends CModule
{
    /**
     * ylab_webinar constructor.
     */
    public function __construct()
    {
        $arModuleVersion = [];

        include __DIR__ . '/version.php';

        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_ID = 'ylab.webinar';
        $this->MODULE_NAME = Loc::getMessage('MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('MODULE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
    }

    /**
     * Метод doInstall
     * устанавливает модуль.
     *
     * @return mixed|void
     */
    public function doInstall()
    {
        $this->InstallDB();
        $this->InstallFiles();
        ModuleManager::registerModule($this->MODULE_ID);
    }

    /**
     * Метод doUninstall
     * удаляет модуль.
     *
     * @return mixed|void
     */
    public function doUninstall()
    {
        $this->UnInstallDB();
        $this->UnInstallFiles();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    /**
     * Метод InstallDB
     * создает таблицы модуля.
     *
     * @return bool|void
     */
    public function InstallDB()
    {
        /** @var \CMain $APPLICATION */
        /** @var \CDatabase $DB */
        global $DB, $APPLICATION;

        $oResult = $DB->RunSQLBatch(__DIR__ . '/db/' . strtolower($DB->type) . '/install.sql');
        if (is_array($oResult)) {
            $APPLICATION->ThrowException(implode("", $oResult));
        }
    }

    /**
     * Метод UnInstallDB
     * удаляет таблицы модуля.
     */
    public function UnInstallDB()
    {
        /** @var \CMain $APPLICATION */
        /** @var \CDatabase $DB */
        global $DB, $APPLICATION;

        $oResult = $DB->RunSQLBatch(__DIR__ . '/db/' . strtolower($DB->type) . '/uninstall.sql');
        if (is_array($oResult)) {
            $APPLICATION->ThrowException(implode("", $oResult));
        }
    }

    /**
     * Метод InstallFiles
     * устанавливает компоненты модуля.
     */
    public function InstallFiles()
    {
        CopyDirFiles(
            __DIR__ . "/components",
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components",
            true,
            true
        );
    }

    /**
     * Метод UnInstallFiles
     * удаляет компоненты модуля.
     */
    public function UnInstallFiles()
    {
        Directory::deleteDirectory(
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/" . $this->MODULE_ID
        );
    }
}
