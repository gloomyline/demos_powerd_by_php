<?php

/**
 * Created by PhpStorm.
 * User: Alan
 * Date: 2016/10/3 0003
 * Time: 22:44
 */
class Image
{
    private $info;

    private $image;

    public function __construct($src)
    {
        $img_info = getimagesize($src);
//       print_r($img_info);
        $this->info = array(
            'width' => $img_info[0],
            'height' => $img_info[1],
            'type' => image_type_to_extension($img_info[2], false),
            'mime' => $img_info['mime']
        );
        $fun = "imagecreatefrom" . $this->info['type'];
        $this->image = $fun($src);
    }

    public function getinfo()
    {
        return print_r($this->info);
    }

    public function textmark($text, $size, $angle, $pos, $color)
    {
        $col = imagecolorallocatealpha($this->image, $color['r'], $color['g'], $color['b'], $color['a']);
        $fontfile = 'resources/fonts/daliang_font.ttf';
        imagettftext($this->image, $size, $angle, $pos['x'], $pos['y'], $col, $fontfile, $text);
    }

    public function imgmark($resource, $pos, $alpha)
    {
        $water_info = getimagesize($resource);
        $width = $water_info[0];
        $height = $water_info[1];
        $type = image_type_to_extension($water_info[2], false);
        $fun = "imagecreatefrom$type";
        $img = $fun($resource);
        imagecopymerge($this->image, $img, $pos['x'], $pos['y'], 0, 0, $width, $height, $alpha);
        imagedestroy($img);
    }

    public function thumb($size)
    {
        $img = imagecreatetruecolor($size['width'], $size['height']);
        $src_width = $this->info['width'];
        $src_height = $this->info['height'];
        imagecopyresampled($img, $this->image, 0, 0, 0, 0, $size['width'], $size['height'], $src_width, $src_height);
//        将一个新的图片连接资源赋给一个原有的图片连接资源的时候，需要先销毁之前的图片连接资源，才能成功
        imagedestroy($this->image);
        $this->image = $img;
    }

    public function show()
    {
        header('content-type:' . $this->info['mime']);
        $fun = 'image' . $this->info['type'];
        $fun($this->image);
    }

    public function save($filename)
    {
        $fun = 'image' . $this->info['type'];
        $path = 'output/';
        $fun($this->image, $path . $filename . '.' . $this->info['type']);
    }

    public function __destruct()
    {
        imagedestroy($this->image);
    }
}



