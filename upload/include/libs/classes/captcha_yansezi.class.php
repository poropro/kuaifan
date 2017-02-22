<?php
/**
 * 生成颜色文字图片
 */
class Captcha
{
    private $width;
    private $height;
    private $hexcolor;
    private $fontfile;
    private $content;
    private $im;

    function __construct($width=0, $height=0, $content='', $txtcolor='', $fontfile='')
    {
        $this->width = !empty($width)?$width:get_strlen($content)*18;
        $this->height = !empty($height)?$height:20;
        $this->hexcolor = !empty($txtcolor)?$txtcolor:'000000';
        $this->fontfile = !empty($fontfile)?$fontfile:'zhunyuan.ttf';
        $this->content = !empty($content)?$content:'颜色文字';
    }

    function showImg()
    {
    	//转义颜色
    	$this->hex2rgb();
        //创建图片
        $this->createImg();
        //设置验证码
        $this->setCaptcha();
        //输出图片
        $this->outputImg();
    }

    function getCaptcha()
    {
        return $this->content;
    }

    private function createImg()
    {
    	
        $this->im = imagecreatetruecolor($this->width, $this->height);
        if ($this->hexcolor == 'ffffff'){
        	$bgColor = imagecolorallocate($this->im, 0, 0, 0);
        }else{
        	$bgColor = imagecolorallocate($this->im, 255, 255, 255);
        }
        imagecolortransparent($this->im, $bgColor);
        imagefill($this->im, 0, 0, $bgColor);
    }


    private function setCaptcha()
    {
    	$rrColor = $this->rgb;
    	$color = imagecolorallocate($this->im, $rrColor['r'], $rrColor['g'], $rrColor['b']);
    	imagettftext($this->im, 12, 0, 2, 16, $color, KF_INC_PATH.'libs/data/font/'.$this->fontfile, $this->content);
    	
        
    }

    private function outputImg()
    {
        if (imagetypes() & IMG_GIF) {
            header('Content-type: image/gif');
            imagegif($this->im);
        } elseif (imagetype() & IMG_PNG) {
            header('Content-type: image/png');
            imagepng($this->im);
        } elseif (imagetypes() & IMG_JPG) {
            header('Content-type:image/jpeg');
            imagejpeg($this->im);
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