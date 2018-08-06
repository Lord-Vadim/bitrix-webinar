<?php

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Loader;

/**
 * Класс UsersListComponent выводит список пользователей из инфоблока "Пользователи" (IBLOCK_ID = 1)
 */
class UsersListComponent extends CBitrixComponent
{
    /**
     * Метод executeComponent вызывается при создании компонента
     *
     * @return mixed|void
     */
    public function executeComponent()
    {
        /** @global \CMain $APPLICATION */
        global $APPLICATION;

        // Очищает буфер
        $APPLICATION->RestartBuffer();

        // Архив для хранения списока пользователей
        $this->arResult = $this->getUsersList();

        // Вызов шаблона
        $this->includeComponentTemplate();
    }

    /**
     * Метод getUsersList возвращает список пользователей из инфоблока Пользователи (IBLOCK_ID = 1)
     *
     * @return array
     */
    protected function getUsersList()
    {
        /**
         * Временный массив для заполнения списка
         *
         * @var array
         */
        $arTemp = array();

        try {
            // Подключает модуль для работы с инфоблоками
            Loader::includeModule('iblock');

            // Получает элементы инфоблока (IBLOCK_ID = 1) используя фильтр
            $Result = ElementTable::getList(
                array(
                    'filter' => array('IBLOCK_ID' => 1),
                    'select' => array('ID', 'NAME')
                ));

            // Перебор всех полученных элементов и заполнение массива
            while ($oElement = $Result->fetch()) {
                $arTemp[] = array(
                    'ID' => (int)$oElement['ID'],
                    'NAME' => $oElement['NAME']
                );
            }

            return $arTemp;

        } catch (Exception $e) {
            // Возникла ошибка
            return array('Exception: ' . $e->getMessage());
        }
    }
}
