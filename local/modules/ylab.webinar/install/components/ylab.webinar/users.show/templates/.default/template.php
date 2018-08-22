<?php
/**
 * Created by PhpStorm.
 * User: Vadim Epifanov
 * Date: 18.08.2018
 * Time: 15:31
 *
 * @var array $arResult
 */

use YLab\Webinar\Helper;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>

<br>
<div class="user-table">
    <table>
        <tr>
            <th><?= Helper::getMessage('ID')?></th>
            <th><?= Helper::getMessage('NAME')?></th>
            <th><?= Helper::getMessage('BIRTHDAY')?></th>
            <th><?= Helper::getMessage('PHONE')?></th>
            <th><?= Helper::getMessage('CITY')?></th>
        </tr>
        <? foreach ($arResult as $arItem) { ?>
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
