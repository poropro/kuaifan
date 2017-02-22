<?php
/*
 * 金币兑换积分
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

$duihuan = $db -> getone("select * from ".table('peizhi_mokuai')." WHERE module='huiyuan'");
$duihuan = string2array($duihuan['setting']);
if (empty($duihuan['showapppoint'])){
	showmsg("系统提醒", "系统已经关闭积分购买功能！");
}
$smarty->assign('rmb_point_rate', $duihuan['rmb_point_rate']);
if ($_POST['dosubmit']){
	$money = intval($_POST['money']);
	if ($money < 1) showmsg("系统提醒", "支出{$_CFG['amountname']}只能为正整数！");
	if ($huiyuan_val['amount'] < $money) showmsg("系统提醒", "您的余额不足！");
	$paysql = array();
	$paysql['payfun'] = 'set_jiangfa("'.$huiyuan_val['userid'].'","'.$money*$duihuan['rmb_point_rate'].'", "0", "购买积分");';
	$paycon = "以{$money}{$_CFG['amountname']}购买".$money*$duihuan['rmb_point_rate']."积分";
	$dingdanid = tianjia_dingdan("购买积分", $money, 1, $huiyuan_val['userid'], 0, 1, $paysql, $paycon, '购买积分', 0, 'amount');
	if (!$dingdanid){
		showmsg('系统提醒','参数错误，请重新操作！');
	}else{
		$links[0]['title'] = '现在去付款';
		$links[0]['href'] = get_link("vs|sid",'','1').'&amp;m=dingdan&amp;c=xiangqing&amp;id='.$dingdanid;
		$links[1]['title'] = '返回继续购买';
		$links[1]['href'] = get_link();
		$links[2]['title'] = '返回会员中心';
		$links[2]['href'] = get_link("vs|sid",'','1').'&amp;m=huiyuan&amp;c=index';
		showmsg('系统提醒','已经成功下好订单！', $links);
	}
}

?>