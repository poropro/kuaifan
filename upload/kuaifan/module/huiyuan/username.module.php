<?php
/*
 * 设置用户名
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

require(KF_INC_PATH.'denglu.php');
if ($_GET['go_url']){
	$links[0]['title'] = '返回来源地址';
	$links[0]['href'] = str_replace('&','&amp;',urldecode($_GET['go_url']));
}
if (UC_USE == '1'){
	$db -> query("update ".table('huiyuan')." set `regtype`=2 WHERE `userid`={$huiyuan_val['userid']}");
	if ($_GET['go_url']){
		tiaozhuan($links[0]['href']);
	}else{
		tiaozhuan(get_link("vs|sid","&amp;","1")."&amp;m=huiyuan");
	}
}
if ($huiyuan_val['regtype'] != 1) {
	if ($_GET['go_url']){
		tiaozhuan($links[0]['href']);
	}else{
		tiaozhuan(get_link("vs|sid","&amp;","1")."&amp;m=huiyuan");
	}
}
if ($_POST['dosubmit']){
	kf_class::run_sys_func('huiyuan');
	update_username($_POST['username'], $huiyuan_val['userid']);
	$db -> query("update ".table('huiyuan')." set `regtype`=2 WHERE `userid`={$huiyuan_val['userid']}");
	$links[1]['title'] = '返回会员中心';
	$links[1]['href'] = get_link("vs|sid","&amp;","1")."&amp;m=huiyuan";
	$links[2]['title'] = '返回网站首页';
	$links[2]['href'] = kf_url('index');
	showmsg("系统提示", "保存修改成功！", $links);
}

$smarty->assign('huiyuan_val', $huiyuan_val);

?>