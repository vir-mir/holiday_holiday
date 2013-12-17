<?php
/**
 * Created by PhpStorm.
 * User: vir-mir
 * Date: 20.11.13
 * Time: 12:33
 */

function p(){$a = func_get_args();echo '<pre>';foreach($a as $v){var_dump($v);}echo '</pre>';}
function t(){$a = func_get_args();echo '<pre>';foreach($a as $v){var_dump($v);}echo '</pre>';die();}