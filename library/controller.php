<?php
/**
 * Created by PhpStorm.
 * User: vir-mir
 * Date: 20.11.13
 * Time: 13:39
 */

namespace library;

use library\Singleton;
use library\DB;


class Controller {

    public function __construct() {
        new DB();
    }

    public function load($fn, $param) {
        if (in_array($fn, Singleton::getKernel()->getConfig('stopFn'))) {
            $this->load404();
        } else {
            $this->$fn($param);
        }
    }

    public function load404() {
        $this->loadTemplate(Singleton::getKernel()->getConfig(404), array());
    }

    public function load403() {
        $this->loadTemplate(Singleton::getKernel()->getConfig(403), array());
    }

    protected function loadTemplateTwig($templateName, &$paramExternal, &$paramTemplate) {
        require_once __MAINROOT__ . DIRECTORY_SEPARATOR . 'library/Twig/Autoloader.php';

        $template = & Singleton::getKernel()->getConfig('template');

        \Twig_Autoloader::register();

        if (isset($paramTemplate['ajax']) && $paramTemplate['ajax']===true) {
            $loader = new \Twig_Loader_String();
        } else {
            $loader = new \Twig_Loader_Filesystem(__MAINROOT__ . DIRECTORY_SEPARATOR . $template['folder']);
        }
        $twig = new \Twig_Environment($loader);

        echo $twig->render($templateName, $paramExternal);
    }

    protected function loadTemplate($templateName, $paramExternal = array(), $paramTemplate = array()) {

        $template = & Singleton::getKernel()->getConfig('template');

        $this->_setHeaders($paramTemplate);

        if ($template['template'] == 'twig') {
            // twig шаблонизация
            $this->loadTemplateTwig($templateName, $paramExternal, $paramTemplate);
        }

    }

    private function _setHeaders(&$paramTemplate) {
        foreach (isset($paramTemplate['header'])?$paramTemplate['header']:Singleton::getKernel()->getConfig('header') as $v) {
            header($v);
        }
    }

} 