<?php

/**
 * Created by PhpStorm.
 * User: Vadim Epifanov
 * Date: 10.08.2018
 * Time: 16:27
 */

use Bitrix\Main\Loader;

require_once(dirname(__FILE__) . '/../vendor/autoload.php');

try{
    Loader::includeModule('ylab.validation');
}  catch (\Exception $e){
    echo 'Error: ' . $e->getMessage();
}
