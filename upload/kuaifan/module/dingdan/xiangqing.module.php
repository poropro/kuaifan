<?php
/*
 * 订单详情
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
$row['maijia'] = get_user($row['touserid']);
$row['price_type_cn']=($row['price_type']=='point')?'积分':$_CFG['amountname'];
if ($row['titleurl']) $row['titleurl'] = $row['titleurl']."&amp;sid={$_GET['sid']}&amp;vs={$_GET['vs']}";
if ($row['status']=='0'){ //0正常(下单未付款)，1已付款，2已发货，10已收货(交易成功)，99关闭交易
	$row['status_cn'] = '未付款,等待付款';
}elseif ($row['status']=='1'){
	$row['status_cn'] = '已付款,等待发货';
	if ($_POST['dosubmit']){
		$row['tocontent'] = $_POST['tocontent'];
		$db -> query("update ".table('dingdan')." set `tocontent`='{$_POST['tocontent']}' WHERE id={$row['id']}");
	}
}elseif ($row['status']=='2'){
	$row['status_cn'] = '已发货,等待收货';
}elseif ($row['status']=='10'){
	$row['status_cn'] = '交易成功';
}elseif ($row['status']=='99'){
	$row['status_cn'] = '交易关闭('.$row['status_close'].')';
}else{
	$row['status_cn'] = '交易失败';
}
$smarty->assign('dingdan', $row);

?>