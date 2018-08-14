<?php
/**
 * Created by PhpStorm.
 * User: Vadim Epifanov
 * Date: 13.08.2018
 * Time: 14:34
 *
 * @global \CMain $APPLICATION
 */

global $APPLICATION;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Список пользователей");

$APPLICATION->IncludeComponent('ylab:users.form', '', []);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
