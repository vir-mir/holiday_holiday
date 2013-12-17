<?php
/**
 * Created by PhpStorm.
 * User: vir-mir
 * Date: 20.11.13
 * Time: 12:44
 */

namespace library;


class Singleton {
    private function __construct() {}
    private function __clone() {}

    /**
     * @var \library\Kernel
     */
    private static $kernel;

    /**
     * @return \library\Kernel
     */
    public static function getKernel() {
        if (!self::$kernel) {
            self::$kernel = new \library\Kernel();
        }

        return self::$kernel;
    }

} 