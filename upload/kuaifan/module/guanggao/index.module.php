<?php
/*
 * 广告点击统计
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

$row = $db->getone("select * from ".table('guanggao')." WHERE id='{$_GET['id']}' LIMIT 1");
if (empty($row)) showmsg("系统提醒", "内容不存在！");
$row['setting'] = string2array($row['setting']);
$param = kf_class::run_sys_class('param');
if (!$param->get_cookie('guanggao_'.$_GET['id'])) {
	//增加点击
	$db -> query("update ".table('guanggao')." set clicks=clicks+1,clickstime='".SYS_TIME."' WHERE id='{$_GET['id']}'");
	//会员点击奖励部分
	if ($row['setting']['setmoney'] > 0 && US_USERID > 0){
		$rowhy = $db->getone("select * from ".table('guanggao_data')." WHERE `userid`='".US_USERID."' AND `id`='".$row['id']."' ORDER BY `inputtime` DESC");
		if (date("Ymd", $rowhy['inputtime']) != date("Ymd", SYS_TIME)){
			kf_class::run_sys_func('huiyuan');
			set_jiangfa(US_USERID, $row['setting']['setmoney'], $row['setting']['setmoney_type'], "点击广告:".$row['title']);
		}
	}
	//统计详情
	$_array = array();
	$_array['id'] = $row['id'];
	$_array['catid'] = $row['catid'];
	$_array['ip'] = $online_ip;
	$_array['userid'] = US_USERID;
	$_array['username'] = US_USERNAME;
	$_array['inputtime'] = SYS_TIME;
	inserttable(table('guanggao_data'), $_array);
	$param->set_cookie('guanggao_'.$_GET['id'], $_GET['id'], SYS_TIME+3600);
}
tiaozhuan($row['setting']['link']);
?>