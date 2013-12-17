<?php
/**
 * Created by PhpStorm.
 * User: vir-mir
 * Date: 20.11.13
 * Time: 18:48
 */


function validFormUser($param) {
    $errors = array();

    if (trim($param['fio'], " \n\t\r")=='') {
        array_push($errors, "Заполните поле ФИО!");
    }

    if (!isset($param['sex'])) {
        array_push($errors, "Не выбран пол!");
    }

    if (to_int($param['id']) == 0) {
        if (!validEmail($param['email'])) {
            array_push($errors, "Неправильно заполненно поле E-mail!");
        } elseif (!\models\User_Model::hasEmailUser($param['email'])) {
            array_push($errors, "такой email уже существует!");
        }
    }

    if (trim($param['adress'], " \n\t\r")=='') {
        array_push($errors, "Заполните поле Адрес!");
    }

    return $errors;
}