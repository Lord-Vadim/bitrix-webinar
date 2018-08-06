<?php
/**
 * @global \CMain $APPLICATION
 * @var array $arResult
 */

use Bitrix\Main\Web\Json;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

// Выводит список пользователей
echo Json::encode($this->getComponent()->arResult);
