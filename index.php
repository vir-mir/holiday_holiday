<?php
/**
 * Created by PhpStorm.
 * User: vir-mir
 * Date: 20.11.13
 * Time: 12:18
 */

error_reporting(E_ALL);

use library\Singleton;

define('__MAINROOT__', dirname(__FILE__));


function __autoload($name) {
    $name = mb_strtolower($name);
    $name = str_replace('\\', DIRECTORY_SEPARATOR, $name);

    $file = __MAINROOT__ . DIRECTORY_SEPARATOR . $name . '.php';

    if (file_exists($file))
        require_once $file;

}

Singleton::getKernel()->run();


