<?php

/**
 * Created by PhpStorm.
 * User: Alan
 * Date: 2016/10/6 0006
 * Time: 19:53
 */
class FileUploadSingle
{
    private $file_info;
    private $max_file_size = 2097152;//2M
    private $legal_file_extensions = array('jpeg', 'jpg', 'png', 'gif', 'swf');
    private $det_true_image_flag = true;
    private $upload_path = '../uploads';
    private $saved_file_name;

    public function __construct($upload_file_info, $upload_setter = null)
    {
        $this->file_info = $upload_file_info;
        $this->max_file_size = isset($upload_setter['size']) ? $upload_setter['size'] : $this->max_file_size;
        $this->legal_file_extensions = isset($upload_setter['exts']) ? $upload_setter['exts'] : $this->legal_file_extensions;
        $this->det_true_image_flag = isset($upload_setter['flag']) ? $upload_setter['flag'] : $this->det_true_image_flag;
        $this->upload_path = isset($upload_setter['path']) ? $upload_setter['path'] : $this->upload_path;

        $this->do_parse_file_info();
    }

    private function do_parse_file_info()
    {
        if ($this->file_info['error'] == UPLOAD_ERR_OK) {
            if ($this->set_upload_limits()) {
                $this->move_tmp_to_destination();
            }
        } else {
            $this->det_upload_error();
        }
    }

    private function is_upload_dir_existed()
    {
        if (!file_exists($this->upload_path)) {
            mkdir($this->upload_path, 0777, true);
            chmod($this->upload_path, 0777);
        }
    }

    private function move_tmp_to_destination()
    {
        $this->is_upload_dir_existed();
        $upload_path = $this->upload_path;
        $unique_name = md5(uniqid(microtime(true), true));
        $this->saved_file_name = $unique_name . '.' . pathinfo($this->file_info['name'], PATHINFO_EXTENSION);
        $destination = $upload_path . '/' . $this->saved_file_name;
        @move_uploaded_file($this->file_info['tmp_name'], $destination);
    }

    private function set_upload_limits()
    {
        if ($this->file_info['size'] > $this->max_file_size) {
            $this->show_error_log('上传文件超过限定值');
        }

        $ext = pathinfo($this->file_info['name'], PATHINFO_EXTENSION);
        $allow_ext = $this->legal_file_extensions;
        if (!in_array($ext, $allow_ext)) {
            $this->show_error_log('上传文件类型不合法');
        }

        if (!is_uploaded_file($this->file_info['tmp_name'])) {
            $this->show_error_log('上传的文件不是通过HTTP POST方式上传');
        }

        if ($this->det_true_image_flag) {
            if (!getimagesize($this->file_info['tmp_name'])) {
                $this->show_error_log('上传的图片不是真实的图片');
            }
        }

        return true;
    }

//    匹配错误信息
    private function det_upload_error()
    {
        $error = $this->file_info['error'];
        $msg = '';
        switch ($error) {
            case 1:
                $msg = ('上传文件超过了PHP配置文件中upload_max_filesize选项的值');
                break;

            case 2:
                $msg = ('上传文件超过了表单max_file_size限制的大小');
                break;

            case 3:
                $msg = ('文件部分被上传');
                break;

            case 4:
                $msg = ('没有选择上传文件');
                break;

            case 6:
                $msg = ('没有找到临时目录');
                break;

            case 7:
                $msg = ('文件写入失败');
                break;

            case 8:
                $msg = ('系统错误');
                break;
        }
        $this->show_error_log($msg);
    }

    private function show_error_log($error = '系统错误')
    {
        header('content-type:text/html;charset=utf-8');
        exit($error);
    }

    public function get_saved_file_name()
    {
        return $this->saved_file_name;
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
        unset($this->file_info);
    }
}