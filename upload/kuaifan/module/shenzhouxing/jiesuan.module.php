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
require(KF_INC_PATH.'denglu_admin.php');
require "function.php";

if (!empty($_POST['dosubmit'])){
	$url = SYS_URL."?m=jiesuantijiao&keyval={$row['key']}";
	$url.= "&jiesuan=".urlencode($_POST['jiesuan']);
	$html = chongzhi_open($url, 'keyidstart', 'keyidend');
	if ($html == '0'){
		showmsg("系统提示", "你已经提交过一笔结算，在未结算完成之前不允许再提交！");
	}elseif ($html == '-1'){
		showmsg("系统提示", "参数错误！");
	}elseif ($html == '-2'){
		showmsg("系统提示", "请输入正确的提现金额！");
	}elseif ($html == '-3'){
		showmsg("系统提示", "提现金额不能超过可用余额！");
	}elseif ($html == '-4'){
		showmsg("系统提示", "提现金额每笔不能小于100{$_CFG['amountname']}！");
	}elseif ($html == '-5'){
		showmsg("系统提示", "网络繁忙，请稍后再试！");
	}elseif ($html > 0){
		showmsg("系统提示", "恭喜您,提交结算成功!<br/>7个工作日内管理员会汇款到您在<a href='".get_link('c')."&amp;c=yinhang'>站长银行</a>里填写的银行信息里<br/>*汇款产生的手续费由收款方支付,客服QQ：342210020");
	}
}
$html = chongzhi_open(SYS_URL."?m=jiesuan&keyval={$row['key']}", 'keyidstart', 'keyidend');
$smarty->assign('htmlarr', string2array($html));
?>