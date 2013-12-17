<?php
/**
 * Created by PhpStorm.
 * User: vir-mir
 * Date: 20.11.13
 * Time: 12:42
 */

namespace library;


class Kernel {

    private $_controller = 'main';
    private $_controllerFunction = 'index';
    private $_controllerParam = array();
    private $_config = array();

    /**
     * @var \PDO
     */
    public $db = null;

    private function __clone() {}

    private function _loadHelper($name) {
        if (!defined('__MAINROOT__'))
            define('__MAINROOT__', dirname(__FILE__));

        $file = __MAINROOT__ . '/' . 'helpers' . '/' . $name . '.php';

        if (file_exists($file))
            return require_once __MAINROOT__ . '/' . 'helpers' . '/' . $name . '.php';
    }

    private function _router() {
        $q = trim(preg_replace("~\?.*~", '', $_SERVER['REQUEST_URI']), "/ \n\r\t");
        if ($q) {
            $list = explode('/', $q);
            $controller = trim(array_shift($list), " \t\n\r");
            $controllerFunction = '';

            if ($controller!='') $this->_controller = $controller;

            if (isset($list[0])) {
                $controllerFunction = trim(array_shift($list), " \t\n\r");
            }

            if ($controllerFunction!='') {
                $this->_controllerFunction = $controllerFunction;
            }

            $this->_controllerParam = $list;

        }
    }

    private function _controller() {
        $class = '\\controllers\\' . ucfirst($this->_controller);
        if (class_exists($class)) {
            $obj = new $class();
            if (method_exists($obj, $this->_controllerFunction)) {
                $obj->load($this->_controllerFunction, $this->_controllerParam);
            } else {
                http404();
            }
        } else {
            http404();
        }

    }

    public function run() {
        $this->_config = $this->_loadHelper('config');
        $this->loadHelpers($this->_config['helpers']);
        $this->_router();
        $this->_controller();
    }

    public function loadHelpers($names) {
        $names = explode(',', $names);
        foreach ($names as $name) {
            $this->_loadHelper($name);
        }
    }

    public function & getConfig($name = null) {
        $null = null;
        if ($name)
            if (isset($this->_config[$name])) return $this->_config[$name];
            else return $null;

        return $this->_config;
    }




} 