<?php
/*
 * 下载附件
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

$row = $db -> getone("select * from ".table("neirong_fujian")." WHERE id='{$_GET['xid']}' LIMIT 1");
if (empty($row)) showmsg("提示信息", "此附件不存在！");
$param = kf_class::run_sys_class('param');
if (!$param->get_cookie('fujian_'.$_GET['xid'])) {
	$param->set_cookie('fujian_'.$_GET['xid'], $_GET['xid'], SYS_TIME+3600);
	$db -> query("update ".table("neirong_fujian")." set `down`=down+1 WHERE id='{$_GET['xid']}'");
	$row['down']++;
}

$links[0]['title'] = '返回内容页面';
$links[0]['href'] = get_link("c|xid")."&amp;c=show";
showmsg("提示信息", "此文件被下载{$row['down']}次, 正在加载请稍后...", $links, $row['allurl']);

?>