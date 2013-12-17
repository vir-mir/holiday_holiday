<?php
/**
 * Created by PhpStorm.
 * User: vir-mir
 * Date: 20.11.13
 * Time: 19:39
 */

namespace controllers;

use library\Controller;
use library\Xml;
use models\User_Model;
use models\Holiday_Model;

class Export extends Controller{

    public function xml() {
        $userObj = new User_Model();
        $holidayObj = new Holiday_Model();
        $xml = new Xml();


        $holidays = array();
        foreach ($holidayObj->getAllHoliday() as $holiday) {
            $holiday = to_array($holiday);
            $usersNotHoliday = $userObj->getAllUserNotHoliday($holiday['id']);
            $usersHoliday = $userObj->getAllUserHoliday(($holiday['id']));
            $dop = null;
            if ($usersNotHoliday)
                $dop .= $xmlText = $xml->setNods('client_not_holidays', $usersNotHoliday);

            if ($usersHoliday)
                $dop .= $xmlText = $xml->setNods('client_holidays', $usersHoliday);

            $holiday['clients'] = $dop;

            $holidays[] = $holiday;
        }

        $xmlText = '<?xml version="1.0" encoding="utf-8"?><root>';
        $xmlText .= $xml->setNods('holidays', $holidays).'</root>';

        $header = array("Content-type: text/xml; charset=utf-8");

        $this->loadTemplate($xmlText, array(), array('ajax'=>true, 'header'=>$header));
    }

} 