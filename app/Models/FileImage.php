<?php

namespace BBCMS\Models;

use BBCMS\Models\File;

class Image // extends File
{
    protected $image;
    protected $width;
    protected $height;
    protected $type;

    public function __construct($file)
    {
        $this->setType($file);
        $this->make($file);
        $this->setSize();
    }

    protected function setType($file)
    {
        $pic = getimagesize($file);

        switch ($pic['mime']) {
            case 'image/jpeg': $this->type = 'jpg'; break;
            case 'image/png': $this->type = 'png'; break;
            case 'image/gif': $this->type = 'gif'; break;
        }
    }

    protected function make($file)
    {
        switch ($this->type) {
            case 'jpg': $this->image = @imagecreatefromJpeg($file); break;
            case 'png': $this->image = @imagecreatefromPng($file); break;
            case 'gif': $this->image = @imagecreatefromGif($file); break;
        }
    }

    protected function setSize()
    {
        $this->width = imageSX($this->image);
        $this->height = imageSY($this->image);
    }

    public function resize($width = false, $height = false)
    {
        if (is_numeric($width) and is_numeric($height) and $width > 0 and $height > 0) {
            $newSize = $this->getSizeByFramework($width, $height);
        } elseif (is_numeric($width) and $width > 0) {
            $newSize = $this->getSizeByWidth($width);
        } else {
            $newSize = [$this->width, $this->height];
        }

        $newImage = imagecreatetruecolor($newSize[0], $newSize[1]);

        if ($this->type == 'gif' or $this->type == 'png') {
            $white = imagecolorallocate($newImage, 255, 255, 255);
            imagefill($newImage, 0, 0, $white);
        }

        imagecopyresampled($newImage, $this->image, 0, 0, 0, 0, $newSize[0], $newSize[1], $this->width, $this->height);

        $this->image = $newImage;
        $this->setSize();

        return $this;
    }

    protected function getSizeByFramework($width, $height)
    {
        if ($this->width <= $width and $this->height <= $height) {
            return [$this->width, $this->height];
        }

        if ($this->width / $width > $this->height / $height) {
            $newSize[0] = $width;
            $newSize[1] = round($this->height * $width / $this->width);
        } else {
            $newSize[0] = round($this->width * $height / $this->height);
            $newSize[1] = $height;
        }

        return $newSize;
    }

    protected function getSizeByWidth($width)
    {
        if ($width >= $this->width) {
            return [$this->width, $this->height];
        }

        $newSize[0] = $width;
        $newSize[1] = round($this->height * $width / $this->width);

        return $newSize;
    }

    public function crop($x0 = 0, $y0 = 0, $w = false, $h = false)
    {
        if (! is_numeric($x0) or $x0 < 0 or $x0 >= $this->width) {
            $x0 = 0;
        }
        if (! is_numeric($y0) or $y0 < 0 or $y0 >= $this->height) {
            $y0 = 0;
        }
        if (! is_numeric($w) or $w <= 0 or $w > $this->width - $x0) {
            $w = $this->width - $x0;
        }
        if (! is_numeric($h) or $h <= 0 or $h > $this->height - $y0) {
            $h = $this->height - $y0;
        }

        $newImage = imagecreatetruecolor($w, $h);
        imagecopyresampled($newImage, $this->image, 0, 0, $x0, $y0, $w, $h, $w, $h);
        $this->image = $newImage;
        $this->setSize();

        return $this;
    }

    public function watermark()
    {
        $stamp = imagecreatefromPng('watermark.png');
        $marge_right = 4;
        $marge_bottom = 5;
        $sx = imageSX($stamp);
        $sy = imageSY($stamp);
        imagealphablending($stamp, true);
        imagealphablending($this->image, true);
        imagecopy($this->image, $stamp, $this->width - $sx - $marge_right, $this->height - $sy - $marge_bottom, 0, 0, $sx, $sy);

        return $this;
    }

    public function saveAsBase64()
    {
        ob_start();
        imagejpeg($this->image);
        $outputBuffer = ob_get_clean();
        $base64 = 'data:image/'.$this->type.';base64,'.base64_encode($outputBuffer);
        imagedestroy($this->image);

        return $base64;
    }

    public function save($path = '', $fileName, $type = false, $quality = 100)
    {
        if (trim($fileName) == '' or $this->image === false or ! is_dir($path)) {
            return false;
        }

        if (! is_numeric($quality) or $quality < 0 or $quality > 100) {
            $quality = 100;
        }

        $type = strtolower($type ?? $this->type);
        $savePath = $path.trim($fileName).'.'.$type;

        switch ($type) {
            case 'jpg': imagejpeg($this->image, $savePath, $quality); return $savePath;
            case 'png': imagepng($this->image, $savePath); return $savePath;
            case 'gif': imagegif($this->image, $savePath); return $savePath;
            default: return false;
        }

        imagedestroy($this->image);
    }
}

/***********************************************************************************
Функция img_resize(): генерация thumbnails
Параметры:
  $src             - имя исходного файла
  $dest            - имя генерируемого файла
  $width, $height  - ширина и высота генерируемого изображения, в пикселях
Необязательные параметры:
  $rgb             - цвет фона, по умолчанию - белый
  $quality         - качество генерируемого JPEG, по умолчанию - максимальное (100)
***********************************************************************************/
function img_resize($src, $dest, $width, $height, $rgb = 0xFFFFFF, $quality = 100)
{
    if (!file_exists($src))
        return false;

    $size = getimagesize($src);

    if ($size === false)
        return false;

    $format = strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1));
    $icfunc = 'imagecreatefrom'.$format;

    if (!function_exists($icfunc))
        return false;

    $x_ratio = $width  / $size[0];
    $y_ratio = $height / $size[1];

    if ($height == 0)
    {
        $y_ratio = $x_ratio;
        $height  = $y_ratio * $size[1];
    }
    elseif ($width == 0)
    {
        $x_ratio = $y_ratio;
        $width   = $x_ratio * $size[0];
    }

    $ratio       = min($x_ratio, $y_ratio);
    $use_x_ratio = ($x_ratio == $ratio);

    $new_width   = $use_x_ratio  ? $width  : floor($size[0] * $ratio);
    $new_height  = !$use_x_ratio ? $height : floor($size[1] * $ratio);
    $new_left    = $use_x_ratio  ? 0 : floor(($width - $new_width)   / 2);
    $new_top     = !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);

    // если не нужно увеличивать маленькую картинку до указанного размера
    if ($size[0]<$new_width && $size[1]<$new_height)
    {
        $width = $new_width = $size[0];
        $height = $new_height = $size[1];
    }

    $isrc  = $icfunc($src);
    $idest = imagecreatetruecolor($width, $height);

    imagefill($idest, 0, 0, $rgb);
    imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0, $new_width, $new_height, $size[0], $size[1]);

    $i = strrpos($dest,'.');
    if (!$i) return '';
    $l = strlen($dest) - $i;
    $ext = substr($dest,$i+1,$l);

    switch ($ext)
    {
        case 'jpeg':
        case 'jpg':
        imagejpeg($idest,$dest,$quality);
        break;
        case 'gif':
        imagegif($idest,$dest);
        break;
        case 'png':
        imagepng($idest,$dest);
        break;
    }

    imagedestroy($isrc);
    imagedestroy($idest);

    return true;
}
