<?php
/*
 * 在线统计
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');


if (isset($_GET['tjopen'])){
	$links[0]['title'] = '返回继续';
	$links[0]['href'] = get_link("tjopen");
	$links[1]['title'] = '返回后台首页';
	$links[1]['href'] = $_admin_indexurl;
	!$db->query("UPDATE ".table('peizhi')." SET value='{$_GET['tjopen']}' WHERE name='tjopen'")?showmsg("系统提醒", "设置失败！", $links):"";
	refresh_cache('peizhi');
	showmsg("系统提醒", "设置成功！", $links, $links[0]['href'], 1);
}
if ($_GET['del']){
	if ($admin_val['id']!='1') showmsg("系统提醒", "只有网站创始人才可以操作！");
	if ($_GET['del'] == 'all'){
		if ($_GET['dosubmit']){
			$db->query("Delete from ".table('tongji'));
			$links[0]['title'] = '返回列表页面';
			$links[0]['href'] = get_link("del|dosubmit");
			$links[1]['title'] = '返回后台首页';
			$links[1]['href'] = $_admin_indexurl;
			admin_log("删除全部日志！", $admin_val['name']);
			showmsg("系统提醒", "删除全部统计成功！", $links);
		}
		$links[0]['title'] = '确定删除';
		$links[0]['href'] = get_link()."&amp;dosubmit=1";
		$links[1]['title'] = '返回列表页面';
		$links[1]['href'] = get_link("del");
		showmsg("系统提醒", "确定删除所有统计并且不可恢复吗？", $links);
	}else{
		$db->query("Delete from ".table('tongji')." WHERE id in ({$_GET['del']})");
		$links[0]['title'] = '返回列表页面';
		$links[0]['href'] = get_link("del|dosubmit");
		$links[1]['title'] = '返回后台首页';
		$links[1]['href'] = $_admin_indexurl;
		admin_log("删除统计ID：{$_GET['del']}！", $admin_val['name']);
		showmsg("系统提醒", "删除统计成功！", $links);
	}
}

?>