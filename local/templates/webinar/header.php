<?php
/** @var $APPLICATION CMain */
/** @var $USER CUser */

global $APPLICATION, $USER;

?><!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title><? $APPLICATION->ShowTitle() ?></title>
    <? $APPLICATION->ShowHead() ?>
    <? if ($APPLICATION->GetCurPage() == '/') {
        $APPLICATION->ShowPanel();
    }; ?>
</head>
<body>
