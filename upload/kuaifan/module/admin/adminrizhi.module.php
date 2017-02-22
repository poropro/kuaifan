<?php
/*
 * 后台日志
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');


if ($_GET['del']){
	if ($admin_val['id']!='1') showmsg("系统提醒", "只有网站创始人才可以操作！");
	if ($_GET['del'] == 'all'){
		if ($_GET['dosubmit']){
			$db->query("Delete from ".table('guanliyuan_rizhi'));
			$links[0]['title'] = '返回列表页面';
			$links[0]['href'] = get_link("del|dosubmit");
			$links[1]['title'] = '返回后台首页';
			$links[1]['href'] = $_admin_indexurl;
			admin_log("删除全部日志！", $admin_val['name']);
			showmsg("系统提醒", "删除全部日志成功！", $links);
		}
		$links[0]['title'] = '确定删除';
		$links[0]['href'] = get_link()."&amp;dosubmit=1";
		$links[1]['title'] = '返回列表页面';
		$links[1]['href'] = get_link("del");
		showmsg("系统提醒", "确定删除所有日志并且不可恢复吗？", $links);
	}else{
		$db->query("Delete from ".table('guanliyuan_rizhi')." WHERE id in ({$_GET['del']})");
		$links[0]['title'] = '返回列表页面';
		$links[0]['href'] = get_link("del|dosubmit");
		$links[1]['title'] = '返回后台首页';
		$links[1]['href'] = $_admin_indexurl;
		admin_log("删除日志ID：{$_GET['del']}！", $admin_val['name']);
		showmsg("系统提醒", "删除日志成功！", $links);
	}
}

?>