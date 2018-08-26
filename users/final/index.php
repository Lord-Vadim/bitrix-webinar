<?php
/**
 * Created by PhpStorm
 * User: Vadim Epifanov
 * Date: 17.08.2018
 * Time: 09:47
 *
 * @global \CMain $APPLICATION
 */

use Bitrix\Main\Localization\Loc;

/** @global \CMain $APPLICATION */
global $APPLICATION;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

if (CModule::IncludeModule('ylab.webinar')) { // Если модуль установлен

    $APPLICATION->SetTitle(Loc::getMessage('TITLE'));

    // Заголовок
    echo '<h2 class="header-3">' . Loc::getMessage('HOMEWORK') . '</h2>';
    // Подключение компонентов
    $APPLICATION->IncludeComponent('ylab.webinar:users.add', '', []);
    $APPLICATION->IncludeComponent('ylab.webinar:users.show', '', []);

    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");

} else { // Если модуль не установлен

    // Создание объекта модуля ylab.webinar
    $oModule = CModule::CreateModuleObject('ylab.webinar');

    if (is_object($oModule)) { // Если объект модуля ylab.webinar создан

        // Установка модуля
        $oModule->DoInstall();

        // Перезагрузка страницы
        header("Refresh:0");

    } else { // Если ошибка создания объекта модуля ylab.webinar

        // Сообщение об ошибке
        echo '<h3>' . Loc::getMessage('ERROR') . '</h3>';
    }
}
