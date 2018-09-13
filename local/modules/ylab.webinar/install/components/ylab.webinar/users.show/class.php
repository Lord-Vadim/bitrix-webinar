<?php
/**
 * Created by PhpStorm
 * User: Vadim Epifanov
 * Date: 17.08.2018
 * Time: 11:03
 */

namespace YLab\Webinar;

use Bitrix\Main\Loader;
use CBitrixComponent;

/**
 * Класс UsersShowComponent
 * выводит список пользователей c помощью ORM.
 *
 * @package YLab\Webinar
 */
class UsersShowComponent extends CBitrixComponent
{
    /**
     * Метод executeComponent
     * вызывается при создании компонента.
     *
     * @return mixed|void
     */
    public function executeComponent()
    {
        // Архив для хранения списока пользователей
        $this->arResult = $this->getUsersList();

        // Вызов шаблона
        $this->includeComponentTemplate();
    }

    /**
     * Метод getUsersList
     * возвращает список пользователей в виде архива. Формат архива:
     * - [ ]['ID'] - ID пользователя
     * - [ ]['NAME'] - Имя пользователя
     * - [ ]['BIRTHDAY'] - Дата рождения пользователя
     * - [ ]['PHONE'] - Телефон пользователя
     * - [ ]['CITY'] - Город проживания пользователя
     *
     * @return array
     */
    protected function getUsersList()
    {
        // Временный массив для заполнения списка
        $arTemp = [];

        try {
            // Подключает модуль для работы с ORM таблицами
            Loader::includeModule('ylab.webinar');

            // Получает список пользователей
            $oResult = UsersTable::getList();

            // Перебор всех полученных элементов и заполнение массива
            while ($oElement = $oResult->fetch()) {
                $arTemp[] = [
                    'ID' => $oElement['ID'],
                    'NAME' => $oElement['NAME'],
                    'BIRTHDAY' => $oElement['BIRTHDAY']->toString(),
                    'PHONE' => $oElement['PHONE'],
                    'CITY' => CityTable::getCityByID($oElement['CITY_ID']),
                ];
            }

            return $arTemp;

        } catch (\Exception $e) {
            // Возникла ошибка
            return ['Exception: ' . $e->getMessage()];
        }
    }
}
