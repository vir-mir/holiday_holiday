<?php
/**
 * Created by PhpStorm.
 * User: vir-mir
 * Date: 20.11.13
 * Time: 13:32
 */

namespace controllers;

use library\Controller;
use models\User_Model;
use models\Holiday_Model;

class User extends Controller{

    public function index() {
        $modelUser = new User_Model();
        $modelHoliday = new Holiday_Model();
        $this->loadTemplate('user/main.twig', array(
            'users' => $modelUser->getAllUser(),
            'holidays' => $modelHoliday->getAllHoliday(),
        ));
    }

    public function filter() {
        $modelUser = new User_Model();
        $users = array();

        if ($_POST['if'] == '||') {
            if (to_int($_POST['not_congratulated']) > 0) {
                $users = array_merge($users, $modelUser->getAllUserNotHoliday(to_int($_POST['not_congratulated'])));
            }
            if (to_int($_POST['congratulated']) > 0) {
                $users = array_merge($users, $modelUser->getAllUserHoliday(to_int($_POST['congratulated'])));
            }
        } elseif ($_POST['if'] == '&&' && to_int($_POST['not_congratulated']) > 0 && to_int($_POST['congratulated']) > 0) {
            $users = $modelUser->getAllUserHolidayAndNot(to_int($_POST['not_congratulated']), to_int($_POST['congratulated']));
        }


        if ( !(to_int($_POST['not_congratulated']) > 0) && !(to_int($_POST['congratulated']) > 0)) {
            $users = $modelUser->getAllUser();
        }

        $this->loadTemplate(json_encode(array('users' => $users)), array(), array('ajax' => true));
    }

    public function replace($get) {
        $id = to_int(array_shift($get));

        $user = array('id' => $id);

        $modelUser = new User_Model();
        $errors = array();
        $messengs = array();

        if ($id > 0 && !$modelUser->hasUser($id)) http404();
        else $user = $modelUser->getUserId($id);

        if (!empty($_POST)) {
            load('user');
            $user = $_POST;
            if ($id > 0 && isset($_POST['email'])) unset($_POST['email']);
            $errors = validFormUser($_POST);
            if (!$errors) {
                if ($modelUser->replaceUser($_POST) > 0) {
                    $user = array();
                    array_push($messengs, 'Запись успешно добавлена!');
                } else {
                    array_push($errors, 'Ошибка вставки в БД!');
                }
            }
        }

        $ex = array(
            'user' => $user,
            'errors' => $errors,
            'messengs' => $messengs,
        );

        $this->loadTemplate('user/replace.twig', $ex);
    }

    public function replace_ajax($get) {
        $id = to_int(array_shift($get));
        $modelUser = new User_Model();
        $errors = array();
        $messengs = array();

        if ($id > 0 && !$modelUser->hasUser($id)) {
            array_push($errors, 'нет такого клиента!');
        } else {
            if (!empty($_POST)) {
                load('user');
                if ($id > 0 && isset($_POST['email'])) unset($_POST['email']);
                $errors = validFormUser($_POST);
                if (!$errors) {
                    if ($modelUser->replaceUser($_POST) > 0) {
                        array_push($messengs, 'Запись успешно добавлена!');
                    } else {
                        array_push($errors, 'Ошибка вставки в БД!');
                    }
                }
            }
        }

        $ex = $messengs?array('messengs'=>$messengs):array('errors'=>$errors);

        $this->loadTemplate(json_encode($ex), array(), array('ajax' => true));
    }

} 