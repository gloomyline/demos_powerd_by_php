<?php
/**
 * Created by PhpStorm.
 * User: Alan
 * Date: 2016/10/4 0004
 * Time: 18:01
 */

require 'Captcha.php';
$captcha = new Captcha(array(
    'width' => 160,
    'height' => 80,
    'type' => 2,
//    'save'=>true
));
$cap_cont = $captcha->get_cap_cont();
$captcha->show('image/jpeg');

