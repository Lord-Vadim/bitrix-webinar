<?php

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Loader;

/**
 * Класс UsersListComponent
 *
 * выводит список пользователей из инфоблока "Пользователи"
 */
class UsersListComponent extends CBitrixComponent
{
    /**
     * Константа IBLOCK_TYPE_ID
     * Задает ID типа инфоблока
     */
    const IBLOCK_TYPE_ID = 'lists_999';

    /**
     * Статическое свойство IBLOCK_ID
     *
     * Задает ID инфоблока со списком пользователей
     */
    public static $iBlockID;

    /**
     * Метод executeComponent
     *
     * вызывается при создании компонента
     *
     * @return mixed|void
     */
    public function executeComponent()
    {
        /** @global \CMain $APPLICATION */
        global $APPLICATION;

        // Очищает буфер
        $APPLICATION->RestartBuffer();

        // Получение iBlockID
        self::$iBlockID = $this->getIBlockID();

        // Архив для хранения списока пользователей
        $this->arResult = $this->getUsersList();

        // Вызов шаблона
        $this->includeComponentTemplate();
    }

    /**
     * Метод getUsersList
     *
     * возвращает список пользователей из инфоблока Пользователи
     *
     * @return array
     */
    protected function getUsersList()
    {
        // Временный массив для заполнения списка
        $arTemp = array();

        try {
            // Подключает модуль для работы с инфоблоками
            Loader::includeModule('iblock');

            // Получает элементы инфоблока (IBLOCK_ID = 1) используя фильтр
            $oResult = ElementTable::getList(
                array(
                    'filter' => array('IBLOCK_ID' => self::$iBlockID),
                    'select' => array('ID', 'NAME')
                ));

            // Перебор всех полученных элементов и заполнение массива
            while ($oElement = $oResult->fetch()) {
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

    /**
     * Метод getIBlockID
     * Возвращает iBlockID инфоблока
     *
     * @return integer
     */
    protected function getIBlockID()
    {
        try {
            // Подключает модуль для работы с инфоблоками
            Loader::includeModule('iblock');

            if ($oIBlock = CIBlock::GetList(Array(), Array('XML_ID' => self::IBLOCK_TYPE_ID), true)->GetNext()) {
                return (int)$oIBlock['ID'];
            } else {
                return 0;
            }
        } catch (\Exception $e) {
            return 0;
        }
    }
}
