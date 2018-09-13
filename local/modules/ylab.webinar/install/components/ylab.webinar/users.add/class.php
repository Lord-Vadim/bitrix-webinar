<?php
/**
 * Created by PhpStorm
 * User: Vadim Epifanov
 * Date: 19.08.2018
 * Time: 08:51
 */

namespace YLab\Webinar;

use YLab\Validation\ComponentValidation;
use YLab\Validation\ValidatorHelper;
use Bitrix\Main\Loader;

/**
 * Класс UsersAddComponent
 * выводит форму добавления новых пользователей в ORM список с валидацией.
 *
 * @package YLab\Webinar
 */
class UsersAddComponent extends ComponentValidation
{
    /**
     * Свойство $arResultCity
     * архив c данными для выпадающего списка городов.
     *
     * @var array
     */
    public $arResultCity = [];

    /**
     * UsersAddComponent constructor.
     *
     * @param \CBitrixComponent|null $component
     * @param string $sFile
     * @throws \Bitrix\Main\IO\InvalidPathException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\SystemException
     */
    public function __construct(\CBitrixComponent $component = null, $sFile = __FILE__)
    {
        parent::__construct($component, $sFile);

        // Подключает модуль для работы с ORM таблицами
        Loader::includeModule('ylab.webinar');
    }

    /**
     * Метод executeComponent
     * вызывается при создании компонента.
     *
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentException
     */
    public function executeComponent()
    {
        // Архив c данными для выпадающего списка городов
        $this->arResultCity = CityTable::getList();

        // Непосредственно валидация и действия при успехе и ошибке
        if ($this->oRequest->isPost() && check_bitrix_sessid()) {
            $this->oValidator->setData($this->oRequest->toArray());

            if ($this->oValidator->passes()) { // Валидация пройдена
                $this->arResult['SUCCESS'] = true;
                $this->arResult['USER'] = $this->oRequest->toArray()['user_name'];

                // Добавление пользователя в список
                $this->addUser($this->oRequest->toArray());


            } else { // Ошибка валидации
                $this->arResult['ERRORS'] = ValidatorHelper::errorsToArray($this->oValidator);
                $this->arResult['NAME'] = $this->oRequest->toArray()['user_name'];
                $this->arResult['BIRTHDAY'] = $this->oRequest->toArray()['birthday'];
                $this->arResult['TEL'] = $this->oRequest->toArray()['tel'];
                $this->arResult['CITY'] = $this->oRequest->toArray()['city'];
            }
        }

        // Вызов шаблона
        $this->includeComponentTemplate();
    }

    /**
     * Метод addUser
     * добавляет нового пользователя в список.
     *
     * @param $arData
     */
    protected function addUser($arData)
    {
        UsersTable::addUser(
            $arData['user_name'],
            $arData['birthday'],
            $arData['tel'],
            $arData['city']
        );
    }

    /**
     * Метод rules
     * возвращает массив правил валидации.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'user_name' => 'required',
            'birthday' => 'required|date_format:d.m.Y',
            'tel' => 'required|regex:/[+][7][0-9]{10}/',
            'city' => 'required'
        ];
    }
}
