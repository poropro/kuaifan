<?php
/*
 * 退出后台
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');


if ($_GET['dosubmit']){
	$db -> query("update ".table('guanliyuan')." set `allow` = '".generate_password(25)."' WHERE id='{$admin_val['id']}'");
	$links[0]['title'] = '重新登录';
	$links[0]['href'] = get_link("vs|m","",1)."&amp;c=login";
	$links[1]['title'] = '返回网站首页';
	$links[1]['href'] = kf_url('index');
	admin_log("安全退出后台。", $admin_val['name']);
	showmsg("系统提醒", "已经安全退出后台。", $links);
}
$links[0]['title'] = '返回后台首页';
$links[0]['href'] = $_admin_indexurl;
$_time = '<br/> <a href="'.get_link().'&amp;dosubmit=1">确定退出</a>';
showmsg("系统提醒", "确定安全退出后台并且重置后台标识使原保存的书签失效吗？".$_time, $links);

?>