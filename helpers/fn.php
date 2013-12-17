<?php
/**
 * Created by PhpStorm.
 * User: vir-mir
 * Date: 20.11.13
 * Time: 12:33
 */

/**
 * Функция окавычивает данные в sql запросы
 *
 * @param string $val
 * @return string
 *  !Внимание использовать, только когда уже есть соединение с БД.
 */
function quote_smart( $val) {
    $val = \library\Singleton::getKernel()->db->quote($val);
    return $val;
}

function validEmail($email){
    if (preg_match('~^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$~', $email)){
        return true;
    }
    else {
        return false;
    }
}

function isValidEmail($email){
    if (preg_match('~^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$~', $email)){
        return true;
    }
    else {
        return false;
    }
}

function http404() {
    header("HTTP/1.0 404 Not Found");
    $ct = new \library\Controller();
    $ct->load404();
    exit;
}

function http403() {
    header('HTTP/1.0 403 Forbidden');
    $ct = new \library\Controller();
    $ct->load403();
    exit;
}

/**
 * Загрузка Хелперов
 *
 * @param string $name
 */
function load($name) {
    return \library\Singleton::getKernel()->loadHelpers($name);
}

/**
 * преобразование к типу string
 *
 * @param mixed $val
 * @return str
 */
function to_str( $val) {
    if ( is_array( $val ) || is_object( $val ) ) {
        return "";
    }
    $val = strval( $val );
    return $val;
}


/**
 * преобразование к типу int
 *
 * @param mixed $val
 * @return int
 */
function to_int( $val ) {
    if ( is_int( $val ) ) {
        return $val;
    }
    $num = intval( $val );
    if ( strval( $num ) != $val ) {
        return 0;
    }
    return $num;
}

/**
 * преобразование к типу float
 *
 * @param mixed $val
 * @return float
 */
function to_float( $val ) {
    return floatval( $val );
}

/**
 * преобразование к типу bool
 *
 * @param mixed $val
 * @return bool
 */
function to_bool( $val ) {
    if ( is_bool( $val ) ) {
        return $val;
    }
    if ( $val == 1 ) {
        return true;
    }
    return false;
}

/**
 * преобразование к типу array
 *
 * @param mixed $val
 * @return array
 */
function to_array( $val ) {
    if ( is_array( $val ) ) {
        return $val;
    } else {
        return (array)$val;
    }
    return array( );
}