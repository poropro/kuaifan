<?php
/*
 * cms ubb公共函数
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

/**
 * UBB标签
 */
function ubb($strtxt, $type1=0, $type2=0){
	$ubbbl = trim($strtxt);
	if (is_null($ubbbl)) return;
	$ubbbl = ubb_ffzf($ubbbl);
	$ubbbl = ubb_fanfuhao($ubbbl);
	$ubbbl = str_replace(">","&gt;",$ubbbl);
	$ubbbl = str_replace("<","&lt;",$ubbbl);
	$ubbbl = str_replace("[tab]","&nbsp;", $ubbbl);
	$ubbbl = str_replace("///","<br/>", $ubbbl);
	$ubbbl = str_replace("[br]","<br/>", $ubbbl);
	$ubbbl = str_replace("[copy]","&copy;", $ubbbl );
	$ubbbl = str_replace("[date]",date("Y-m-d"), $ubbbl );
	$ubbbl = str_replace("[time]",date("H:i:s"), $ubbbl );
	$ubbbl = str_replace("[now]",date("Y-m-d H:i:s"), $ubbbl );
	$ubbbl = str_replace("[miao]",SYS_TIME, $ubbbl );
	$ubbbl = str_replace("[sid]",$_GET['sid'],$ubbbl);
	$ubbbl = str_replace("[vs]",$_GET['vs'],$ubbbl);
	
	$ubbbl = ubb_wml_huiyuan($ubbbl);

	if ($type1==0) $ubbbl = getubbcode($ubbbl);
	if ($type2==0) $ubbbl = getubbcode_2($ubbbl);

	$ubbbl = str_replace("&amp;","&",$ubbbl);
	$ubbbl = str_replace("&","&amp;", $ubbbl );
	$ubbbl = preg_replace("/&amp;([a-z,A-Z,0-9]{2,10});/is","&\\1;",$ubbbl);
	$ubbbl = preg_replace("/\[date\([\'|\"](.+?)[\'|\"]\)\]/es","date('\\1')",$ubbbl);
	$ubbbl = preg_replace("/\[date\((.+?)\)\]/es","date('\\1')",$ubbbl);

	if (US_USERID>0){
		$ubbbl = preg_replace("/\[login\](.+?)\[\/login\]/is","\\1",$ubbbl);
		$ubbbl = preg_replace("/\[nologin\](.+?)\[\/nologin\]/is","",$ubbbl);
	}else{
		$ubbbl = preg_replace("/\[login\](.+?)\[\/login\]/is","",$ubbbl);
		$ubbbl = preg_replace("/\[nologin\](.+?)\[\/nologin\]/is","\\1",$ubbbl);
	}

	$ubbbl = ubb_fanfuhao($ubbbl, 1);
	return $ubbbl;
}

/**
 * WML标签
 */
function wml($strtxt){
	$ubbbl = trim($strtxt);
	if (is_null($ubbbl)) return "";
	$ubbbl = ubb_ffzf($ubbbl);
	$ubbbl = ubb_fanfuhao($ubbbl);
	$ubbbl = str_replace("{sid}",$_GET['sid'],$ubbbl);
	$ubbbl = str_replace("{vs}",$_GET['vs'],$ubbbl);

	$ubbbl = ubb_wml_huiyuan($ubbbl, 'wml');
	$ubbbl = getwmlcode_2($ubbbl);
	
	$ubbbl = preg_replace("/\{date\([\'|\"](.+?)[\'|\"]\)\}/es","date('\\1')",$ubbbl);
	$ubbbl = preg_replace("/\{date\((.+?)\)\}/es","date('\\1')",$ubbbl);
	$ubbbl = preg_replace("/\{ubb\}(.+?)\{\/ubb\}/es","ubb('\\1')",$ubbbl);
	if (US_USERID>0){
		$ubbbl = preg_replace("/\{login\}(.+?)\{\/login\}/is","\\1",$ubbbl);
		$ubbbl = preg_replace("/\{nologin\}(.+?)\{\/nologin\}/is","",$ubbbl);
	}else{
		$ubbbl = preg_replace("/\{login\}(.+?)\{\/login\}/is","",$ubbbl);
		$ubbbl = preg_replace("/\{nologin\}(.+?)\{\/nologin\}/is","\\1",$ubbbl);
	}

	$ubbbl = ubb_fanfuhao($ubbbl, 1);
	return $ubbbl;
}

/**
 * 反斜标识
 */
function ubb_fanfuhao($strtxt, $type = ''){
	$ubbbl = $strtxt;
	$stra = array("\]","\[","\{","\}","\(","\)","\,","\.","\|");
	$strb = array("\*u005d","\*u005b","\*u007b","\*u007d","\*u0028","\*u0029","\*u002c","\*u002e","\*u007c");
	if (empty($type)){
		$ubbbl = str_replace($stra, $strb, $ubbbl);
	}else{
		$stra = array("]","[","{","}","(",")",",",".","|");
		$ubbbl = str_replace($strb, $stra, $ubbbl);
	}
	return $ubbbl;
}

/**
 * 获取会员UBB
 */
function ubb_wml_huiyuan($strtxt, $type = ''){
	global $huiyuan_val;
	$_s = '[';
	$_e = ']';
	if ($type == 'wml'){
		$_s = '{';
		$_e = '}';
	}
	$ubbbl = trim($strtxt);
	$ubbbl = str_replace($_s."userid".$_e, $huiyuan_val['userid'], $ubbbl);
	$ubbbl = str_replace($_s."username".$_e, $huiyuan_val['username'], $ubbbl);
	$ubbbl = str_replace($_s."nickname".$_e, $huiyuan_val['nickname'], $ubbbl);
	$ubbbl = str_replace($_s."colorname".$_e, colorname($huiyuan_val), $ubbbl);
	$ubbbl = str_replace($_s."onlycolorname".$_e, onlycolorname($huiyuan_val), $ubbbl);
	$ubbbl = str_replace($_s."loginnum".$_e,$huiyuan_val['loginnum'],$ubbbl);
	$ubbbl = str_replace($_s."email".$_e,$huiyuan_val['email'],$ubbbl);
	$ubbbl = str_replace($_s."amount".$_e,$huiyuan_val['amount'],$ubbbl);
	$ubbbl = str_replace($_s."point".$_e,$huiyuan_val['point'],$ubbbl);
	$ubbbl = str_replace($_s."mobile".$_e,$huiyuan_val['mobile'],$ubbbl);
	return $ubbbl;
}
/**
 * 纯文本标签
 */
function ubbwenben($strtxt){
	$ubbbl = trim($strtxt);
	if (is_null($ubbbl)) return;
	$ubbbl = ubb_ffzf($ubbbl);
	$ubbbl = str_replace("&amp;","&",$ubbbl);
	$ubbbl = str_replace("&","&amp;", $ubbbl );
	$ubbbl = str_replace(">","&gt;",$ubbbl);
	$ubbbl = str_replace("<","&lt;",$ubbbl);
	$ubbbl = str_replace("“","&ldquo;", $ubbbl );
	$ubbbl = str_replace("”","&rdquo;", $ubbbl );
	$ubbbl = str_replace("'","&apos;", $ubbbl );
	$ubbbl = str_replace("\"","&quot;", $ubbbl );
	return $ubbbl;
}

/**
 * UBB拓展标签1
 */
function getubbcode($strtxt){
	global $_CFG;
	$ubbbl = trim($strtxt);
	if (is_null($ubbbl)) return;
	$ubbbl = str_replace("file:","file :", $ubbbl );
	$ubbbl = str_replace("files:","files :", $ubbbl );
	$ubbbl = str_replace("script:","script :", $ubbbl );
	$ubbbl = str_replace("js:","js :", $ubbbl );

	$ubbbl = preg_replace("/\[img\](.+?)\[\/img\]/is","<img src=\"\\1\" alt=\".\"/>",$ubbbl);
	$ubbbl = preg_replace("/\[img=(.+?)\](.+?)\[\/img\]/is","<img src=\"\\1\" alt=\"\\2\"/>",$ubbbl);

	$ubbbl = preg_replace("/\[url\](.+?)\[\/url\]/is","<a href=\"\\1\">\\1</a>",$ubbbl);
	$ubbbl = preg_replace("/\[url=(.+?)\](.+?)\[\/url\]/is","<a href=\"\\1\">\\2</a>",$ubbbl);

	$ubbbl = preg_replace("/\[call\](.+?)\[\/call\]/is","<a href=\"wtai://wp/mc;\\1\">\\1</a>",$ubbbl);
	$ubbbl = preg_replace("/\[call=(.+?)\](.+?)\[\/call\]/is","<a href=\"wtai://wp/mc;\\1\">\\2</a>",$ubbbl);

	$ubbbl = preg_replace("/\[i\](.+?)\[\/i\]/is","<i>\\1</i>",$ubbbl);
	$ubbbl = preg_replace("/\[u\](.+?)\[\/u\]/is","<u>\\1</u>",$ubbbl);
	$ubbbl = preg_replace("/\[b\](.+?)\[\/b\]/is","<b>\\1</b>",$ubbbl);
	$ubbbl = preg_replace("/\[big\](.+?)\[\/big\]/is","<big>\\1</big>",$ubbbl);
	$ubbbl = preg_replace("/\[small\](.+?)\[\/small\]/is","<small>\\1</small>",$ubbbl);
	$ubbbl = preg_replace("/\[center\](.+?)\[\/center\]/is","<center>\\1</center>",$ubbbl);

	$ubbbl = preg_replace("/\[div=(.+?)\]/is","<div class=\"\\1\">",$ubbbl);
	$ubbbl = str_replace("[/div]","</div>", $ubbbl );
	$ubbbl = preg_replace("/\[span=(.+?)\]/is","<span class=\"\\1\">",$ubbbl);
	$ubbbl = str_replace("[/span]","</span>", $ubbbl );
	$ubbbl = preg_replace("/\[ul=(.+?)\]/is","<ul class=\"\\1\">",$ubbbl);
	$ubbbl = str_replace("[/ul]","</ul>", $ubbbl );
	$ubbbl = preg_replace("/\[li=(.+?)\]/is","<li class=\"\\1\">",$ubbbl);
	$ubbbl = str_replace("[/li]","</li>", $ubbbl );

	$ubbbl = preg_replace("/\[color=(.+?)\](.+?)\[\/color\]/is","<font color=\"\\1\">\\2</font>",$ubbbl);
	$ubbbl = preg_replace("/\[font=(.+?)\](.+?)\[\/font\]/is","<font face=\"\\1\">\\2</font>",$ubbbl);
	$ubbbl = preg_replace("/\[size=(.+?)\](.+?)\[\/size\]/is","<font size=\"\\1\">\\2</font>",$ubbbl);
	$ubbbl = preg_replace("/\[sup\](.+?)\[\/sup\]/is","<sup>\\1</sup>",$ubbbl);
	$ubbbl = preg_replace("/\[sub\](.+?)\[\/sub\]/is","<sub>\\1</sub>",$ubbbl);
	$ubbbl = preg_replace("/\[pre\](.+?)\[\/pre\]/is","<pre>\\1</pre>",$ubbbl);
	$ubbbl = preg_replace("/\[strike\](.+?)\[\/strike\]/is","<strike>\\1</strike>",$ubbbl);
	$ubbbl = preg_replace("/\[email\](.+?)\[\/email\]/is","<a href=\"mailto:\\1\">\\1</a>",$ubbbl);
	$ubbbl = preg_replace("/\[quote\](.+?)\[\/quote\]/is","<blockquote><font size='1' face='Courier New'>quote:</font><hr>\\1<hr></blockquote>",  $ubbbl);
	$ubbbl = preg_replace("/\[index\](.+?)\[\/index\]/is","<a href=\"index.php?m=".$_GET['m']."&amp;sid=".$_GET['sid']."\">\\1</a>",  $ubbbl);
	$ubbbl = preg_replace("/\[sel\](.+?)\[\/sel\]/is","<select name=\"zhuandiao\" onchange=\"javascript:location.href=this.value;\">\\1</select>",  $ubbbl);
	$ubbbl = preg_replace("/\[option\](.+?)\[\/option\]/is","<option>\\1</option>",  $ubbbl);
	$ubbbl = preg_replace("/\[option=(.+?)\](.+?)\[\/option\]/is","<option value=\"\\1\">\\2</option>",$ubbbl);
	$ubbbl = preg_replace("/\[optionv=(.+?)\](.+?)\[\/optionv\]/is","<option value=\"\\1\">\\2</option>",$ubbbl);
	$ubbbl = preg_replace("/\[selv=(.+?)\](.+?)\[\/selv\]/is","<select name=\"\\1\">\\2</select>",$ubbbl);

	$ubbbl = str_replace("[HR]","<hr/>", $ubbbl );
	$ubbbl = preg_replace("/\[anchor=(.+?)\](.+?)\[\/anchor\]/is","<a href=\"\\1\" style=\"text-decoration:none;\">\\2</a>",$ubbbl);
	$ubbbl = preg_replace("/\[ag=(.+?)\](.+?)\[\/ag\]/is","<div align=\"\\1\">\\2</div>",$ubbbl);
	$ubbbl = preg_replace("/\[back\](.+?)\[\/back\]/is","<a href=\"javascript:onclick=history.go(-1)\">\\1</a>",$ubbbl);
	$ubbbl = preg_replace("/\[backcolor=(.+?)\](.+?)\[\/backcolor\]/is","<span style=\"background-color: \\1\">\\2</span>",$ubbbl);
	$ubbbl = preg_replace("/\[fly\](.+?)\[\/fly\]/is","<marquee width=\"100%\" behavior=\"alternate\" scrollamount=\"3\"'>\\1</marquee>",  $ubbbl);

	return $ubbbl;
}

/**
 * UBB拓展标签2
 */
function getubbcode_2($strtxt){
	$ubbbl = trim($strtxt);
	if (is_null($ubbbl)) return "";
	//未读信箱
	if (strpos($ubbbl,"[weidu=")){
		$ubbbl = preg_replace("/\[weidu=(.+?)\]/es","weiduxx('\\1')",$ubbbl);
	}
	//在线会员数
	if (strpos($ubbbl,"[/zx]")){
		$ubbbl = preg_replace("/\[zx\](.+?)\[\/zx\]/es","zxhys('\\1')",$ubbbl);
	}
	//随机数
	if (strpos($ubbbl,"[/sjs]")){
		$ubbbl = preg_replace("/\[sjs\](.+?)\[\/sjs\]/es","ubb_sjs('\\1')",$ubbbl);
	}
	//随机链接
	if (strpos($ubbbl,"[/rndurl]")){
		$ubbbl = preg_replace("/\[rndurl\](.+?)\[\/rndurl\]/es","ubb_rnd('\\1','',1)",$ubbbl);
		$ubbbl = preg_replace("/\[rndurl=(.+?)\](.+?)\[\/rndurl\]/es","ubb_rnd('\\1','\\2',1)",$ubbbl);
	}
	//随机图片
	if (strpos($ubbbl,"[/rndimg]")){
		$ubbbl = preg_replace("/\[rndimg\](.+?)\[\/rndimg\]/es","ubb_rnd('\\1','',2)",$ubbbl);
		$ubbbl = preg_replace("/\[rndimg=(.+?)\](.+?)\[\/rndimg\]/es","ubb_rnd('\\1','\\2',2)",$ubbbl);
	}
	//随机文字
	if (strpos($ubbbl,"[/rndtxt]")){
		$ubbbl = preg_replace("/\[rndtxt\](.+?)\[\/rndtxt\]/es","ubb_rnd('\\1','',3)",$ubbbl);
		$ubbbl = preg_replace("/\[rndtxt=(.+?)\](.+?)\[\/rndtxt\]/es","ubb_rnd('\\1','\\2',3)",$ubbbl);
	}
	//倒计时
	if (strpos($ubbbl,"[/codo]")){
		$ubbbl = preg_replace("/\[codo\](.+?)\[\/codo\]/es","ubb_codo('\\1','')",$ubbbl);
		$ubbbl = preg_replace("/\[codo=(.+?)\](.+?)\[\/codo\]/es","ubb_codo('\\2','\\1')",$ubbbl);
	}
	//友情链接
	if (strpos($ubbbl,"[/lianjie]")){
    	kf_class::run_sys_func('lianjie');
    	$ubbbl = preg_replace("/\[lianjie\](.+?)\[\/lianjie\]/es","ubb_lianjie('\\1')",$ubbbl);
	}
	//广告系统
	if (strpos($ubbbl,"[/guanggao]")){
    	kf_class::run_sys_func('guanggao');
    	$ubbbl = preg_replace("/\[guanggao\](.+?)\[\/guanggao\]/es","ubb_guanggao('\\1')",$ubbbl);
	}
	//版本显示
	if (strpos($ubbbl,"[/wap]")){
		$ubbbl = preg_replace("/\[wap=(.+?)\](.+?)\[\/wap\]/es","ubb_wap('\\2','\\1',1)",$ubbbl);
	}
	return $ubbbl;
}

/**
 * WML拓展标签
 */
function getwmlcode_2($strtxt){
	$ubbbl = trim($strtxt);
	if (is_null($ubbbl)) return "";
	//未读信箱
	if (strpos($ubbbl,"{weidu=")){
		$ubbbl = preg_replace("/\{weidu=(.+?)\}/es","weiduxx('\\1')",$ubbbl);
	}
	//在线会员数
	if (strpos($ubbbl,"{/zx}")){
		$ubbbl = preg_replace("/\{zx\}(.+?)\{\/zx\}/es","zxhys('\\1')",$ubbbl);
	}
	//随机数
	if (strpos($ubbbl,"{/sjs}")){
		$ubbbl = preg_replace("/\{sjs\}(.+?)\{\/sjs\}/es","ubb_sjs('\\1')",$ubbbl);
	}
	//随机链接
	if (strpos($ubbbl,"{/rndurl}")){
		$ubbbl = preg_replace("/\{rndurl\}(.+?)\{\/rndurl\}/es","ubb_rnd('\\1','',1)",$ubbbl);
		$ubbbl = preg_replace("/\{rndurl=(.+?)\}(.+?)\{\/rndurl\}/es","ubb_rnd('\\1','\\2',1)",$ubbbl);
	}
	//随机图片
	if (strpos($ubbbl,"{/rndimg}")){
		$ubbbl = preg_replace("/\{rndimg\}(.+?)\{\/rndimg\}/es","ubb_rnd('\\1','',2)",$ubbbl);
		$ubbbl = preg_replace("/\{rndimg=(.+?)\}(.+?)\{\/rndimg\}/es","ubb_rnd('\\1','\\2',2)",$ubbbl);
	}
	//随机文字
	if (strpos($ubbbl,"{/rndtxt}")){
		$ubbbl = preg_replace("/\{rndtxt\}(.+?)\{\/rndtxt\}/es","ubb_rnd('\\1','',3)",$ubbbl);
		$ubbbl = preg_replace("/\{rndtxt=(.+?)\}(.+?)\{\/rndtxt\}/es","ubb_rnd('\\1','\\2',3)",$ubbbl);
	}
	//倒计时
	if (strpos($ubbbl,"{/codo}")){
		$ubbbl = preg_replace("/\{codo\}(.+?)\{\/codo\}/es","ubb_codo('\\1','')",$ubbbl);
		$ubbbl = preg_replace("/\{codo=(.+?)\}(.+?)\{\/codo\}/es","ubb_codo('\\2','\\1')",$ubbbl);
	}
	//友情链接
	if (strpos($ubbbl,"{/lianjie}")){
    	kf_class::run_sys_func('lianjie');
    	$ubbbl = preg_replace("/\{lianjie\}(.+?)\{\/lianjie\}/es","ubb_lianjie('\\1')",$ubbbl);
	}
	//广告系统
	if (strpos($ubbbl,"{/guanggao}")){
    	kf_class::run_sys_func('guanggao');
    	$ubbbl = preg_replace("/\{guanggao\}(.+?)\{\/guanggao\}/es","ubb_guanggao('\\1')",$ubbbl);
	}
	//版本显示
	if (strpos($ubbbl,"{/wap}")){
		$ubbbl = preg_replace("/\{wap=(.+?)\}(.+?)\{\/wap\}/es","ubb_wap('\\2','\\1',1)",$ubbbl);
	}
	return $ubbbl;
}


/**
 * 随机整数UBB
 */
function ubb_sjs($str){
	if (is_null($str)) return "";
	$strarr = explode(',', $str);
	return mt_rand(intval($strarr[0]), intval($strarr[1]));
}

/**
 * 随机UBB标签  strc：1链接、2图片、3文字
 */
function ubb_rnd($stra, $strb = '', $strc = '1'){
	if (is_null($stra)) return "";
	$stra = str_replace("\|","\u007c",$stra);
	$stra_arr = explode('|',$stra);
	$___suiji = rand(0,count($stra_arr)-1);
	$stra_val = $stra_arr[$___suiji];
	$stra_val = str_replace("\u007c","\|",$stra_val);
	if ($strb) {
		$strb = str_replace("\|","\u007c",$strb);
		$strb_arr = explode('|',$strb);
		$strb_val = $strb_arr[$___suiji];
		$strb_val = str_replace("\u007c","\|",$strb_val);
	}if (!$strb_val) $strb_val = $stra_val;

	if ($strc == '2'){
		$rndtxt = $strb?"<img src=\"{$stra_val}\" alt=\"{$strb_val}\"/>":"<img src=\"{$stra_val}\"/>";
	}elseif ($strc == '3'){
		$rndtxt = $stra_val;
	}else{
		$rndtxt = "<a href=\"{$stra_val}\">{$strb_val}</a>";
	}
	return $rndtxt;
}

/**
 * 倒计时 strb：1小时、2分钟、3秒、默认0天
 */
function ubb_codo($stra, $strb = ''){
	$stra = trim($stra);
	if (is_null($stra)) return "";
	$rndtxt = intval(strtotime($stra)-strtotime(date("Y-m-d H:i:s")));
	if ($rndtxt<0) $rndtxt = $rndtxt*(-1);
	if ($strb == "1"){
		$rndtxt = intval($rndtxt/3600);
	}elseif ($strb == "2"){
		$rndtxt = intval($rndtxt/60);
	}elseif ($strb == "3"){
		$rndtxt = intval($rndtxt);
	}else{
		$rndtxt = intval($rndtxt/86400);
	}
	return $rndtxt;
}

/**
 * 内容指定版本显示
 * @param 要显示的内容 $_str
 * @param 显示的版本 $_v
 */
function ubb_wap($_str, $_v, $isformdata = 0){
	if (!in_array($_GET['vs'],explode(',',$_v))) return '';
	if ($isformdata) $_str = stripslashes($_str);
	return $_str;
}
/**
 * 过滤非法字符标签
 */
function ubb_ffzf($strtxt){
	$ubbbl = trim($strtxt);
	if (is_null($ubbbl)) return "";
	$ubbbl = str_replace("无界浏览器","*",$ubbbl);
	$ubbbl = str_replace("特码","*",$ubbbl);
	$ubbbl = str_replace("法轮功","*",$ubbbl);
	$ubbbl = str_replace("法轮大法","*",$ubbbl);
	$ubbbl = str_replace("李洪志","*",$ubbbl);
	$ubbbl = str_replace("大法弟子","*",$ubbbl);
	$ubbbl = str_replace("大纪元","*",$ubbbl);
	$ubbbl = str_replace("六合","*",$ubbbl);
	$ubbbl = str_replace("真善忍","*",$ubbbl);
	$ubbbl = str_replace("修炼大法","*",$ubbbl);
	$ubbbl = str_replace("反共","*",$ubbbl);
	$ubbbl = str_replace("台独","*",$ubbbl);
	$ubbbl = str_replace("反革命","*",$ubbbl);
	$ubbbl = str_replace("疆独","*",$ubbbl);
	$ubbbl = str_replace("藏独","*",$ubbbl);
	$ubbbl = str_replace("多党执政","*",$ubbbl);
	$ubbbl = str_replace("江泽民","*",$ubbbl);
	$ubbbl = str_replace("胡锦涛","*",$ubbbl);
	$ubbbl = str_replace("罗干","*",$ubbbl);
	$ubbbl = str_replace("镕基","*",$ubbbl);
	$ubbbl = str_replace("李鹏","*",$ubbbl);
	$ubbbl = str_replace("温家宝","*",$ubbbl);
	$ubbbl = str_replace("温家堡","*",$ubbbl);
	$ubbbl = str_replace("六四","*",$ubbbl);
	$ubbbl = str_replace("玉蒲团","*",$ubbbl);
	$ubbbl = str_replace("素女自拍","*",$ubbbl);
	$ubbbl = str_replace("成人片","*",$ubbbl);
	$ubbbl = str_replace("成人电影","*",$ubbbl);
	$ubbbl = str_replace("激情图片","*",$ubbbl);
	$ubbbl = str_replace("激情电影","*",$ubbbl);
	$ubbbl = str_replace("成人小说","*",$ubbbl);
	$ubbbl = str_replace("肉棍","*",$ubbbl);
	$ubbbl = str_replace("淫水","*",$ubbbl);
	$ubbbl = str_replace("乱伦","*",$ubbbl);
	$ubbbl = str_replace("嫖鸡","*",$ubbbl);
	$ubbbl = str_replace("小穴","*",$ubbbl);
	$ubbbl = str_replace("换妻","*",$ubbbl);
	$ubbbl = str_replace("淫魔","*",$ubbbl);
	$ubbbl = str_replace("淫女","*",$ubbbl);
	$ubbbl = str_replace("淫靡","*",$ubbbl);
	$ubbbl = str_replace("口交","*",$ubbbl);
	$ubbbl = str_replace("迷药","*",$ubbbl);
	$ubbbl = str_replace("迷昏药","*",$ubbbl);
	$ubbbl = str_replace("窃听器","*",$ubbbl);
	$ubbbl = str_replace("六合彩","*",$ubbbl);
	$ubbbl = str_replace("买卖枪支","*",$ubbbl);
	$ubbbl = str_replace("退党","*",$ubbbl);
	$ubbbl = str_replace("麻醉药","*",$ubbbl);
	$ubbbl = str_replace("短信群发器","*",$ubbbl);
	$ubbbl = str_replace("色情服务","*",$ubbbl);
	$ubbbl = str_replace("出售枪支","*",$ubbbl);
	$ubbbl = str_replace("摇头丸","*",$ubbbl);
	$ubbbl = str_replace("出售假币","*",$ubbbl);
	$ubbbl = str_replace("监听王","*",$ubbbl);
	$ubbbl = str_replace("蒙汗","*",$ubbbl);
	$ubbbl = str_replace("迷奸药","*",$ubbbl);
	$ubbbl = str_replace("催情药","*",$ubbbl);
	$ubbbl = str_replace("办理证件","*",$ubbbl);
	$ubbbl = str_replace("反政府","*",$ubbbl);
	$ubbbl = str_replace("乱伦","*",$ubbbl);
	$ubbbl = str_replace("待开发票","*",$ubbbl);
	$ubbbl = str_replace("代开发票","*",$ubbbl);
	$ubbbl = str_replace("老虎机","*",$ubbbl);
	$ubbbl = str_replace("换妻","*",$ubbbl);
	$ubbbl = str_replace("曾道人","*",$ubbbl);
	$ubbbl = str_replace("仿真假钞","*",$ubbbl);
	$ubbbl = str_replace("裸聊","*",$ubbbl);
	return $ubbbl;
}
/**
 * 提交表单textarea换行符替换
 */
function ubb_textarea($strtxt){
	$ubbbl = trim($strtxt);
	if (is_null($ubbbl)) return;
	$ubbbl = str_replace("\r","", $ubbbl);
	$ubbbl = str_replace("\n","<br/>", $ubbbl);
	return $ubbbl;
}
/**
 * UBB替换链接
 */
function ubb_link($strtxt){
	$ubbbl = trim($strtxt);
	if (is_null($ubbbl)) return "";
	$ubbbl = str_replace("{sid}",$_GET['sid'],$ubbbl);
	$ubbbl = str_replace("[sid]",$_GET['sid'],$ubbbl);
	$ubbbl = str_replace("{vs}",$_GET['vs'],$ubbbl);
	$ubbbl = str_replace("[vs]",$_GET['vs'],$ubbbl);
	$ubbbl = htmlspecialchars($ubbbl);
	$ubbbl = preg_replace("/&amp;([a-z,A-Z,0-9]{2,10});/is","&\\1;",$ubbbl);
	return $ubbbl;
}

function ubb_qianming($strtxt, $num=0){
	$ubbbl = trim($strtxt);
	if (is_null($ubbbl)) return;
	$ubbbl = ubb_ffzf($ubbbl);
	$ubbbl = ubb_fanfuhao($ubbbl);
	$limit = (empty($num))?-1:$num;
	$ubbbl = preg_replace("/\[br\]/is","<br/>", $ubbbl, $limit, $count);
	if ($num > 0) $limit = $limit - $count;
	$ubbbl = preg_replace("/\[copy\]/is","&copy;", $ubbbl, $limit, $count);
	if ($num > 0) $limit = $limit - $count;
	$ubbbl = preg_replace("/\[date\]/is",date("Y-m-d"), $ubbbl, $limit, $count);
	if ($num > 0) $limit = $limit - $count;
	$ubbbl = preg_replace("/\[time\]/is",date("H:i:s"), $ubbbl, $limit, $count);
	if ($num > 0) $limit = $limit - $count;
	$ubbbl = preg_replace("/\[now\]/is",date("Y-m-d H:i:s"), $ubbbl, $limit, $count);
	if ($num > 0) $limit = $limit - $count;
	
	$ubbbl = str_replace("[sid]",$_GET['sid'],$ubbbl);
	$ubbbl = str_replace("[vs]",$_GET['vs'],$ubbbl);

	$ubbbl = str_replace("&amp;","&",$ubbbl);
	$ubbbl = str_replace("&","&amp;", $ubbbl );

	$ubbbl = preg_replace("/&amp;([a-z,A-Z,0-9]{2,10});/is","&\\1;", $ubbbl, $limit, $count);
	if ($num > 0) $limit = $limit - $count;
	
	$ubbbl = preg_replace("/\[date\([\'|\"](.+?)[\'|\"]\)\]/es","date('\\1')",$ubbbl, $limit, $count);
	if ($num > 0) $limit = $limit - $count;
	$ubbbl = preg_replace("/\[date\((.+?)\)\]/es","date('\\1')",$ubbbl, $limit, $count);
	if ($num > 0) $limit = $limit - $count;

	$ubbbl = preg_replace("/\[img\](.+?)\[\/img\]/is","<img src=\"\\1\" alt=\".\"/>",$ubbbl, $limit, $count);
	if ($num > 0) $limit = $limit - $count;
	$ubbbl = preg_replace("/\[img=(.+?)\](.+?)\[\/img\]/is","<img src=\"\\1\" alt=\"\\2\"/>",$ubbbl, $limit, $count);
	if ($num > 0) $limit = $limit - $count;

	$ubbbl = preg_replace("/\[url\](.+?)\[\/url\]/is","<a href=\"\\1\">\\1</a>",$ubbbl, $limit, $count);
	if ($num > 0) $limit = $limit - $count;
	$ubbbl = preg_replace("/\[url=(.+?)\](.+?)\[\/url\]/is","<a href=\"\\1\">\\2</a>",$ubbbl, $limit, $count);
	if ($num > 0) $limit = $limit - $count;

	$ubbbl = preg_replace("/\[call\](.+?)\[\/call\]/is","<a href=\"wtai://wp/mc;\\1\">\\1</a>",$ubbbl, $limit, $count);
	if ($num > 0) $limit = $limit - $count;
	$ubbbl = preg_replace("/\[call=(.+?)\](.+?)\[\/call\]/is","<a href=\"wtai://wp/mc;\\1\">\\2</a>",$ubbbl, $limit, $count);
	if ($num > 0) $limit = $limit - $count;

	$ubbbl = preg_replace("/\[i\](.+?)\[\/i\]/is","<i>\\1</i>",$ubbbl, $limit, $count);
	if ($num > 0) $limit = $limit - $count;
	$ubbbl = preg_replace("/\[u\](.+?)\[\/u\]/is","<u>\\1</u>",$ubbbl, $limit, $count);
	if ($num > 0) $limit = $limit - $count;
	$ubbbl = preg_replace("/\[b\](.+?)\[\/b\]/is","<b>\\1</b>",$ubbbl, $limit, $count);
	if ($num > 0) $limit = $limit - $count;
	$ubbbl = preg_replace("/\[big\](.+?)\[\/big\]/is","<big>\\1</big>",$ubbbl, $limit, $count);
	if ($num > 0) $limit = $limit - $count;
	$ubbbl = preg_replace("/\[small\](.+?)\[\/small\]/is","<small>\\1</small>",$ubbbl, $limit, $count);
	if ($num > 0) $limit = $limit - $count;
	$ubbbl = preg_replace("/\[center\](.+?)\[\/center\]/is","<center>\\1</center>",$ubbbl, $limit, $count);
	if ($num > 0) $limit = $limit - $count;

	$ubbbl = ubb_fanfuhao($ubbbl, 1);
	return $ubbbl;
}
/**
 * 内嵌广告
 * @param 要替换的内容 $strtxt
 * @param 类型0内容，1评论 $type
 */
function ubb_neirong_guanggao($strtxt, $type = 0){
	static $nr_funcs = array();
	$ubbbl = trim($strtxt);
	if (is_null($ubbbl)) return;
	$path = "caches/cache_neirong_guanggao.php";
	$key = md5($path);
	if (!isset($nr_funcs[$key])){
		if (file_exists(KF_ROOT_PATH.$path)) {
			include KF_ROOT_PATH.$path;
			$nr_funcs[$key] = true;
		} else {
			$nr_funcs[$key] = false;
		}
	}
	if ($nr_funcs[$key] == false){
		return $ubbbl;
	}
	if ($type == 1){
		$ubbbl = __ubb_pinglun_guanggao($ubbbl);
	}else{
		$ubbbl = __ubb_neirong_guanggao($ubbbl);
	}
	return $ubbbl;
}
?>