<?php
 /*
 * 神州行充值系统
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
if(!defined('IN_KUAIFAN')) exit('Access Denied!');
require(KF_INC_PATH.'denglu_admin.php');
require "function.php";

if (!empty($_POST['dosubmit'])){
	$url = SYS_URL."?m=yinhangbaocun&keyval={$row['key']}";
	$url.= "&shouji=".urlencode($_POST['shouji']);
	$url.= "&qq=".urlencode($_POST['qq']);
	$url.= "&yinhang=".urlencode($_POST['yinhang']);
	$url.= "&zhanghao=".urlencode($_POST['zhanghao']);
	$url.= "&kaihuming=".urlencode($_POST['kaihuming']);
	$url.= "&kaihuhang=".urlencode($_POST['kaihuhang']);
	$html = chongzhi_open($url, 'keyidstart', 'keyidend');
	if ($html<1){
		showmsg("系统提示", "保存失败！");
	}else{
		showmsg("系统提示", "保存成功！");
	}
}
$html = chongzhi_open(SYS_URL."?m=yinhang&keyval={$row['key']}", 'keyidstart', 'keyidend');
$smarty->assign('htmlarr', string2array($html));
?>