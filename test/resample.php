<?php
/**
 * Created by PhpStorm.
 * User: Alan
 * Date: 2016/10/4 0004
 * Time: 09:48
 */
$des_img = imagecreatetruecolor(300, 300);
$src_filename = '../resources/imgs/garden_girl.jpg';
$src_img = imagecreatefromjpeg($src_filename);
$info = getimagesize($src_filename);
imagecopyresampled($des_img, $src_img, 0, 0, 0, 0, 300, 300, $info[0], $info[1]);
header('content-type:' . $info['mime']);
imagejpeg($des_img);
imagedestroy($des_img);
imagedestroy($src_img);