<?php
/**
 * Created by PhpStorm.
 * User: Vadim Epifanov
 * Date: 13.08.2018
 * Time: 17:34
 *
 * @var array $arParams
 * @var array $arResult
 * @var array $arResultUsers
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @global CDatabase $DB
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $templateFile
 * @var string $templateFolder
 * @var string $componentPath
 * @var CBitrixComponent $component
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->addExternalCss("/local/styles.css");

?>

<h3>Форма добавления нового пользователя</h3>
<form action="" method="post" class="form form-block">
    <?= bitrix_sessid_post() ?>
    <? if (count($arResult['ERRORS'])): ?>
        <p><?= implode('<br/>', $arResult['ERRORS']) ?></p>
    <? elseif ($arResult['SUCCESS']): ?>
        <h3 style="color: red">Пользователь добавлен</h3>
    <? endif; ?>
    <div style="margin: 5px">
        <label for="user_name">Имя</label>
        <br>
        <input id="user_name" type="text" name="user_name"/>
    </div>
    <div style="margin: 5px">
        <label for="birthday">Дата рождения</label>
        <br>
        <input
                id="birthday"
                type="text"
                name="birthday"
                placeholder="dd.mm.yyyy"
                maxlength="10"
        /><!--pattern="[0-3][0-9].[0-1][0-9].[1-2][0-9]{3}"-->
    </div>
    <div style="margin: 5px">
        <label for="tel">Телефон:</label>
        <br>
        <input
                id="tel"
                type="tel"
                name="tel"
                placeholder="+7xxxxxxxxxx"
                maxlength="12"
        /><!--pattern="[+][7][0-9]{10}"-->
    </div>
    <div style="margin: 5px">
        <label for="city">Город</label>
        <br>
        <select id="city" name="city">
            <option value="">Выбрать</option>
            <? foreach ($this->getComponent()->arResultCity as $arItem) { ?>
                <option value="<?= $arItem['ID'] ?>"><?= $arItem['CITY'] ?></option>
            <? } ?>
        </select>
    </div>
    <div style="margin: 15px 5px">
        <button type="submit" name="submit">Отправить</button>
    </div>
    <br>
    <hr>
    <br>
</form>

<style type="text/css">
    .tftable {
        font-size: 14px;
        color: #333333;
        width: 100%;
        border-width: 1px;
        border-color: #a9a9a9;
        border-collapse: collapse;
    }

    .tftable th {
        font-size: 14px;
        background-color: #fffae1;
        border-width: 1px;
        padding: 8px;
        border-style: solid;
        border-color: #a9a9a9;
        text-align: left;
    }

    .tftable tr {
        background-color: #ffffff;
    }

    .tftable td {
        font-size: 14px;
        border-width: 1px;
        padding: 8px;
        border-style: solid;
        border-color: #a9a9a9;
    }
</style>

<div style="max-width: 700px; max-height: 300px; overflow-y: auto">
    <table class="tftable" border="1">
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Дата рождения</th>
            <th>Номер телефона</th>
            <th>Город</th>
        </tr>
        <? foreach ($this->getComponent()->arResultUsers as $arItem) { ?>
            <tr>
                <td><?= $arItem['ID'] ?></td>
                <td><?= $arItem['NAME'] ?></td>
                <td><?= $arItem['BIRTHDAY'] ?></td>
                <td><?= $arItem['PHONE'] ?></td>
                <td><?= $arItem['CITY'] ?></td>
            </tr>
        <? } ?>
    </table>
</div>
