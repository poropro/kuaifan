<?php
/*
 * cms 搜索模块相关函数
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

function htmlneirong($strtxt, $length = 40, $key = '', $start = 0, $dot = '...'){
	$strtxt = trim($strtxt);
	if (is_null($strtxt)) return;
	$ubbbl = cut_str($strtxt, $length, $start, $dot);
	if (!empty($key)) {
		$_i = strpos($strtxt, $key);
		if (strpos($ubbbl, $key) === false && $_i > 0){
			$ubbblb = substr($strtxt, $_i);
			$_al = get_strlen($ubbbl);
			$_alb = get_strlen($ubbblb);
			if ($_alb >= $_al/2) {
				$ubbbl = cut_str($ubbbl, $_al/2-1).$dot.cut_str($ubbblb, $_alb/2);
			}else{
				$ubbbl = cut_str($ubbbl, $_al-$_alb-1).$dot.$ubbblb;
			}
		}
	}
	$ubbbl = htmlspecialchars(strip_tags($ubbbl));
	$ubbbl = str_replace("[br]","", $ubbbl );
	$ubbbl = str_replace("[page]","", $ubbbl );
	$ubbbl = str_replace("[/page]","", $ubbbl );
	return $ubbbl;
}

function key_color($word, $key, $color='red'){
	if (!empty($word) && !empty($key)){
		$word = preg_replace("/$key/i", "<font color=\"{$color}\">\\0</font>", $word);
	}
	return $word;
}
?>