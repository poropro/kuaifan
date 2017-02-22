<?php
 /*
 * 神州行充值系统
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
if (empty($row['key'])) showmsg('系统提醒','系统正在安装中！');
//手续费费率
if ($row['rate'] > 100) $row['rate']=100;
if ($row['rate'] < 0) $row['rate']=0;
//提交订单
if ($_POST['dosubmit']){
	if (!is_num($_POST['amount'])) showmsg("系统提醒", "请选择有效的充值金额！");
	if (!($_POST['cardNo'])) showmsg("系统提醒", "请输入神州行充值卡序列号！");
	if (!($_POST['cardPwd'])) showmsg("系统提醒", "请输入神州行充值卡密码！");
	$_POST['amount'] = intval($_POST['amount']);
	//
	$shouxu = 0;
	$ruzhang = $_POST['amount'];
	$content = "充值{$_POST['amount']}{$_CFG['amountname']}";
	$content.= "<br/>充值卡序列号: ".$_POST['cardNo'];
	$content.= "<br/>充值卡密码: ".$_POST['cardPwd'];
	if ($row['rate']>0){
		$shouxu = sprintf("%.2f", $_POST['amount']*$row['rate']/100);
		$ruzhang = $_POST['amount']-$shouxu;
		$content.= "<br/>手续费: {$shouxu}{$_CFG['amountname']}({$row['rate']}%); 到账: {$ruzhang}{$_CFG['amountname']}";
	}
	if ($row['content']) $content.= "<br/>充值描述: ".$row['content'];
	//发送充值命令
	require(dirname(dirname(__FILE__)).'/shenzhouxing/function.php');
	if (substr(SYS_URL, 0, 4) != 'http') showmsg('系统提醒','系统正在维护中...请稍后再试！');
	$tempmd5 = generate_password(8);
	$setting = md5($tempmd5);
	if ($db->getone("select * from ".table('dingdan')." WHERE `setting`='{$setting}'")) showmsg('系统提醒','系统繁忙:0，请稍后再试！');
	$tempurl = SYS_URL."?m=zhifu";
	$tempurl.= "&userid={$huiyuan_val['userid']}";
	$tempurl.= "&keyval={$row['key']}";
	$tempurl.= "&Amt={$_POST['amount']}";
	$tempurl.= "&Amts={$ruzhang}";
	$tempurl.= "&rate={$row['rate']}";
	$tempurl.= "&cardNo={$_POST['cardNo']}";
	$tempurl.= "&cardPwd={$_POST['cardPwd']}";
	$tempurl.= "&md5={$tempmd5}";
	$tempurl.= "&url=".urlencode(get_link('m|c')."&m=shenzhouxing&c=fanhui");
	$result = chongzhi_open($tempurl,'keyidstart','keyidend');
	if($result == "11"){
		showmsg('系统提醒','系统繁忙:11，请稍后再试！'); //定单号重复
	}elseif($result == "7"){
		showmsg('系统提醒','卡密无效！'); 
	}elseif($result == "61"){
		showmsg('系统提醒','账户未开通，请联系管理员！'); 
	}elseif($result == "112"){
		showmsg('系统提醒','商户状态不可用,未开通此项卡支持或FrpId传递有误,请联系管理员！');
	}elseif($result == "-1"){
		showmsg('系统提醒','系统繁忙:-1，请稍后再试！'); //交易信息被篡改
	}elseif($result != "1"){
		showmsg('系统提醒','系统繁忙:'.$result.'，请稍后再试！');
	}
	$paysql = array();
	$paysql['payfun'] = 'set_jiangfa("'.$huiyuan_val['userid'].'", "'.$ruzhang.'", "1", "'.$row['title'].'")';
	$dingdanid = tianjia_dingdan("账户充值({$row['title']})", 0, 1, $huiyuan_val['userid'], 0, 2, $paysql, $content, "账户充值", 0, "amount", 0, $setting);
	if (!$dingdanid){
		showmsg('系统提醒','参数错误，请重新操作！');
	}else{
		dingdan_set($dingdanid, 1);
		$links[0]['title'] = '查充值状态';
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