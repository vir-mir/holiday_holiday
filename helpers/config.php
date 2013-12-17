<?php
/**
 * Created by PhpStorm.
 * User: vir-mir
 * Date: 20.11.13
 * Time: 13:45
 */


return array(
    'template' => array(
        'folder' => 'template/site',
        'template' => 'twig', // twig
    ),

    'helpers' => 'debug,fn',

    'header' => array(
        "Content-Type: text/html; charset=utf-8",
    ),

    'database' => array( // для работы измените _database на database
        'hostname' => 'wp',
        'username' => 'root',
        'pwd' => '',
        'dbname' => 'holiday_holiday',
    ),

    'stopFn' => array(
        'load',
        'loadTemplate',
        'loadTemplateTwig',
        'loadTemplateHtml',
        '_setHeaders',
        'load403',
        'load404',
    ),

    404 => '404.twig',
    403 => '403.twig',

);