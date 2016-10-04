<?php
/**
 * Created by PhpStorm.
 * User: Alan
 * Date: 2016/10/4 0004
 * Time: 22:12
 */

function unicode_decode($need_str)
{

    // 转换编码，将Unicode编码转换成可以浏览的utf-8编码
    $pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
    preg_match_all($pattern, $need_str, $matches);
    if (!empty($matches)) {
        $need_str = '';
        for ($j = 0; $j < count($matches[0]); $j++) {
            $need_str = $matches[0][$j];
            if (strpos($need_str, '\\u') === 0) {
                $code = base_convert(substr($need_str, 2, 2), 16, 10);
                $code2 = base_convert(substr($need_str, 4), 16, 10);
                $c = chr($code) . chr($code2);
                $c = iconv('UCS-2', 'UTF-8', $c);
                $need_str .= $c;
            } else {
                $need_str .= $need_str;
            }
        }
    }
    return $need_str;
}


$str = "";
for ($i = 0; $i < 10; $i++) {
    $str .= "\\u" . dechex(rand(19968, 40895));
}
$str = unicode_decode($str);

echo $str;
