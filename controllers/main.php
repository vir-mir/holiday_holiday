<?php
/**
 * Created by PhpStorm.
 * User: vir-mir
 * Date: 20.11.13
 * Time: 13:32
 */

namespace controllers;

use library\Controller;

class Main extends Controller{

    public function index() {
        $this->loadTemplate('main/main.twig');
    }

} 