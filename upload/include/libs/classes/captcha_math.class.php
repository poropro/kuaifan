<?php
/**
 * 验证码
 */
class Captcha
{
    private $width;
    private $height;
    private $codeNum;
    private $SesSion;
    private $code;
    private $im;

    function __construct($width=80, $height=20)
    {
        $this->width = $width;
        $this->height = $height;
        $this->codeNum = $codeNum;
        $this->SesSion = $SesSion;
    }

    function showImg()
    {
        //创建图片
        $this->createImg();
        //设置干扰元素
        $this->setDisturb();
        //设置验证码
        $this->setCaptcha();
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
        $bgColor = imagecolorallocate($this->im, 200, 200, 200);
        imagefill($this->im, 0, 0, $bgColor);
    }

    private function setDisturb()
    {
        $area = ($this->width * $this->height) / 20;
        $disturbNum = ($area > 250) ? 250 : $area;
        //加入点干扰
        for ($i = 0; $i < $disturbNum; $i++) {
            $color = imagecolorallocate($this->im, rand(0, 255), rand(0, 255), rand(0, 255));
            imagesetpixel($this->im, rand(1, $this->width - 2), rand(1, $this->height - 2), $color);
        }
        //加入弧线
        for ($i = 0; $i <= 5; $i++) {
            //$color = imagecolorallocate($this->im, rand(128, 255), rand(125, 255), rand(100, 255));
            $color = imagecolorallocate($this->im, rand(0, 100), rand(0, 100), rand(0, 250));
            imagearc($this->im, rand(0, $this->width), rand(0, $this->height), rand(30, 300), rand(20, 200), 50, 30, $color);
        }
    }

    private function createCode()
    {
        $str_a = intval(rand(1,30));
        $str_b = strlen($str_a) == 1?intval(rand(1,30)):intval(rand(1,9));    
        if ($str_a > $str_b) {
          $str_c = "+-";
          $str_c = $str_c{rand(0, strlen($str_c) - 1)};
        }else{
          $str_c = "+";
        } 
        if ($str_c == '-'){
          $this->SesSion = intval($str_a - $str_b);
        }else{
          $this->SesSion = intval($str_a + $str_b);
        }        
        $this->code = $str_a.$str_c.$str_b.'=?';

    }

    private function setCaptcha()
    {
        $this->createCode();
        $this->codeNum = strlen($this->code);

        for ($i = 0; $i < $this->codeNum; $i++) {
            $color = imagecolorallocate($this->im, rand(0, 100), rand(0, 100), rand(0, 250));
            $size = rand(floor($this->height / 5), floor($this->height / 3));
            $x = floor($this->width / $this->codeNum) * $i + 5;
            $y = rand(0, $this->height - 20);
            imagechar($this->im, $size, $x, $y, $this->code{$i}, $color);
        }
    }

    private function outputImg()
    {
        $this->generate_captcha();        
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
    
    private function generate_captcha()
    {
      global $db,$timestamp;
      $_data = array();
      $_data['captcha'] = yanzhengmaip();
      $_data['code'] = $this->SesSion;
      $_data['time'] = $timestamp;
      $__r = $db->getone("select * from ".table('yanzhengma')." where captcha = '{$_data['captcha']}' LIMIT 1");
      if($__r) {
        updatetable(table('yanzhengma'), $_data, "captcha = '{$_data['captcha']}'");
      }else{
        inserttable(table('yanzhengma'), $_data);
      }
      $db->query("Delete from ".table('yanzhengma')." WHERE time < ".intval($timestamp - 3600)); //删除1个小时以前的验证码
    }

}

?>