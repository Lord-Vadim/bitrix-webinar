<?php

/**
 * Created by PhpStorm.
 * User: Vadim Epifanov
 * Date: 13.08.2018
 * Time: 15:51
 */

namespace YLab\Validation\Components;

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\LoaderException;
use CIBlock;
use CIBlockElement;
use CIBlockPropertyEnum;
use CIBlockType;
use CUtil;
use YLab\Validation\ComponentValidation;
use YLab\Validation\ValidatorHelper;
use Bitrix\Main\Loader;

/**
 * Класс UsersFormComponent
 * Выводит список пользователей из инфоблока "Пользователи"
 * И форму добавления новых пользователей с валидацией
 *
 * @package YLab\Validation\Components
 */
class UsersFormComponent extends ComponentValidation
{
    /**
     * Константа IBLOCK_TYPE_ID
     * Задает ID типа инфоблока
     */
    const IBLOCK_TYPE_ID = 'lists_999';

    /**
     * Свойство $iBlockID
     * iBlockID инфоблока
     *
     * @var
     */
    public static $iBlockID;

    /**
     * Свойство $iBlockEnumPropertyID
     * Property_ID списка городов ENUM инфоблока
     *
     * @var
     */
    public static $iBlockEnumPropertyID;

    /**
     * Свойство $arResultUsers
     * Архив для хранения списока пользователей
     *
     * @var array
     */
    public $arResultUsers = array();

    /**
     * Свойство $arResultCity
     * Архив c данными для выпадающего списка городов
     *
     * @var array
     */
    public $arResultCity = array();

    /**
     * UsersFormComponent constructor.
     *
     * @param \CBitrixComponent|null $component
     * @param string $sFile
     * @throws \Bitrix\Main\IO\InvalidPathException
     * @throws \Bitrix\Main\SystemException
     * @throws \Exception
     */
    public function __construct(\CBitrixComponent $component = null, $sFile = __FILE__)
    {
        parent::__construct($component, $sFile);

        // Подключает модуль для работы с инфоблоками
        Loader::includeModule('iblock');
    }

    /**
     * Метод executeComponent
     * вызывается при создании компонента
     *
     * @return mixed|void
     * @throws \Exception
     */
    public function executeComponent()
    {
        /** @global \CMain $APPLICATION */
        global $APPLICATION;

        // Очищает буфер
        $APPLICATION->RestartBuffer();

//        $this->test();

        // Получение iBlockID
        self::$iBlockID = $this->getIBlockID();

        // Получение iBlockEnumPropertyID
        self::$iBlockEnumPropertyID = $this->getIBlockEnumPropertyID();

        // Архив для хранения списока пользователей
        $this->arResultUsers = $this->getUsersList();

        // Архив c данными для выпадающего списка городов
        $this->arResultCity = $this->getCityList();

        // Непосредственно валидация и действия при успехе и фейле
        if ($this->oRequest->isPost() && check_bitrix_sessid()) {
            $this->oValidator->setData($this->oRequest->toArray());

            if ($this->oValidator->passes()) {
                $this->arResult['SUCCESS'] = true;

                // Добавление пользователя в инфоблок
                $this->addUser($this->oRequest->toArray());
                // Обновление содержимого архива списока пользователей
                $this->arResultUsers = $this->getUsersList();

            } else {
                $this->arResult['ERRORS'] = ValidatorHelper::errorsToArray($this->oValidator);
            }
        }

        // Вызов шаблона
        $this->includeComponentTemplate();
    }

    /**
     * Метод addUser
     * Добавляет нового пользователя в инфоблок
     *
     * @param $arData
     */
    protected function addUser($arData)
    {
        try {
            $oElement = new CIBlockElement;

            // Параметры для транслитерации
            $arParamsTranslit = array("replace_space" => "_", "replace_other" => "_");

            // Данные пользователя
            $sUserName = $arData['user_name'];
            $sUserNameUpperCase = mb_strtoupper($arData['user_name']);
            $sUserNameTranslit = Cutil::translit($arData['user_name'], "ru", $arParamsTranslit);
            $sUserBirthday = $arData['birthday'];
            $sUserPhone = $arData['tel'];
            $sUserCity = (int)$arData['city'];

            // Массив с полями для записи элемента
            $arFields = Array(
                'MODIFIED_BY' => $GLOBALS['USER']->GetID(),
                'IBLOCK_ID' => self::$iBlockID,
                'NAME' => $sUserName,
                'CREATED_BY' => $GLOBALS['USER']->GetID(),
                'SEARCHABLE_CONTENT' => $sUserNameUpperCase,
                'CODE' => $sUserNameTranslit
            );

            // Массив со свойствами для записи элемента
            $arFields["PROPERTY_VALUES"] = Array(
                'birthday' => $sUserBirthday,
                'phone' => $sUserPhone,
                'city' => $sUserCity
            );

            $oElementID = $oElement->Add($arFields);

        } catch (\Exception $e) {
            // Возникла ошибка
            return array('Exception: ' . $e->getMessage());
        }
    }

    /**
     * Метод rules
     * Возвращает массив правил валидации
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'user_name' => 'required',
            'birthday' => 'required|date_format:d.m.Y',
            'tel' => 'required|regex:/[+][7][0-9]{10}/',
            'city' => 'required|numeric'
        ];
    }

    /**
     * Метод getUsersList
     * возвращает список пользователей из инфоблока Пользователи
     *
     * @return array
     */
    protected function getUsersList()
    {
        // Временный массив для заполнения списка
        $arTemp = array();

        try {
            // Получает элементы инфоблока (IBLOCK_ID = 1) используя фильтр
            $oResult = ElementTable::getList(
                array(
                    'filter' => array('IBLOCK_ID' => self::$iBlockID),
                    'select' => array('ID', 'NAME')
                ));

            // Перебор всех полученных элементов и заполнение массива
            while ($oElement = $oResult->fetch()) {

                $iElementID = (int)$oElement['ID'];

                $sDate = CIBlockElement::GetProperty(
                    self::$iBlockID,
                    $iElementID,
                    array('sort' => 'asc'),
                    Array('CODE' => 'birthday')
                )->GetNext()['VALUE'];

                $sPhone = CIBlockElement::GetProperty(
                    self::$iBlockID,
                    $iElementID,
                    array('sort' => 'asc'),
                    Array('CODE' => 'phone')
                )->GetNext()['VALUE'];

                $sCity = CIBlockElement::GetProperty(
                    self::$iBlockID,
                    $iElementID,
                    array('sort' => 'asc'),
                    Array('CODE' => 'city')
                )->GetNext()['VALUE_ENUM'];

                $arTemp[] = array(
                    'ID' => $iElementID,
                    'NAME' => $oElement['NAME'],
                    'BIRTHDAY' => $sDate,
                    'PHONE' => $sPhone,
                    'CITY' => $sCity
                );
            }

            return $arTemp;

        } catch (\Exception $e) {
            // Возникла ошибка
            return array('Exception: ' . $e->getMessage());
        }
    }

    /**
     * Метод getCityList
     * Возвращает список городов для выпадающего списка
     *
     * @return array
     */
    protected function getCityList()
    {
        // Временный массив для заполнения списка
        $arTemp = array();

        try {
            // Заполняет список ID городов
            $oIBlockPropertyEnums = CIBlockPropertyEnum::GetList(Array('DEF' => 'DESC', 'SORT' => 'ASC'),
                Array('PROPERTY_ID' => self::$iBlockEnumPropertyID));
            $i = 0;
            while ($oEnumField = $oIBlockPropertyEnums->GetNext()) {
//                $arTemp[$i] = '<option value="' . $oEnumField['ID'] . '">' . $oEnumField['VALUE'] . '</option>';
                $arTemp[$i]['ID'] = (int)$oEnumField['ID'];
                $arTemp[$i]['CITY'] = $oEnumField['VALUE'];
                $i++;
            }

            return $arTemp;

        } catch (\Exception $e) {
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
            if ($oIBlock = CIBlock::GetList(Array(), Array('XML_ID' => self::IBLOCK_TYPE_ID), true)->GetNext()) {
                return (int)$oIBlock['ID'];
            } else {
                return 0;
            }
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Метод getIBlockEnumPropertyID
     * Возвращает Property_ID ENUM списка инфоблока
     *
     * @return integer
     */
    protected function getIBlockEnumPropertyID()
    {
        try {
            if ($oIBlock = CIBlockPropertyEnum::GetList(Array(),
                Array('IBLOCK_ID ' => self::IBLOCK_TYPE_ID))->GetNext()) {
                return (int)$oIBlock['PROPERTY_ID'];
            } else {
                return 0;
            }
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Метод test
     * Тестовый метод для экспериментов
     */
    protected function test()
    {
        /** @global \CDatabase $DB */
        global $DB;

        try {
            // Подключает модуль для работы с инфоблоками
            Loader::includeModule('iblock');

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
