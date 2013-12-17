<?php
/**
 * Created by PhpStorm.
 * User: vir-mir
 * Date: 20.11.13
 * Time: 18:31
 */

namespace models;

use library\Model;
use library\Singleton;

class Holiday_Model extends Model {

    public function __construct(){
        parent::__construct();
    }


    public function getAllHoliday() {
        $sql = "
            select
                h.id,
                h.`name`,
                date(concat(
                    YEAR (NOW()),
                    '-',
                    h.`month`,
                    '-',
                    h.`day`
                ))  as dd
            from
                holiday h
        ";

        $res = Singleton::getKernel()->db->query($sql);
        if ($res) {
            $ret = array();
            while ($obj = $res->fetchObject()) {
                $ret[] = $obj;
            }

            return $ret;
        }

        return array();
    }

} 