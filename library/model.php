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


class Model {

    public function __construct() {
        new DB();
    }


} 