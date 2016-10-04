<?php
/**
 * Created by PhpStorm.
 * User: Alan
 * Date: 2016/10/4 0004
 * Time: 10:15
 */
require 'Image.php';
$img = new Image('resources/imgs/garden_girl.jpg');
$pos = array(
    'x' => 400,
    'y' => 200
);
$pos1 = array(
    'x' => 250,
    'y' => 300
);

$color = array(
    'r' => 255,
    'g' => 0,
    'b' => 0,
    'a' => 60
);

$size = array(
    'width' => 640,
    'height' => 480
);
$mark_resource = 'resources/imgs/style_fresh.png';
$img->textmark('Hello,world', 32, 25, $pos, $color);
$img->imgmark($mark_resource, $pos1, 10);
$img->thumb($size);
$img->show();

//$test->save('beauty');