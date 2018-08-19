<?php

/**
 * Created by PhpStorm.
 * User: Vadim Epifanov
 * Date: 17.08.2018
 * Time: 18:37
 */

namespace YLab\Webinar;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\DateField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Type\Date;

/**
 * Class UsersTable -
 * реализует связь с таблицей b_ylab_users.
 *
 * @package YLab\Webinar
 */
class UsersTable extends DataManager
{
    /**
     * Метод getFilePath -
     * возвращает путь расположения файла
     *
     * @return string
     */
    public static function getFilePath()
    {
        return __FILE__;
    }

    /**
     * Метод getTableName -
     * возвращает имя таблицы
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'b_ylab_users';
    }

    /**
     * Метод getMap -
     * возвращает структуру таблицы b_ylab_users
     *
     * @return array|\Bitrix\Main\Entity\public
     * @throws \Exception
     */
    public static function getMap()
    {
        return [
            new IntegerField('ID',
                [
                    'primary' => true,
                    'autocomplete' => true
                ]
            ),
            new StringField('NAME',
                [
                    'required' => true
                ]
            ),
            new DateField('BIRTHDAY',
                [
                    'required' => true
                ]
            ),
            new StringField('PHONE',
                [
                    'required' => true
                ]
            ),
            new IntegerField('CITY',
                [
                    'required' => true
                ]
            )
        ];
    }

    /**
     * Метод addUser -
     * добавляет нового пользователя в b_ylab_users
     *
     * @param string $sName - Имя пользователя
     * @param string $sBirthday - Дата рождения в формате d.m.Y
     * @param string $sPhone - Телефон в формате +7xxxxxxxxxx
     * @param int $iCityCode - Код города
     * @return array
     */
    public static function addUser($sName, $sBirthday, $sPhone, $iCityCode)
    {
        try {
            $oResult = self::add(
                [
                    'NAME' => $sName,
                    'BIRTHDAY' => new Date($sBirthday, 'd.m.Y'),
                    'PHONE' => $sPhone,
                    'CITY' => $iCityCode
                ]
            );
            if ($oResult->isSuccess()) {
                return ['ID' => $oResult->getId()];
            } else {
                return $oResult->getErrorMessages();
            }
        } catch (\Exception $e) {
            return ['Error: ' => $e->getMessage()];
        }
    }
}
