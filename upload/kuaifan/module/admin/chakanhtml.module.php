<?php
/*
 * 查看源码
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');


if ($_POST['dosubmit']){
	$html = file_get_contents($_POST['html_url']);
	$html = mb_convert_encoding( $html, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5' );
	if ($_POST['html_head']){
		if (strpos($html, "<body") !== false && strpos($html, "</body") !== false){
			$html_arr = explode('<body',$html);
			$html_arr = explode('</body',ltrim(trim($html_arr[1]), '>'));
			$html = trim($html_arr['0']);
		}
		if (strpos($html, "<wml") !== false && strpos($html, "</wml") !== false){
			$html_arr = explode('<wml',$html);
			$html_arr = explode('</wml',ltrim(trim($html_arr[1]), '>'));
			$html = trim($html_arr['0']);
		}
	}
	$smarty->assign('html', $html);
}

?>