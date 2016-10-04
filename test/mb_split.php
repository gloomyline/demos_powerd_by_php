<?php
/**
 * Created by PhpStorm.
 * User: Alan
 * Date: 2016/10/4 0004
 * Time: 19:12
 */

$str = '0/12/345';
/**
 * array mb_split ( string $pattern , string $string [, int $limit = -1 ] )
 * $pattern The regular expression pattern.     正则表达式
 * $string The string being split.
 * $limit If optional parameter limit is specified, it will be split in limit elements as maximum.
 */

print_r(mb_split('/', $str, -1));
print_r(mb_split('/', $str));
print_r(mb_split('/', $str, 1));
print_r(mb_split('/', $str, 2));

$str_cn = '今天是假期第四天了，计划完成度还未过半，剩下三天需要加油啦！';
print_r(preg_split('/(?<!^)(?!$)/u', $str_cn));
$str_cn_arr = mb_split('[，！]', $str_cn);
print_r($str_cn_arr);
$arr = join('', $str_cn_arr);
print_r(str_split($arr, 3));
