<?php
/**
 * 生成颜色图片
 */
class Captcha
{
    private $width;
    private $height;
    private $hexcolor;
    private $code;
    private $im;

    function __construct($width=32, $height=32, $hexcolor='0000ff')
    {
        $this->width = $width;
        $this->height = $height;
        $this->hexcolor = $hexcolor;
    }

    function showImg()
    {
    	//转义颜色
    	$this->hex2rgb();
        //创建图片
        $this->createImg();
        //输出图片
        $this->outputImg();
    }

    function getCaptcha()
    {
        return $this->code;
    }

    private function createImg()
    {
        $this->im = imagecreatetruecolor($this->width, $this->height);
        $rrColor = $this->rgb;
        $bgColor = imagecolorallocate($this->im, $rrColor['r'], $rrColor['g'], $rrColor['b']);
        imagefill($this->im, 0, 0, $bgColor);
    }


    private function outputImg()
    {
        if (imagetypes() & IMG_JPG) {
            header('Content-type:image/jpeg');
            imagejpeg($this->im);
        } elseif (imagetypes() & IMG_GIF) {
            header('Content-type: image/gif');
            imagegif($this->im);
        } elseif (imagetype() & IMG_PNG) {
            header('Content-type: image/png');
            imagepng($this->im);
        } else {
            die("Don't support image type!");
        }
    }
    private function hex2rgb() 
    {
    	$color = str_replace('#', '', $this->hexcolor);
    	if (strlen($color) > 3) {
    		$rgb = array(
				'r' => hexdec(substr($color, 0, 2)), 
				'g' => hexdec(substr($color, 2, 2)), 
				'b' => hexdec(substr($color, 4, 2)) 
    		);
    	} else {
    		$color = str_replace('#', '', $this->hexcolor);
    		$r = substr($color, 0, 1) . substr($color, 0, 1);
    		$g = substr($color, 1, 1) . substr($color, 1, 1);
    		$b = substr($color, 2, 1) . substr($color, 2, 1);
    		$rgb = array(
				'r' => hexdec($r), 
				'g' => hexdec($g), 
				'b' => hexdec($b) 
    		);
    	}
    	$this->rgb = $rgb;
    }
    
    

}

?>