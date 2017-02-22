<?php
 /*
 * 转账
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

$row = $db->getone("select * from ".table('zhifu')." WHERE path='{$_GET['c']}' AND open=1 LIMIT 1");
if (empty($row)) showmsg("系统提醒", "没有这个付款方式！");
//手续费费率
if ($row['rate'] > 100) $row['rate']=100;
if ($row['rate'] < 0) $row['rate']=0;
//提交订单
if ($_POST['dosubmit']){
	if (!is_num($_POST['amount'])) showmsg("系统提醒", "请输入有效的充值金额！");
	$_POST['amount'] = intval($_POST['amount']);
	//
	$shouxu = 0;
	$ruzhang = $_POST['amount'];
	$content = "充值{$_POST['amount']}{$_CFG['amountname']}<br/>";
	if ($row['rate']>0){
		$shouxu = sprintf("%.2f", $_POST['amount']*$row['rate']/100);
		$ruzhang = $_POST['amount']-$shouxu;
		$content.= "手续费: {$shouxu}{$_CFG['amountname']}({$row['rate']}%); 到账: {$ruzhang}{$_CFG['amountname']}<br/>";
	}
	$content.= $row['content']?"充值描述: ".$row['content']:"";
	$paysql = array();
	$paysql['payfun'] = 'set_jiangfa("'.$huiyuan_val['userid'].'", "'.$ruzhang.'", "1", "'.$row['title'].'")';
	$dingdanid = tianjia_dingdan("账户充值({$row['title']})", 0, 1, $huiyuan_val['userid'], 0, 2, $paysql, $content, "账户充值", 0, "amount", 1);
	if (!$dingdanid){
		showmsg('系统提醒','参数错误，请重新操作！');
	}else{
		$links[0]['title'] = '现在去付款';
		$links[0]['href'] = get_link("vs|sid",'','1').'&amp;m=dingdan&amp;c=xiangqing&amp;id='.$dingdanid;
		$links[1]['title'] = '返回继续充值';
		$links[1]['href'] = get_link();
		$links[2]['title'] = '返回会员中心';
		$links[2]['href'] = get_link("vs|sid",'','1').'&amp;m=huiyuan&amp;c=index';
		showmsg('系统提醒','已经成功下好订单！', $links);
	}
}
$smarty->assign('huiyuan', $huiyuan_val);
$smarty->assign('zhifu', $row);
?>