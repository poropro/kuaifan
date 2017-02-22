<?php
/*
 * 付款订单
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
	showmsg("系统提醒", "此订单不在可付款状态！");
}
$smarty->assign('dingdan', $row);

if ($_POST['dosubmit']){
	//验证支付密码
	if (empty($_POST['zhifumima'])){
		showmsg("系统提醒", "请输入支付密码！");
	}
	if (!run_zhifumima($huiyuan_val, $_POST['zhifumima'])){
		$payerr = $huiyuan_val['payerr']<3?$huiyuan_val['payerr']:0;
		$payerr = 2 - $payerr;
		if ($payerr == 0){
			showmsg("系统提醒", "支付密码输入错误3次，支付功能封锁24小时！");
		}else{
			showmsg("系统提醒", "支付密码输入错误，您还可以输入{$payerr}次错误后支付功能封锁24小时！");
		}
	}
	//验证余额
	if ($row['price_type']=='point'){
		//积分
		if ($huiyuan_val['point'] < $row['price']*$row['num']){
			showmsg("系统提醒", "付款失败，余额不足！");
		}
	}else{
		//金币
		if ($huiyuan_val['amount'] < $row['price']*$row['num']){
			showmsg("系统提醒", "付款失败，余额不足！");
		}
	}
	$price = $row['price']*$row['num'];
	$price_type = ($row['price_type']=='point')?'0':'1';
	//买家扣除余额
	set_jiangfa($row['userid'], $price*-1, $price_type, '订单付款-'.$row['title']);
	//处理订单
	$restatus = dingdan_set($row['id'], 1, '' ,$_POST['tocontent']);
	if ($restatus == 10){
		$msg = "付款成功，交易完成！";
	}else{
		$msg = "付款成功，等待发货！";
	}

	$links[0]['title'] = '订单详情';
	$links[0]['href'] = get_link('c').'&amp;c=xiangqing';
	$links[1]['title'] = '回订单列表';
	$links[1]['href'] = get_link('c|id');
	$links[2]['title'] = '返回会员中心';
	$links[2]['href'] = get_link("vs|sid",'','1').'&amp;m=huiyuan&amp;c=index';
	showmsg("系统提醒", $msg, $links);
}

?>