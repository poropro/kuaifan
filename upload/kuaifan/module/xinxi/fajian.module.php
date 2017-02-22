<?php
/*
 * 发件箱
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

if ($_GET['del'] == 'all'){
	$whereval = "send_from_id = '{$huiyuan_val['username']}'";
	if ($_GET['dosubmit']){
		$db -> query("update ".table('xinxi')." set `del_type`='1' WHERE {$whereval}");
		$links[0]['title'] = '返回发件列表';
		$links[0]['href'] = get_link("del|dosubmit");
		$links[1]['title'] = '返回会员中心';
		$links[1]['href'] = get_link("vs|sid",'','1').'&amp;m=huiyuan&amp;c=index';
		showmsg("系统提醒", "删除所有发件成功！", $links);
	}
	$links[0]['title'] = '确定删除';
	$links[0]['href'] = get_link()."&amp;dosubmit=1";
	$links[1]['title'] = '返回发件列表';
	$links[1]['href'] = get_link("del");
	showmsg("系统提醒", "确定删除所有发件并且不可恢复吗？", $links);
}elseif (!empty($_GET['del'])){
	$whereval = "messageid in ({$_GET['del']}) AND send_from_id = '{$huiyuan_val['username']}'";
	$db -> query("update ".table('xinxi')." set `del_type`='1' WHERE {$whereval}");
	$links[0]['title'] = '返回发件列表';
	$links[0]['href'] = get_link("del|dosubmit");
	$links[1]['title'] = '返回会员中心';
	$links[1]['href'] = get_link("vs|sid",'','1').'&amp;m=huiyuan&amp;c=index';
	showmsg("系统提醒", "删除发件成功！", $links);
}
$smarty->assign('username', $huiyuan_val['username']);

?>