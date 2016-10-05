<?php
/**
 * Created by PhpStorm.
 * User: Alan
 * Date: 2016/10/4 0004
 * Time: 18:01
 */
session_start();
require 'Captcha.php';
$captcha = new Captcha(array(
    'width' => 160,
    'height' => 60,
    'type' => 2,
//    'save'=>true
));
$cap_cont = $captcha->get_cap_cont();
$_SESSION['authentication_code'] = $cap_cont;
//print_r($_SESSION);

$captcha->show('image/jpeg');

//exit();

