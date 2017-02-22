<?php
/*
 * 账户记录
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

if ($_GET['id']){
	$templatefile.= '/xiangqing';
	$wheresql = "del=0 AND userid='{$huiyuan_val['userid']}' AND id='{$_GET['id']}'";
	$row = $db->getone("select * from ".table('jiangfa')." WHERE {$wheresql} LIMIT 1");
	if (empty($row)) showmsg("系统提醒", "此记录已经不存在！");
	$row['type_cn']=$row['type']?'元':'分';
	$row['type_cn2']=$row['type']?$_CFG['amountname']:'积分';
	$row['add_cn']=($row['add']=='cut')?'支出':'收入';
	$ip_area = kf_class::run_sys_class('ip_area');
	$ip_city = $ip_area->get($row['ip']);
	if ($ip_city == 'Unknown' || $ip_city == 'IANA' || $ip_city == 'RIPE'){
		$ip_city_arr = $ip_area->getcitybyapi($row['ip']);
		$ip_city = $ip_city_arr['city'];
	}
	$row['ip_cn'] = $ip_city?"({$ip_city})":"";
	$smarty->assign('jilu', $row);
}else{
	$templatefile.= '/index';
}

?>