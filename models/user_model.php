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

class User_Model extends Model {


    /**
     * @param string $email
     * @return bool
     */
    public static function hasEmailUser($email) {
        $email = quote_smart($email);
        $sql = "select * from `user` where email = {$email}";
        return !(Singleton::getKernel()->db->query($sql)->rowCount() > 0);
    }


    public function __construct(){
        parent::__construct();
    }


    /**
     * @param int $id
     * @return bool
     */
    public function hasUser($id) {
        $sql = "select * from `user` where id = {$id}";
        $res = Singleton::getKernel()->db->query($sql);
        return $res ? ($res->rowCount()>0) : false;
    }


    public function getAllUser() {
        $res = Singleton::getKernel()->db->query('select * from `user`');
        if ($res) {
            $ret  = array();
            while ($ret[] = $res->fetchObject());
            array_pop($ret);
            return $ret;
        }
        return false;
    }


    public function getUserId($id) {
        $res = Singleton::getKernel()->db->query("select * from `user` where id = {$id}");
        return $res?$res->fetchObject():false;
    }


    public function getAllUserNotHoliday($id_holiday) {
        $sql = "
            select
                c.*
            from
                `user` c
                left join `congratulation` co on
                    c.`id` = co.`id_user`
                    and co.`id_holiday` = {$id_holiday}
                    and YEAR(co.`dd`) = YEAR(NOW())
                left join `holiday` h on h.`id` = {$id_holiday}
            where
	            co.`id_user` is null
	            and (h.sex = 'all' or h.sex = c.sex)
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


    public function getAllUserHoliday($id_holiday) {
        $sql = "
            select
                c.*
            from
                `user` c
                inner join `congratulation` co on
                    c.`id` = co.`id_user`
                    and co.`id_holiday` = {$id_holiday}
                    and YEAR(co.`dd`) = YEAR(NOW())
                left join `holiday` h on h.`id` = {$id_holiday}
            where
                h.sex = 'all' or h.sex = c.sex
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


    public function getAllUserHolidayAndNot($id_notHoliday, $id_holiday) {
        $sql = "
            select
                cl.*
            from
                `user` cl
                inner join `congratulation` co on co.`id_user` = cl.id and co.`id_holiday` = {$id_holiday}
                left join `congratulation` co2 on co2.`id_user` = cl.id and co2.`id_holiday` = {$id_notHoliday}
                left join `holiday` h on h.`id` = {$id_holiday}
                left join `holiday` h1 on h1.`id` = {$id_notHoliday}
            where
                (h.`sex` = 'all' or h1.sex = cl.`sex`)
                and (h1.`sex` = 'all' or h1.sex = cl.`sex`)
                and co2.`id_user` is null
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


    /**
     * @param array $param
     * @return bool|int
     */
    public function replaceUser($param) {

        $sqlList = array();

        if (isset($param['fio'])) {
            $fio = quote_smart($param['fio']);
            array_push($sqlList, " `fio` = {$fio} ");
        }

        if (isset($param['email'])) {
            $email = quote_smart($param['email']);
            array_push($sqlList, " `email` = {$email} ");
        }

        if (isset($param['adress'])) {
            $adress = quote_smart($param['adress']);
            array_push($sqlList, " `adress` = {$adress} ");
        }

        if (isset($param['sex'])) {
            $sex = quote_smart($param['sex']);
            array_push($sqlList, " `sex` = {$sex} ");
        }

        $id = isset($param['id'])?to_int($param['id']):0;

        $sqlList = implode(' , ', $sqlList);

        $sql = ($id == 0)?"insert into `user` set {$sqlList}":"update `user` set $sqlList where id = {$id}";

        if (Singleton::getKernel()->db->query($sql)) {
            return ($id == 0)?to_int(Singleton::getKernel()->db->lastInsertId()):$id;
        }

        return false;

    }

} 