<?php
/**
 * Created by PhpStorm.
 * User: Alan
 * Date: 2016/10/5 0005
 * Time: 10:27
 */
$str_cn = '今天是假期第四天了计划完成度还未过半剩下三天需要加油啦';
$str_arr = str_split($str_cn, 3);
print_r($str_arr);
$str_rand = '';
for ($i = 0; $i < 4; $i++) {
    $index = array_rand($str_arr, 1);
    $str_rand .= $str_arr[$index];
}
print_r($str_rand);