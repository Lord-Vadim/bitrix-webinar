<?php
/**
 * @global \CMain $APPLICATION
 * @var array $arResult
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

// Выводит список пользователей
echo var_dump($this->getComponent()->arResult);
