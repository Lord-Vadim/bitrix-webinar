<?php
/**
 * @global \CMain $APPLICATION
 * @var array $arResult
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

?>

<h2>Домашнее задание № 1</h2>
<?= var_dump($this->getComponent()->arResult); ?>
