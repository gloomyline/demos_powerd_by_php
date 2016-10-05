<?php
/**
 * Created by PhpStorm.
 * User: Alan
 * Date: 2016/10/5 0005
 * Time: 10:43
 */
//isset($_REQUEST['authentication_code'])
if (!!$_REQUEST['authentication_code']) {
    session_start();

    if (mb_strtolower($_REQUEST['authentication_code']) == $_SESSION['authentication_code']) {
        echo '<p style="color:#00cc00;">输入正确</p>';
    } else {
        echo '<p style="color: #cc0000;">输入错误</p>';
    }
} else {
    echo '<p style="color:#ff0000">请输入验证码</p>';
}

?>
