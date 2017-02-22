<?php
/*

【等比例缩放】


使用实例:

< ?php

$t = new ThumbHandler();

$t->setSrcImg("img/test.jpg");
$t->setDstImg("tmp/new_test.jpg");
$t->setMaskImg("img/test.gif");
$t->setMaskPosition(1);
$t->setMaskImgPct(80);
$t->setDstImgBorder(4,"#dddddd");

// 指定缩放比例
$t->createImg(300,200);
?>
< ?php


$t = new ThumbHandler();

// 基本使用
$t->setSrcImg("img/test.jpg");
$t->setMaskWord("test");
$t->setDstImgBorder(10,"#dddddd");

// 指定缩放比例
$t->createImg(50);
?>
< ?php
equire_once('lib/thumb.class.php');

$t = new ThumbHandler();

// 基本使用
$t->setSrcImg("img/test.jpg");
$t->setMaskWord("test");

// 指定固定宽高
$t->createImg(200,200);
?>
< ?php


$t = new ThumbHandler();

$t->setSrcImg("img/test.jpg");
$t->setDstImg("tmp/new_test.jpg");
$t->setMaskWord("test");

// 指定固定宽高
$t->createImg(200,200);
?>
< ?php


$t = new ThumbHandler();

$t->setSrcImg("img/test.jpg");

// 指定字体文件地址
$t->setMaskFont("c:/winnt/fonts/arial.ttf");
$t->setMaskFontSize(20);
$t->setMaskFontColor("#ffff00");
$t->setMaskWord("test3333333");
$t->setDstImgBorder(99,"#dddddd");
$t->createImg(50);

?>
< ?php


$t = new ThumbHandler();

$t->setSrcImg("img/test.jpg");
$t->setMaskOffsetX(55);
$t->setMaskOffsetY(55);
$t->setMaskPosition(1);
//$t->setMaskPosition(2);
//$t->setMaskPosition(3);
//$t->setMaskPosition(4);
$t->setMaskFontColor("#ffff00");
$t->setMaskWord("test");

// 指定固定宽高
$t->createImg(50);
?>
< ?php


$t = new ThumbHandler();

$t->setSrcImg("img/test.jpg");
$t->setMaskFont("c:/winnt/fonts/simyou.ttf");
$t->setMaskFontSize(20);
$t->setMaskFontColor("#ffffff");
$t->setMaskTxtPct(20);
$t->setDstImgBorder(10,"#dddddd");
$text = "中文";
$str = mb_convert_encoding($text, "UTF-8", "gb2312");
$t->setMaskWord($str);
$t->setMaskWord("test");

// 指定固定宽高
$t->createImg(50);
?>
</strlen>
?>


*/
class ThumbHandler{
	var $dst_img;// 目标文件
	var $h_src; // 图片资源句柄
	var $h_dst;// 新图句柄
	var $h_mask;// 水印句柄
	var $img_create_quality = 100;// 图片生成质量
	var $img_display_quality = 80;// 图片显示质量,默认为75
	var $img_scale = 0;// 图片缩放比例
	var $src_w = 0;// 原图宽度
	var $src_h = 0;// 原图高度
	var $dst_w = 0;// 新图总宽度
	var $dst_h = 0;// 新图总高度
	var $fill_w;// 填充图形宽
	var $fill_h;// 填充图形高
	var $copy_w;// 拷贝图形宽
	var $copy_h;// 拷贝图形高
	var $src_x = 0;// 原图绘制起始横坐标
	var $src_y = 0;// 原图绘制起始纵坐标
	var $start_x;// 新图绘制起始横坐标
	var $start_y;// 新图绘制起始纵坐标
	var $mask_word;// 水印文字
	var $mask_img;// 水印图片
	var $mask_pos_x = 0;// 水印横坐标
	var $mask_pos_y = 0;// 水印纵坐标
	var $mask_offset_x = 5;// 水印横向偏移
	var $mask_offset_y = 5;// 水印纵向偏移
	var $font_w;// 水印字体宽
	var $font_h;// 水印字体高
	var $mask_w;// 水印宽
	var $mask_h;// 水印高
	var $mask_font_color = "#ffffff";// 水印文字颜色
	var $mask_font = 2;// 水印字体
	var $font_size;// 尺寸
	var $mask_position = 0;// 水印位置
	var $mask_img_pct = 50;// 图片合并程度,值越大，合并程序越低
	var $mask_txt_pct = 50;// 文字合并程度,值越小，合并程序越低
	var $img_border_size = 0;// 图片边框尺寸
	var $img_border_color;// 图片边框颜色
	var $_flip_x=0;// 水平翻转次数
	var $_flip_y=0;// 垂直翻转次数
	var $cut_type=0;// 剪切类型
	var $img_type;// 文件类型
	// 文件类型定义,并指出了输出图片的函数
	var $all_type = array(
	"jpg" => array("output"=>"imagejpeg"),
	"gif" => array("output"=>"imagegif"),
	"png" => array("output"=>"imagepng"),
	"wbmp" => array("output"=>"image2wbmp"),
	"jpeg" => array("output"=>"imagejpeg"));

	function ThumbHandler()
	{
	$this->mask_font_color = "#ffffff";
	$this->font = 2;
	$this->font_size = 12;
	}

	function getImgWidth($src)
	{
	return imagesx($src);
	}

	function getImgHeight($src)
	{
	return imagesy($src);
	}

	function setSrcImg($src_img, $img_type=null)
	{
	if(!file_exists($src_img))
	{
	die("图片不存在");
	}
	if(!empty($img_type))
	{
	$this->img_type = $img_type;
	}
	else
	{
	$this->img_type = $this->_getImgType($src_img);
	}
	$this->_checkValid($this->img_type);
	// file_get_contents函数要求php版本>4.3.0
	$src = '';
	if(function_exists("file_get_contents"))
	{
	$src = file_get_contents($src_img);
	}
	else
	{
	$handle = fopen ($src_img, "r");
	while (!feof ($handle))
	{
	$src .= fgets($fd, 4096);
	}
	fclose ($handle);
	}
	if(empty($src))
	{
	die("图片源为空");
	}
	$this->h_src = @ImageCreateFromString($src);
	$this->src_w = $this->getImgWidth($this->h_src);
	$this->src_h = $this->getImgHeight($this->h_src);
	}

	function setDstImg($dst_img)
	{
	$arr = explode('/',$dst_img);
	$last = array_pop($arr);
	$path = implode('/',$arr);
	$this->_mkdirs($path);
	$this->dst_img = $dst_img;
	}

	function setImgDisplayQuality($n)
	{
	$this->img_display_quality = (int)$n;
	}

	function setImgCreateQuality($n)
	{
	$this->img_create_quality = (int)$n;
	}

	function setMaskWord($word)
	{
	$this->mask_word .= $word;
	}

	function setMaskFontColor($color="#ffffff")
	{
	$this->mask_font_color = $color;
	}

	function setMaskFont($font=2)
	{
	if(!is_numeric($font) && !file_exists($font))
	{
	die("字体文件不存在");
	}
	$this->font = $font;
	}

	function setMaskFontSize($size = "12")
	{
	$this->font_size = $size;
	}

	function setMaskImg($img)
	{
	$this->mask_img = $img;
	}

	function setMaskOffsetX($x)
	{
	$this->mask_offset_x = (int)$x;
	}

	function setMaskOffsetY($y)
	{
	$this->mask_offset_y = (int)$y;
	}

	function setMaskPosition($position=0)
	{
	$this->mask_position = (int)$position;
	}

	function setMaskImgPct($n)
	{
	$this->mask_img_pct = (int)$n;
	}

	function setMaskTxtPct($n)
	{
	$this->mask_txt_pct = (int)$n;
	}

	function setDstImgBorder($size=1, $color="#000000")
	{
	$this->img_border_size = (int)$size;
	$this->img_border_color = $color;
	}

	function flipH()
	{
	$this->_flip_x++;
	}

	function flipV()
	{
	$this->_flip_y++;
	}

	function setCutType($type)
	{
	$this->cut_type = (int)$type;
	}

	function setRectangleCut($width, $height)
	{
	$this->fill_w = (int)$width;
	$this->fill_h = (int)$height;
	}

	function setSrcCutPosition($x, $y)
	{
	$this->src_x = (int)$x;
	$this->src_y = (int)$y;
	}

	function createImg($a, $b=null)
	{
	$num = func_num_args();
	if(1 == $num)
	{
	$r = (int)$a;
	if($r < 1)
	{
	die("图片缩放比例不得小于1");
	}
	$this->img_scale = $r;
	$this->_setNewImgSize($r);
	}
	if(2 == $num)
	{
	$w = (int)$a;
	$h = (int)$b;
	if(0 == $w)
	{
	die("目标宽度不能为0");
	}
	if(0 == $h)
	{
	die("目标高度不能为0");
	}
	$this->_setNewImgSize($w, $h);
	}
	if($this->_flip_x%2!=0)
	{
	$this->_flipH($this->h_src);
	}
	if($this->_flip_y%2!=0)
	{
	$this->_flipV($this->h_src);
	}
	$this->_createMask();
	$this->_output();
	// 释放
	if(imagedestroy($this->h_src) && imagedestroy($this->h_dst))
	{
	Return true;
	}
	else
	{
	Return false;
	}
	}

	function _createMask()
	{
	if($this->mask_word)
	{
	// 获取字体信息
	$this->_setFontInfo();
	if($this->_isFull())
	{
	die("水印文字过大");
	}
	else
	{
	$this->h_dst = imagecreatetruecolor($this->dst_w,$this->dst_h);
	$white = ImageColorAllocate($this->h_dst,255,255,255);
	imagefilledrectangle($this->h_dst,0,0,$this->dst_w,$this->dst_h,$white);// 填充背景色
	$this->_drawBorder();
	imagecopyresampled( $this->h_dst, $this->h_src,
	$this->start_x, $this->start_y,
	$this->src_x, $this->src_y,
	$this->fill_w, $this->fill_h,
	$this->copy_w, $this->copy_h);
	$this->_createMaskWord($this->h_dst);
	}
	}
	if($this->mask_img)
	{
	$this->_loadMaskImg();//加载时，取得宽高
	if($this->_isFull())
	{
	// 将水印生成在原图上再拷
	$this->_createMaskImg($this->h_src);
	$this->h_dst = imagecreatetruecolor($this->dst_w,$this->dst_h);
	$white = ImageColorAllocate($this->h_dst,255,255,255);
	imagefilledrectangle($this->h_dst,0,0,$this->dst_w,$this->dst_h,$white);// 填充背景色
	$this->_drawBorder();
	imagecopyresampled( $this->h_dst, $this->h_src,
	$this->start_x, $this->start_y,
	$this->src_x, $this->src_y,
	$this->fill_w, $this->start_y,
	$this->copy_w, $this->copy_h);
	}
	else
	{
	// 创建新图并拷贝
	$this->h_dst = imagecreatetruecolor($this->dst_w,$this->dst_h);
	$white = ImageColorAllocate($this->h_dst,255,255,255);
	imagefilledrectangle($this->h_dst,0,0,$this->dst_w,$this->dst_h,$white);// 填充背景色
	$this->_drawBorder();
	imagecopyresampled( $this->h_dst, $this->h_src,
	$this->start_x, $this->start_y,
	$this->src_x, $this->src_y,
	$this->fill_w, $this->fill_h,
	$this->copy_w, $this->copy_h);
	$this->_createMaskImg($this->h_dst);
	}
	}
	if(empty($this->mask_word) && empty($this->mask_img))
	{
	$this->h_dst = imagecreatetruecolor($this->dst_w,$this->dst_h);
	$white = ImageColorAllocate($this->h_dst,255,255,255);
	imagefilledrectangle($this->h_dst,0,0,$this->dst_w,$this->dst_h,$white);// 填充背景色
	$this->_drawBorder();
	imagecopyresampled( $this->h_dst, $this->h_src,
	$this->start_x, $this->start_y,
	$this->src_x, $this->src_y,
	$this->fill_w, $this->fill_h,
	$this->copy_w, $this->copy_h);
	}
	}

	function _drawBorder()
	{
	if(!empty($this->img_border_size))
	{
	$c = $this->_parseColor($this->img_border_color);
	$color = ImageColorAllocate($this->h_src,$c[0],$c[1], $c[2]);
	imagefilledrectangle($this->h_dst,0,0,$this->dst_w,$this->dst_h,$color);// 填充背景色
	}
	}

	function _createMaskWord($src)
	{
	$this->_countMaskPos();
	$this->_checkMaskValid();
	$c = $this->_parseColor($this->mask_font_color);
	$color = imagecolorallocatealpha($src, $c[0], $c[1],$c[2], $this->mask_txt_pct);
	if(is_numeric($this->font))
	{
	imagestring($src,
	$this->font,
	$this->mask_pos_x, $this->mask_pos_y,
	$this->mask_word,
	$color);
	}
	else
	{
	imagettftext($src,
	$this->font_size, 0,
	$this->mask_pos_x, $this->mask_pos_y,
	$color,
	$this->font,
	$this->mask_word);
	}
	}

	function _createMaskImg($src)
	{
	$this->_countMaskPos();
	$this->_checkMaskValid();
	imagecopymerge($src,
	$this->h_mask,
	$this->mask_pos_x ,$this->mask_pos_y,
	0, 0,
	$this->mask_w, $this->mask_h,
	$this->mask_img_pct);
	imagedestroy($this->h_mask);
	}

	function _loadMaskImg()
	{
	$mask_type = $this->_getImgType($this->mask_img);
	$this->_checkValid($mask_type);
	// file_get_contents函数要求php版本>4.3.0
	$src = '';
	if(function_exists("file_get_contents"))
	{
	$src = file_get_contents($this->mask_img);
	}
	else
	{
	$handle = fopen ($this->mask_img, "r");
	while (!feof ($handle))
	{
	$src .= fgets($fd, 4096);
	}
	fclose ($handle);
	}
	if(empty($this->mask_img))
	{
	die("水印图片为空");
	}
	$this->h_mask = ImageCreateFromString($src);
	$this->mask_w = $this->getImgWidth($this->h_mask);
	$this->mask_h = $this->getImgHeight($this->h_mask);
	}

	function _output()
	{
	$img_type = $this->img_type;
	$func_name = $this->all_type[$img_type]['output'];
	if(function_exists($func_name))
	{
	// 判断浏览器,若是IE就不发送头
	if(isset($_SERVER['HTTP_USER_AGENT']))
	{
	$ua = strtoupper($_SERVER['HTTP_USER_AGENT']);
	if(!preg_match('/^.*MSIE.*\)$/i',$ua))
	{
	header("Content-type:$img_type");
	}
	}
	$func_name($this->h_dst, $this->dst_img, $this->img_display_quality);
	}
	else
	{
	Return false;
	}
	}

	function _parseColor($color)
	{
	$arr = array();
	for($ii=1; $ii<strlen ($color); $ii++)
	{
	$arr[] = hexdec(substr($color,$ii,2));
	$ii++;
	}
	Return $arr;
	}

	function _countMaskPos()
	{
	if($this->_isFull())
	{
	switch($this->mask_position)
	{
	case 1:
	// 左上
	$this->mask_pos_x = $this->mask_offset_x +$this->img_border_size;
	$this->mask_pos_y = $this->mask_offset_y +$this->img_border_size;
	break;
	case 2:
	// 左下
	$this->mask_pos_x = $this->mask_offset_x +$this->img_border_size;
	$this->mask_pos_y = $this->src_h - $this->mask_h - $this->mask_offset_y;
	break;
	case 3:
	// 右上
	$this->mask_pos_x = $this->src_w - $this->mask_w - $this->mask_offset_x;
	$this->mask_pos_y = $this->mask_offset_y +$this->img_border_size;
	break;
	case 4:
	// 右下
	$this->mask_pos_x = $this->src_w - $this->mask_w - $this->mask_offset_x;
	$this->mask_pos_y = $this->src_h - $this->mask_h - $this->mask_offset_y;
	break;
	default:
	// 默认将水印放到右下,偏移指定像素
	$this->mask_pos_x = $this->src_w - $this->mask_w - $this->mask_offset_x;
	$this->mask_pos_y = $this->src_h - $this->mask_h - $this->mask_offset_y;
	break;
	}
	}
	else
	{
	switch($this->mask_position)
	{
	case 1:
	// 左上
	$this->mask_pos_x = $this->mask_offset_x +$this->img_border_size;
	$this->mask_pos_y = $this->mask_offset_y +$this->img_border_size;
	break;
	case 2:
	// 左下
	$this->mask_pos_x = $this->mask_offset_x +$this->img_border_size;
	$this->mask_pos_y = $this->dst_h - $this->mask_h - $this->mask_offset_y - $this->img_border_size;
	break;
	case 3:
	// 右上
	$this->mask_pos_x = $this->dst_w - $this->mask_w - $this->mask_offset_x - $this->img_border_size;
	$this->mask_pos_y = $this->mask_offset_y +$this->img_border_size;
	break;
	case 4:
	// 右下
	$this->mask_pos_x = $this->dst_w - $this->mask_w - $this->mask_offset_x - $this->img_border_size;
	$this->mask_pos_y = $this->dst_h - $this->mask_h - $this->mask_offset_y - $this->img_border_size;
	break;
	default:
	// 默认将水印放到右下,偏移指定像素
	$this->mask_pos_x = $this->dst_w - $this->mask_w - $this->mask_offset_x - $this->img_border_size;
	$this->mask_pos_y = $this->dst_h - $this->mask_h - $this->mask_offset_y - $this->img_border_size;
	break;
	}
	}
	}

	function _setFontInfo()
	{
	if(is_numeric($this->font))
	{
	$this->font_w = imagefontwidth($this->font);
	$this->font_h = imagefontheight($this->font);
	// 计算水印字体所占宽高
	$word_length = strlen($this->mask_word);
	$this->mask_w = $this->font_w*$word_length;
	$this->mask_h = $this->font_h;
	}
	else
	{
	$arr = imagettfbbox ($this->font_size,0, $this->font,$this->mask_word);
	$this->mask_w = abs($arr[0] - $arr[2]);
	$this->mask_h = abs($arr[7] - $arr[1]);
	}
	}

	function _setNewImgSize($img_w, $img_h=null)
	{
	$num = func_num_args();
	if(1 == $num)
	{
	$this->img_scale = $img_w;// 宽度作为比例
	$this->fill_w = round($this->src_w * $this->img_scale/ 100) - $this->img_border_size*2;
	$this->fill_h = round($this->src_h * $this->img_scale/ 100) - $this->img_border_size*2;
	// 源文件起始坐标
	$this->src_x = 0;
	$this->src_y = 0;
	$this->copy_w = $this->src_w;
	$this->copy_h = $this->src_h;
	// 目标尺寸
	$this->dst_w = $this->fill_w + $this->img_border_size*2;
	$this->dst_h = $this->fill_h + $this->img_border_size*2;
	}
	if(2 == $num)
	{
	$fill_w = (int)$img_w - $this->img_border_size*2;
	$fill_h = (int)$img_h - $this->img_border_size*2;
	if($fill_w < 0 || $fill_h < 0)
	{
	die("图片边框过大，已超过了图片的宽度");
	}
	$rate_w = $this->src_w/$fill_w;
	$rate_h = $this->src_h/$fill_h;
	switch($this->cut_type)
	{
	case 0:
	// 如果原图大于缩略图，产生缩小，否则不缩小
	if($rate_w < 1 && $rate_h < 1)
	{
	$this->fill_w = (int)$this->src_w;
	$this->fill_h = (int)$this->src_h;
	}
	else
	{
	if($rate_w >= $rate_h)
	{
	$this->fill_w = (int)$fill_w;
	$this->fill_h = round($this->src_h/$rate_w);
	}
	else
	{
	$this->fill_w = round($this->src_w/$rate_h);
	$this->fill_h = (int)$fill_h;
	}
	}
	$this->src_x = 0;
	$this->src_y = 0;
	$this->copy_w = $this->src_w;
	$this->copy_h = $this->src_h;
	// 目标尺寸
	$this->dst_w = $this->fill_w + $this->img_border_size*2;
	$this->dst_h = $this->fill_h + $this->img_border_size*2;
	break;
	// 自动裁切
	case 1:
	// 如果图片是缩小剪切才进行操作
	if($rate_w >= 1 && $rate_h >=1)
	{
	if($this->src_w > $this->src_h)
	{
	$src_x = round($this->src_w-$this->src_h)/2;
	$this->setSrcCutPosition($src_x, 0);
	$this->setRectangleCut($fill_h,$fill_h);
	$this->copy_w = $this->src_h;
	$this->copy_h = $this->src_h;
	}
	elseif($this->src_w < $this->src_h)
	{
	$src_y = round($this->src_h-$this->src_w)/2;
	$this->setSrcCutPosition(0, $src_y);
	$this->setRectangleCut($fill_w,$fill_h);
	$this->copy_w = $this->src_w;
	$this->copy_h = $this->src_w;
	}
	else
	{
	$this->setSrcCutPosition(0, 0);
	$this->copy_w = $this->src_w;
	$this->copy_h = $this->src_w;
	$this->setRectangleCut($fill_w,$fill_h);
	}
	}
	else
	{
	$this->setSrcCutPosition(0, 0);
	$this->setRectangleCut($this->src_w,$this->src_h);
	$this->copy_w = $this->src_w;
	$this->copy_h = $this->src_h;
	}
	// 目标尺寸
	$this->dst_w = $this->fill_w + $this->img_border_size*2;
	$this->dst_h = $this->fill_h + $this->img_border_size*2;
	break;
	// 手工裁切
	case 2:
	$this->copy_w = $this->fill_w;
	$this->copy_h = $this->fill_h;
	// 目标尺寸
	$this->dst_w = $this->fill_w + $this->img_border_size*2;
	$this->dst_h = $this->fill_h + $this->img_border_size*2;
	break;
	default:
	break;
	}
	}
	// 目标文件起始坐标
	$this->start_x = $this->img_border_size;
	$this->start_y = $this->img_border_size;
	}

	function _isFull()
	{
	Return ( $this->mask_w + $this->mask_offset_x > $this->fill_w
	|| $this->mask_h + $this->mask_offset_y > $this->fill_h)
	?true:false;
	}

	function _checkMaskValid()
	{
	if( $this->mask_w + $this->mask_offset_x > $this->src_w
	|| $this->mask_h + $this->mask_offset_y > $this->src_h)
	{
	die("水印图片尺寸大于原图，请缩小水印图");
	}
	}

	function _getImgType($file_path)
	{
	$type_list =array("1"=>"gif","2"=>"jpg","3"=>"png","4"=>"swf","5" =>"psd","6"=>"bmp","15"=>"wbmp");
	if(file_exists($file_path))
	{
	$img_info = @getimagesize ($file_path);
	if(isset($type_list[$img_info[2]]))
	{
	Return $type_list[$img_info[2]];
	}
	}
	else
	{
	die("文件不存在,不能取得文件类型!");
	}
	}

	function _checkValid($img_type)
	{
	if(!array_key_exists($img_type, $this->all_type))
	{
	Return false;
	}
	}

	function _mkdirs($path)
	{
	$adir = explode('/',$path);
	$dirlist = '';
	$rootdir = array_shift($adir);
	if(($rootdir!='.'||$rootdir!='..')&&!file_exists($rootdir))
	{
	@mkdir($rootdir);
	}
	foreach($adir as $key=>$val)
	{
	if($val!='.'&&$val!='..')
	{
	$dirlist .= "/".$val;
	$dirpath = $rootdir.$dirlist;
	if(!file_exists($dirpath))
	{
	@mkdir($dirpath);
	@chmod($dirpath,0777);
	}
	}
	}
	}

	function _flipV($src)
	{
	$src_x = $this->getImgWidth($src);
	$src_y = $this->getImgHeight($src);
	$new_im = imagecreatetruecolor($src_x, $src_y);
	for ($y = 0; $y < $src_y; $y++)
	{
	imagecopy($new_im, $src, 0, $src_y - $y - 1, 0, $y,$src_x, 1);
	}
	$this->h_src = $new_im;
	}

	function _flipH($src)
	{
	$src_x = $this->getImgWidth($src);
	$src_y = $this->getImgHeight($src);
	$new_im = imagecreatetruecolor($src_x, $src_y);
	for ($x = 0; $x < $src_x; $x++)
	{
	imagecopy($new_im, $src, $src_x - $x - 1, 0, $x, 0,1, $src_y);
	}
	$this->h_src = $new_im;
	}
}
?>