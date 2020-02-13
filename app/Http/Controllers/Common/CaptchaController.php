<?php

namespace BBCMS\Http\Controllers\Common;

class CaptchaController
{
    protected $number;
    protected $width;
    protected $height;
    protected $size;
    protected $font;

    public function __construct()
    {
        // Prepare attributes
        $this->number = rand(1000, 9999);
        $this->width = setting('system.captcha_width', 68);
        $this->height = setting('system.captcha_height', 38);
        $this->size = setting('system.captcha_font_size', 20);
        $this->font = resource_path('fonts'.DS) . setting('system.captcha_font_family', 'blowbrush') . '.ttf';
    }

    public function make()
    {
        // Save to session value of `number`
        session(['captcha' => md5($this->number)]);

        // Generate captcha image
        $image = imagecreatetruecolor($this->width, $this->height);
        $back = imagecolorallocate($image, 200, 200, 200);
        $grey = imagecolorallocate($image, 150, 150, 150);
        $white = imagecolorallocate($image, 255, 255, 255);
        imagefilledrectangle($image, 0, 0, $this->width * 2, $this->height * 2, $back);
        imagettftext($image, $this->size, 8, 10, 30, $grey, $this->font, $this->number);
        imagettftext($image, $this->size, 8, 6, 34, $white, $this->font, $this->number);

        // Print and destroy image
        imagepng($image);
        imagedestroy($image);

        // Print HTTP headers and prevent caching on client side
        return response('', 200, [
            'Content-Type' => 'image/png',
            'Pragma' => 'no-cache',
            'Expires' => 'Wed, 1 Jan 1997 00:00:00 GMT',
            'Last-Modified' => gmdate('D, d M Y H:i:s') . ' GMT',
            'Cache-Control' => 'must-revalidate, no-cache, no-store, private',
        ]);
    }
}
