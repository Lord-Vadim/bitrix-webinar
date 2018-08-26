<?php
/**
 * Created by PhpStorm
 * User: Vadim Epifanov
 * Date: 17.08.2018
 * Time: 18:14
 */

namespace YLab\Webinar;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;

/**
 * Class CityTable
 * реализующий связь с таблицей b_ylab_city.
 *
 * @package YLab\Webinar
 */
class CityTable extends DataManager
{
    /**
     * Метод getFilePath
     *
     * @return string
     */
    public static function getFilePath()
    {
        return __FILE__;
    }

    /**
     * Метод getTableName
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'b_ylab_city';
    }

    /**
     * Метод getMap
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
            )
        ];
    }

    /**
     * Метод getCityByID
     * возвращает название города по его ID.
     *
     * @param int $iID - ID города
     * @return string
     */
    public static function getCityByID($iID)
    {
        try {
            $oResult = self::getList(['select' => ['*'], 'filter' => ['=ID' => $iID]])->fetchAll();
            if ($oResult) {
                return $oResult[0]['NAME'];
            } else {
                return 'City ID = ' . $iID . ' is not in the table.';
            }

        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
