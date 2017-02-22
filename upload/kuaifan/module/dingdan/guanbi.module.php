<?php
/*
 * 关闭订单
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

$row = $db->getone("select * from ".table('dingdan')." WHERE userid='{$huiyuan_val['userid']}' AND id='{$_GET['id']}' LIMIT 1");
if (empty($row)) showmsg("系统提醒", "此订单已经不存在！");
if ($row['status']!='0'){
	showmsg("系统提醒", "此订单不在可关闭状态！");
}
if ($_POST['dosubmit']){
	if (empty($_POST['status_close'])){
		showmsg("系统提醒", "请输入关闭订单理由！");
	}
	dingdan_set($row['id'], 99, $_POST['status_close']);
		
	$links[0]['title'] = '回我的订单';
	$links[0]['href'] = get_link('c|id');
	$links[1]['title'] = '返回会员中心';
	$links[1]['href'] = get_link("vs|sid",'','1').'&amp;m=huiyuan&amp;c=index';
	showmsg("系统提醒", "关闭成功！", $links);
}

?>