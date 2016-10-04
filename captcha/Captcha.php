<?php

/**
 * Created by PhpStorm.
 * User: Alan
 * Date: 2016/10/4 0004
 * Time: 18:02
 */

/**
 * Class Captcha
 * @param array $cap_setter
 * width                        验证码图片宽度
 * height                       验证码图片高度
 * count                        验证码中字符长度
 * type                         验证码校验类型，0为数字，1为数字字母结合，2为中文字符，3为图片
 */
class Captcha
{
    private $img;
    private $cap_size;
    private $cap_cont;
    private $cap_cont_count;
    private $cap_cont_color;

    public function __construct($cap_setter)
    {
        $this->cap_size = array(
            'w' => $cap_setter['width'],
            'h' => $cap_setter['height']
        );
        $this->img = imagecreatetruecolor(100, 30);
        $this->img = imagecreatetruecolor($cap_setter['width'], $cap_setter['height']);
        $this->cap_cont = '';
        $this->cap_cont_count = isset($cap_setter['count']) ? $cap_setter['count'] : 4;
        $this->cap_cont_color = imagecolorallocate($this->img, rand(0, 60), rand(0, 60), rand(0, 60));
        $cap_bg_color = imagecolorallocate($this->img, 255, 255, 255);
        imagefill($this->img, 0, 0, $cap_bg_color);

        $this->gen_val_con($cap_setter['type']);
        if (isset($cap_setter['save'])) {
            $cap_setter['save'] ? $this->save(time()) : null;
        }
    }

    /**
     * @param $type //校验内容类型
     * $type = 0,   纯数字
     * $type = 1,   数字与字母结合
     * $type = 2,   中文字符
     * $type = 3,   图片验证
     */
    private function gen_val_con($type)
    {
        switch ($type) {
            case 0:
                $this->gen_val_num();
                $this->set_pixel();
                $this->set_line();
                break;

            case 1:
                $this->gen_val_char();
                $this->set_pixel();
                $this->set_line();
                break;

            case 2:
                $this->gen_val_cn();
                $this->set_pixel();
                $this->set_line();
                break;

            case 3:
                break;

            default:
                break;
        }
    }

    //纯数字验证码
    private function gen_val_num()
    {
        $font = 6;
        for ($i = 0; $i < $this->cap_cont_count; $i++) {
            $num = rand(0, 9);
            $this->cap_cont .= $num;
            $x = $i * $this->cap_size['w'] / $this->cap_cont_count + rand(0, 5);
            $y = rand(5, $this->cap_size['h'] - 15);
            imagestring($this->img, $font, $x, $y, $num, $this->cap_cont_color);
        }
    }

    //数字与字母结合
    private function gen_val_char()
    {
        $str = 'abcdefghijkmnpqrstuvwxyz23456789';
        $str_arr = str_split($str, 1);
        $font = 6;
        for ($i = 0; $i < $this->cap_cont_count; $i++) {
            $index = rand(0, count($str_arr));
            $char = $str_arr[$index];
            $this->cap_cont .= $char;
            $x = $i * $this->cap_size['w'] / $this->cap_cont_count + rand(0, 5);
            $y = rand(5, $this->cap_size['h'] - 15);
            imagestring($this->img, $font, $x, $y, $char, $this->cap_cont_color);
        }
    }

    //中文字符
    private function gen_val_cn()
    {
        $str_cn = '今天是假期第四天了，计划完成度还未过半，剩下三天需要加油啦！';
        $str_cn_arr = mb_split('[，！]', $str_cn);
        $str_cn = join('', $str_cn_arr);
        $str_cn_arr = str_split($str_cn, 3);
        for ($i = 0; $i < $this->cap_cont_count; $i++) {
            $index = rand(0, count($str_cn_arr));
            $this->cap_cont .= $str_cn_arr[$index];
            $x = $i * $this->cap_size['w'] / $this->cap_cont_count + rand(0, 5);
            $y = rand(20, $this->cap_size['h'] - 15);
            $font_file = '../image_operate/resources/fonts/daliang_font.ttf';
            imagettftext($this->img, 24, rand(-60, 60), $x, $y, $this->cap_cont_color, $font_file, $str_cn_arr[$index]);
        }
    }

    //设置干扰噪点
    private function set_pixel($point_num = 200)
    {
        for ($i = 0; $i < $point_num; $i++) {
            $point_color = imagecolorallocate($this->img, rand(80, 220), rand(80, 220), rand(80, 220));
            imagesetpixel($this->img, $this->do_rand(0), $this->do_rand(1), $point_color);
        }
    }

    //设置干扰线段
    private function set_line($line_num = 4)
    {
        for ($i = 0; $i < $line_num; $i++) {
            $line_color = imagecolorallocate($this->img, rand(80, 220), rand(80, 220), rand(80, 220));
            $x1 = $this->do_rand(0);
            $x2 = $this->do_rand(0);
            $y1 = $this->do_rand(1);
            $y2 = $this->do_rand(1);
            imageline($this->img, $x1, $y1, $x2, $y2, $line_color);
        }
    }

    //画布范围内产生随机数
    private function do_rand($range)
    {
        return $range == 0 ? rand(1, $this->cap_size['w']) : rand(1, $this->cap_size['h']);
    }

    //保存生成的验证码图片以'jpg'格式存储在output文件夹中
    private function save($filename)
    {
        $file_path = dirname(__FILE__) . '/output/' . $filename . '.jpg';
        imagejpeg($this->img, $file_path);
    }

    //返回验证码的内容，以便校验
    public function get_cap_cont()
    {
        return $this->cap_cont;
    }

    //在浏览器输出
    public function show($con_type)
    {
        header('content-type:' . $con_type);
        if ($con_type == 'text/html') {
            //TODO: Show text/HTML on the browser
        } else {
            $arr = mb_split('/', $con_type);
            $type = end($arr);
            $fun = "image$type";
            $fun($this->img);
        }
    }

    //销毁图像连接资源
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
        imagedestroy($this->img);
    }
}