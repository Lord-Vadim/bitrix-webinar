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

$aMenu = [
    [
        'parent_menu' => 'global_menu_content',
        'sort' => 400,
        'text' => 'Ylab.Webinar',
        'title' => '',
        'icon' => 'util_menu_icon',
        'page_icon' => 'util_page_icon',
        'items_id' => 'menu_references',
        'items' => [
            [
                'text' => 'Table "Users"',
                'title' => '',
                'url' => 'perfmon_table.php?lang=' . LANGUAGE_ID . '&table_name=b_ylab_users',
                'icon' => 'user_menu_icon',
            ],
            [
                'text' => 'Table "City"',
                'title' => '',
                'url' => 'perfmon_table.php?lang=' . LANGUAGE_ID . '&table_name=b_ylab_city',
                'icon' => 'rating_menu_icon',
            ],
        ]
    ]
];
return $aMenu;
