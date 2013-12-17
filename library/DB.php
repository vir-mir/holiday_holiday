<?php
/**
 * Created by PhpStorm.
 * User: vir-mir
 * Date: 20.11.13
 * Time: 14:14
 */

namespace library;

use PDO;
use PDOException;


class DB {

    public function __construct() {
        if (Singleton::getKernel()->db) return ;

        $database = & Singleton::getKernel()->getConfig('database');
        if (!$database) return ;
        
        $dsn = "mysql:dbname={$database['dbname']};host={$database['hostname']}";

        try {
            $db = new PDO($dsn, $database['username'], $database['pwd']);
            Singleton::getKernel()->db = $db;
        } catch (PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
        }
    }

} 