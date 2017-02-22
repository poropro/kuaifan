<?php
/*
 * 搜索
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

//
$grouplist = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_zu.php');
if (empty($huiyuan_val['groupid'])){
	$group_id = $grouplist[8]; //游客
}else{
	$group_id = $grouplist[$huiyuan_val['groupid']];	
}
if (empty($group_id['allowsearch'])) showmsg("系统提醒", "您所在的用户组没有搜索内容的权利！");
//
if (empty($_REQUEST['key'])){
	$links[1]['title'] = '返回网站首页';
	$links[1]['href'] = kf_url('index');
	showmsg("系统提醒", "请输入您要搜索的关键词...", $links, get_link("c").'&amp;c=sousuo', 1);
}
//
if (!empty($_POST['key'])){
	tiaozhuan(get_link("key").'&amp;key='.$_POST['key']);
}
?>