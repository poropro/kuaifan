<?php
/*
 * 注册条款
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

$row = $db->getone("select * from ".table('peizhi_mokuai')." where module='huiyuan' LIMIT 1");
$member_setting = string2array($row['setting']);
if (empty($member_setting['showregprotocol'])){
	showmsg("系统提示", "没有添加任何条款！");
}
if (empty($member_setting['regprotocol'])){
	showmsg("系统提示", "没有添加任何条款！");
}else{
	$smarty->assign('member_setting', $member_setting);
}

?>