<?php
/*
 * 删除账户记录
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

$wheresql = "del=0 AND userid='{$huiyuan_val['userid']}' AND id='{$_GET['id']}'";
$row = $db->getone("select * from ".table('jiangfa')." WHERE {$wheresql} LIMIT 1");
if (empty($row)) showmsg("系统提醒", "此记录已经不存在！");
if ($_GET['dosubmit']){
	$db -> query("update ".table('jiangfa')." set `del` = '1' WHERE {$wheresql}");
	$links[0]['title'] = '返回记录列表';
	$links[0]['href'] = get_link("c|id|dosubmit").'&amp;c=jilu';
	$links[1]['title'] = '返回会员中心';
	$links[1]['href'] = get_link("vs|sid",'','1').'&amp;m=huiyuan&amp;c=index';
	showmsg("系统提醒", "删除成功！", $links);
}
$links[0]['title'] = '返回详情';
$links[0]['href'] = get_link("c").'&amp;c=jilu';
$links[1]['title'] = '返回记录列表';
$links[1]['href'] = get_link("c|id").'&amp;c=jilu';
$_time = '<br/> <a href="'.get_link().'&amp;dosubmit=1">确定删除</a>';
showmsg("系统提醒", "你确定删除此记录吗？".$_time, $links);

?>