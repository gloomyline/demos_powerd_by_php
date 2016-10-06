<?php
/**
 * Created by PhpStorm.
 * User: Alan
 * Date: 2016/10/6 0006
 * Time: 12:19
 */

//print_r($_REQUEST);
//$_FILES:文件上传变量
//print_r($_FILES);
//exit — 输出一个消息并且退出当前脚本
//exit;
//$_FILES
//
//Array
//(
//    [upload_file] => Array
//    (
//        [name] => yoona . jpg
//        [type] => image / jpeg
//        [tmp_name] => D:\wamp64\tmp\php20AF . tmp
//        [error] => 0
//        [size] => 8778
//    )
//
//)
header('content-type:text/html;charset=utf-8');
/**
 * upload_file 客户端input标签的name属性值
 *
 * name:长传文件的名称
 * type:上传文件的MIME类型
 * tmp_name:上传到服务器上的临时文件名
 * size:上传文件的大小
 * error:上传文件的错误号
 */
$file_info = $_FILES['upload_file'];
$file_name = $file_info['name'];
$file_type = $file_info['type'];
$file_tmp_name = $file_info['tmp_name'];
$file_size = $file_info['size'];
$error_upload_file = $file_info['error'];

$maxSize = 5242880;//允许上传文件大小的最大值，单位字节
//    $ext = strtolower(end(explode('.', $file_name)));
$ext = pathinfo($file_name, PATHINFO_EXTENSION);
$allowExt = array(
    'jpeg', 'jpg', 'png', 'gif', 'swf'
);
$upload_path = '../uploads';
if (!file_exists($upload_path)) {
    mkdir($upload_path, 0777, true);//创建多级目录
    chmod($upload_path, 0777);//修改目录权限
}

//    确保文件名唯一，避免重名产生覆盖
$uni_name = md5(uniqid(microtime(true), true)) . '.' . $ext;
$uploads_dir_path = '../uploads' . '/' . $uni_name;
//    echo $uni_name;exit;
//是否开启检测图片是否是真实图片，默认开启
$det_true_image_flag = true;

/**
 *将服务器上的临时文件移动到指定目录下
 *方法一：
 *move_uploaded_file($tmp_name, $destination)
 * 成功返回true,否则返回false
 */
//move_uploaded_file($file_tmp_name, '../uploads/'.$file_name);

/**
 * 方法二：
 * copy($src, $dst):将文件拷贝到指定的目录，拷贝成功返回true,失败返回false
 */
//copy($file_tmp_name, '../uploads/'.$file_name);

/**
 * 文件上传配置
 * 服务器端配置：
 * file_uploads = On,支持HTTP上传
 * upload_tmp_dir=,临时文件保存的目录
 * upload_max_filesize=2M,允许上传文件的最大值
 * max_file_uploads=20,允许一次上传的最大文件数
 * post_max_size=8M,POST方式发送数据的最大值
 *
 * max_execution_time = -1,设置了脚本被解析器终止之前的允许的最大执行时间，单位为秒，防止程序写得不好而占尽服务器资源
 * max_input_time = 60,脚本解析器输入数据允许的最大时间，单位是秒
 * max_input_nesting_level = 64,设置输入变量的嵌套深度
 *
 */

/**
 * 错误信息说明
 * UPLOAD_ERR_OK:0,没有错误发生，文件上传成功
 * UPLOAD_ERR_INI_SIZE:1,上传的文件超过了php.ini中upload_max_filesize选项的值
 * UPLOAD_ERR_FORM_SIZE:2,上传文件的大小超过了HTML表单MAX_FILE_SIZE选项指定的值
 * UPLOAD_ERR_PARTIAL:3,文件只有部分被上传
 * UPLOAD_ERR_NO_FILE:4,没有文件被上传
 * UPLOAD_ERR_NO_TMP_DIR:6,找不到临时文件夹
 * UPLOAD_ERR_CANT_WRITE:7,文件写入失败
 * UPLOAD_ERR_EXTENSION:8,上传的文件被php扩展程序打断
 */
//判断错误号，只有0或者是UPLOAD_ERR_OK，没有错误发生，上传成功
if ($error_upload_file == UPLOAD_ERR_OK) {
//    判断文件大小是否超过限定值
    if ($file_size > $maxSize) {
        exit('上传文件的大小超过限定值');
    }
//    判断文件类型是否符合规范
    if (!in_array($ext, $allowExt)) {
        exit('上传的文件类型不符合规范');
    }
//    判断文件是否通过HTTP POST方式上传
    if (!is_uploaded_file($file_tmp_name)) {
        exit('文件不是通过HTTP POST方式上传');
    }
//    判断文件是否是真实图片
    if ($det_true_image_flag) {
        if (!getimagesize($file_tmp_name)) {
            exit('上传的文件不是真实的图片');
        }
    }

//    将符合规范的临时文件移动到上传目录中
    if (@move_uploaded_file($file_tmp_name, $uploads_dir_path)) {//'@'错误译制符，避免错误显示在客户端，让用户看到
        echo '文件' . $file_name . '上传成功';
    } else {
        echo '文件' . $file_name . '上传失败';
    }
} else {
    //匹配错误信息
    switch ($error_upload_file) {
        case 1:
            exit('上传文件超过了PHP配置文件中upload_max_filesize选项的值');
            break;

        case 2:
            exit('上传文件超过了表单max_file_size限制的大小');
            break;

        case 3:
            exit('文件部分被上传');
            break;

        case 4:
            exit('没有选择上传文件');
            break;

        case 6:
            exit('没有找到临时目录');
            break;

        case 7:
            exit('文件写入失败');
            break;

        case 8:
            exit('系统错误');
            break;
    }
}



