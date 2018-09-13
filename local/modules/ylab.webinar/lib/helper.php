<?php
/**
 * Created by PhpStorm
 * User: Vadim Epifanov
 * Date: 22.08.2018
 * Time: 99:21
 *
 * Хотел сделать в Helper общую функцию получения языковых констант для модуля и компонентов
 * из одного файла, но тогда локализация компонентов не поддерживается из админки.
 * Пришлось отказаться. Больше не смог придумать, что бы еще можно было для данного
 * модуля запихнуть в хелпер, но суть - понятна.
 * Код хелпера и относящиеся к нему языковые файлы убирать не стал, хотя в модуле они не используются.
 * Может быть концепция расположения языковых констант в одном файле, вместо раскидывания их по
 * десяткам отдельных файлов когда ни будь пригодится.
 */

namespace YLab\Webinar;

use Bitrix\Main\Localization\Loc;

/**
 * Class Helper
 * содержит набор общих функций:
 * - getMessage($sType) - возвращает сообщение в зависимости от передаваемого параметра.
 *
 * @package YLab\Webinar
 */
class Helper
{
    /**
     * Метод getMessage
     * возвращает сообщение в зависимости от передаваемого параметра.
     * Доступные параметры можно посмотреть:
     * - modules/ylab.webinar/lang/en/lib/helper.php
     * - modules/ylab.webinar/lang/ru/lib/helper.php
     *
     * @param $sCode
     * @return string
     */
    public static function getMessage($sCode)
    {
        Loc::loadLanguageFile(__FILE__);

        switch ($sCode) {
            case 'HOMEWORK':
                return '<h2 class="header-3">' . Loc::getMessage('HOMEWORK') . '</h2>';
            case 'ERROR':
                return '<h3>' . Loc::getMessage('ERROR') . '</h3>';
            default:
                return Loc::getMessage($sCode);
        }
    }
}
