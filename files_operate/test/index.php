<?php
/**
 * Created by PhpStorm.
 * User: Alan
 * Date: 2016/10/6 0006
 * Time: 20:58
 */

require 'FileUploadSingle.php';
$upload_file = $_FILES['upload_file'];
$upload_setter = array(
    'path' => '../imooc',
    'size' => 5242880
);
$uploader = new FileUploadSingle($upload_file, null);
header('content-type:text/html;charset=utf-8');
echo $uploader->get_saved_file_name() . '图片上传成功！';