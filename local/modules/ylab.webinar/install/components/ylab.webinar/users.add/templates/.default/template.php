<?php

/**
 * Created by PhpStorm.
 * User: Vadim Epifanov
 * Date: 18.08.2018
 * Time: 15:14
 *
 * @var array $arResult
 * @var array $arResultCity
 */

use YLab\Webinar\Helper;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

CJSCore::Init(array("jquery"));
?>

<h3><?= Helper::getMessage('HEADER') ?></h3>
<form class="form-block" action="" method="post">
    <?= bitrix_sessid_post() ?>
    <? if (count($arResult['ERRORS'])): ?>
        <p class="coment-error"><?= implode('<br/>', $arResult['ERRORS']) ?></p>
    <? elseif ($arResult['SUCCESS']): ?>
        <h3 class="coment-ok"><?= Helper::getMessage('ADDED') . $arResult['USER'] ?></h3>
    <? endif; ?>
    <div>
        <label for="user_name"><?= Helper::getMessage('NAME') ?></label>
        <br>
        <input
                id="user_name"
                class="element-input"
                type="text"
                name="user_name"
                value="<?= $arResult['NAME'] ?>"
                placeholder="<?= Helper::getMessage('PLACEHOLDER_NAME') ?>"
        />
    </div>
    <div>
        <label for="birthday"><?= Helper::getMessage('BIRTHDAY') ?></label>
        <br>
        <input
                id="birthday"
                class="element-input"
                type="text"
                name="birthday"
                maxlength="10"
                value="<?= $arResult['BIRTHDAY'] ?>"
                placeholder="<?= Helper::getMessage('PLACEHOLDER_BIRTHDAY') ?>"
        />
    </div>
    <div>
        <label for="tel"><?= Helper::getMessage('PHONE') ?></label>
        <br>
        <input
                id="tel"
                class="element-input"
                type="tel"
                name="tel"
                maxlength="12"
                value="<?= $arResult['TEL'] ?>"
                placeholder="<?= Helper::getMessage('PLACEHOLDER_PHONE') ?>"
        />
    </div>
    <div>
        <label for="city"><?= Helper::getMessage('CITY') ?></label>
        <br>
        <select id="city" class="select-no element-input" name="city">
            <option class="select-no" value=""><?= Helper::getMessage('CHOICE_CITY') ?></option>
            <? foreach ($this->getComponent()->arResultCity as $arItem) { ?>
                <option
                        class="select-ok"
                        value="<?= $arItem['ID'] ?>"
                    <?= $arItem['ID'] == $arResult['CITY'] ? 'selected' : '' ?>
                >
                    <?= $arItem['CITY'] ?>
                </option>
            <? } ?>
        </select>
    </div>
    <div>
        <button class="form-btn" type="submit" name="submit"><?= Helper::getMessage('SEND') ?></button>
    </div>
    <br>
    <hr>
    <br>
</form>
