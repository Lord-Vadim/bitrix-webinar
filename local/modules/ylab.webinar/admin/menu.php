<?php
/**
 * Created by PhpStorm
 * User: Vadim Epifanov
 * Date: 25.08.2018
 * Time: 13:20
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$aMenu = array(
    array(
        'parent_menu' => 'global_menu_content',
        'sort' => 400,
        'text' => "Ylab.Webinar",
        'title' => "",
        'url' => 'perfmon_table.php?lang=ru&table_name=b_ylab_users',
        'items_id' => 'menu_references'
    )
);
return $aMenu;
