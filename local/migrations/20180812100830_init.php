<?php

/**
 * Created by PhpStorm.
 * User: Vadim Epifanov
 * Date: 10.08.2018
 * Time: 14:23
 */

use Bitrix\Main\Loader;
use Phinx\Migration\AbstractMigration;

/**
 * Class Init
 * Стартовая миграция с настройками
 */
class Init extends AbstractMigration
{
    /**
     * Константа IBLOCK_TYPE_ID
     * Задает ID типа инфоблока
     */
    const IBLOCK_TYPE_ID = 'lists_999';

    /**
     * Метод up
     * Применение миграции
     *
     * @throws Exception
     */
    public function up()
    {
        /** @global \CDatabase $DB */
        global $DB;

        try {

            // Подключает модуль для работы с инфоблоками
            Loader::includeModule('iblock');

            // Если тип инфоблока отсутствует - создать его
            if (!(CIBlockType::GetByID(self::IBLOCK_TYPE_ID)->GetNext())) {

                $arFields = Array(
                    'ID' => self::IBLOCK_TYPE_ID,
                    'SECTIONS' => 'N',
                    'LANG' => Array(
                        'ru' => Array(
                            'NAME' => 'Списки',
                            'ELEMENT_NAME' => 'Пользователи'
                        ),
                        'en' => Array(
                            'NAME' => 'Lists',
                            'ELEMENT_NAME' => 'Users'
                        )
                    )
                );

                $oIBlockType = new CIBlockType;
                $DB->StartTransaction();
                $oResult = $oIBlockType->Add($arFields);

                if (!$oResult) {
                    $DB->Rollback();
                } else {
                    $DB->Commit();
                }
            }

            // Если инфоблок отсутствует - создать его
            if (!(CIBlock::GetList(Array(), Array('XML_ID' => self::IBLOCK_TYPE_ID), true)->GetNext())) {
                $arFields = Array(
                    'IBLOCK_TYPE_ID' => self::IBLOCK_TYPE_ID,
                    'XML_ID' => self::IBLOCK_TYPE_ID,
                    'NAME' => 'Пользователи',
                    'CODE' => 'webinar_users',
                    'LIST_PAGE_URL' => '#SITE_DIR#/lists/index.php?ID=#IBLOCK_ID#',
                    'DETAIL_PAGE_URL' => '#SITE_DIR#/lists/detail.php?ID=#ELEMENT_ID#',
                    'SECTION_PAGE_URL' => '#SITE_DIR#/lists/list.php?SECTION_ID=#SECTION_ID#',
                    'SECTION_PROPERTY' => 'N',
                    'PROPERTY_INDEX' => 'N',
                    'SECTIONS_NAME' => 'Разделы',
                    'SECTION_NAME' => 'Раздел',
                    'ELEMENTS_NAME' => 'Пользователи',
                    'ELEMENT_NAME' => 'Пользователь',
                    'ACTIVE' => 'Y',
                    'SITE_ID' => Array('s1') //Массив ID сайтов
                );
                $obIblock = new CIBlock;
                $iIBlockID = $obIblock->Add($arFields);

                // Если инфоблок создан - добаляются свойства
                if ($iIBlockID) {
                    $arFields = Array(
                        'IBLOCK_ID' => $iIBlockID,
                        'NAME' => 'Дата рождения',
                        'ACTIVE' => 'Y',
                        'SORT' => '100',
                        'CODE' => 'birthday',
                        'PROPERTY_TYPE' => 'S',
                        'USER_TYPE' => 'Date',
                        'IS_REQUIRED' => 'Y'
                    );
                    $oIBlockProperty = new CIBlockProperty;
                    $PropID = $oIBlockProperty->Add($arFields);

                    $arFields = Array(
                        'IBLOCK_ID' => $iIBlockID,
                        'NAME' => 'Номер телефона',
                        'ACTIVE' => 'Y',
                        'SORT' => '200',
                        'CODE' => 'phone',
                        'PROPERTY_TYPE' => 'S',
                        'IS_REQUIRED' => 'Y'
                    );
                    $oIBlockProperty = new CIBlockProperty;
                    $PropID = $oIBlockProperty->Add($arFields);

                    $arFields = Array(
                        'IBLOCK_ID' => $iIBlockID,
                        'NAME' => 'Город',
                        'ACTIVE' => 'Y',
                        'SORT' => '300',
                        'CODE' => 'city',
                        'PROPERTY_TYPE' => 'L',
                        'MULTIPLE_CNT' => 5,
                        'IS_REQUIRED' => 'Y'
                    );
                    $arFields['VALUES'][0] = Array(
                        'VALUE' => 'Москва',
                        'DEF' => 'N',
                        'SORT' => '100',
                    );
                    $arFields['VALUES'][1] = Array(
                        'VALUE' => 'Санкт-Петербург',
                        'DEF' => 'N',
                        'SORT' => '200',
                    );
                    $arFields['VALUES'][2] = Array(
                        'VALUE' => 'Казань',
                        'DEF' => 'N',
                        'SORT' => '300',
                    );
                    $oIBlockProperty = new CIBlockProperty;
                    $PropID = $oIBlockProperty->Add($arFields);

                    // Заполняет список ID городов
                    $arEnumFields = array();
                    $oIBlockPropertyEnums = CIBlockPropertyEnum::GetList(Array('DEF' => 'DESC', 'SORT' => 'ASC'),
                        Array('PROPERTY_ID' => $PropID));
                    $i = 0;
                    while ($oEnumField = $oIBlockPropertyEnums->GetNext()) {
                        $arEnumFields[$i++] = $oEnumField['ID'];
                    }

                    // Создает несколько пользователей
                    $oElement = new CIBlockElement;

                    $arFields = Array(
                        'MODIFIED_BY' => $GLOBALS['USER']->GetID(),
                        'IBLOCK_ID' => $iIBlockID,
                        'NAME' => 'Иван',
                        'CREATED_BY' => $GLOBALS['USER']->GetID(),
                        'SEARCHABLE_CONTENT' => 'ИВАН',
                        'CODE' => 'ivan'
                    );
                    // Массив со свойствами для записи элемента
                    $arFields["PROPERTY_VALUES"] = Array(
                        'birthday' => '10.10.1980',
                        'phone' => '+78001234567',
                        'city' => $arEnumFields[0]
                    );
                    $oElementID = $oElement->Add($arFields);

                    $arFields = Array(
                        'MODIFIED_BY' => $GLOBALS['USER']->GetID(),
                        'IBLOCK_ID' => $iIBlockID,
                        'NAME' => 'Петр',
                        'CREATED_BY' => $GLOBALS['USER']->GetID(),
                        'SEARCHABLE_CONTENT' => 'ПЕТР',
                        'CODE' => 'petr'
                    );
                    // Массив со свойствами для записи элемента
                    $arFields["PROPERTY_VALUES"] = Array(
                        'birthday' => '11.11.1981',
                        'phone' => '+79001234567',
                        'city' => $arEnumFields[1]
                    );
                    $oElementID = $oElement->Add($arFields);

                    $arFields = Array(
                        'MODIFIED_BY' => $GLOBALS['USER']->GetID(),
                        'IBLOCK_ID' => $iIBlockID,
                        'NAME' => 'Евгений',
                        'CREATED_BY' => $GLOBALS['USER']->GetID(),
                        'SEARCHABLE_CONTENT' => 'ЕВГЕНИЙ',
                        'CODE' => 'evgeniy'
                    );
                    // Массив со свойствами для записи элемента
                    $arFields["PROPERTY_VALUES"] = Array(
                        'birthday' => '12.12.1982',
                        'phone' => '+79901234567',
                        'city' => $arEnumFields[2]
                    );
                    $oElementID = $oElement->Add($arFields);
                }
            }

        } catch (\Exception $e) {
        }
    }

    /**
     * Метод down
     * Откат миграции
     *
     * @throws Exception
     */
    public function down()
    {
        /** @global \CDatabase $DB */
        global $DB;

        try {
            // Подключает модуль для работы с инфоблоками
            Loader::includeModule('iblock');

            // Если инфоблок существует - удалить его
            while ($oIBlock = CIBlock::GetList(Array(), Array('XML_ID' => self::IBLOCK_TYPE_ID), true)->GetNext()) {
                $iIBlockID = (int)$oIBlock['ID'];
                $DB->StartTransaction();
                if (CIBlock::Delete($iIBlockID)) {
                    $DB->Commit();
                } else {
                    $DB->Rollback();
                }
            }

            // Если тип инфоблока существует - удалить его
            if (CIBlockType::GetByID(self::IBLOCK_TYPE_ID)->GetNext()) {
                $DB->StartTransaction();
                if (CIBlockType::Delete(self::IBLOCK_TYPE_ID)) {
                    $DB->Commit();
                } else {
                    $DB->Rollback();
                }
            }
        } catch (\Exception $e) {
        }
    }
}
