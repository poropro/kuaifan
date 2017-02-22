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
require "function.php";
kf_class::run_sys_func('huiyuan');
kf_class::run_sys_func('dingdan');
kf_class::run_sys_func('xinxi');

extract($_GET);  
$row = $db->getone("select * from ".table('dingdan')." WHERE `setting`='".md5($md5)."'");
if (empty($row)){
	echo "N"; //订单不存在
}else{
	if ($row['status']=='10' || $row['status']=='99'){
		echo "Y"; //订单已经处理
	}else{
		$_time = date('Y-m-d H:i:s', $row['inputtime']);
		$_huiy = $db->getone("select * from ".table('huiyuan')." WHERE `userid`='".$row['userid']."'");
		if ($rs_code == "1"){
			//充值成功
			$_dd = dingdan_set($row['id'], 2);
			add_message($_huiy['username'], 0, '充值成功', '您在'.$_time.'使用充值卡充值的订单充值成功！');
		}else{
			//充值失败
			$_dd = dingdan_set($row['id'], 99, cardstr($rs_code,'充值失败:'));
			add_message($_huiy['username'], 0, '充值失败', '您在'.$_time.'使用充值卡充值的订单充值失败(原因:'.cardstr($rs_code).')！');
		}
		if ($_dd > 0){
			echo "Y"; //处理订单成功
		}else{
			echo "N"; //处理订单失败
		}
	}
}
exit();

function cardstr($str, $_a = ''){
	if ($str == '1'){
		return '充值成功';
	}elseif ($str == '2'){
		return $_a.'卡无效';
	}elseif ($str == '5'){
		return $_a.'卡数量过多，目前组多支持10张';
	}elseif ($str == '7'){
		return $_a.'消费者提交的卡中有非法格式的卡';
	}elseif ($str == '11'){
		return $_a.'商户订单号重复';
	}elseif ($str == '61'){
		return $_a.'业务未开通,请联系程序开发商';
	}elseif ($str == '66'){
		return $_a.'金额有误';
	}elseif ($str == '112'){
		return $_a.'商户状态不可用,未开通此项卡支持或FrpId传递有误,请联系程序开发商';
	}elseif ($str == '213'){
		return $_a.'该用户交易超限';
	}elseif ($str == '-1'){
		return $_a.'签名较验失败或未知错误';
	}elseif ($str == '8001'){
		return $_a.'卡面值与卡不符';
	}elseif ($str == '3001'){
		return $_a.'卡不存在';
	}elseif ($str == '3002'){
		return $_a.'卡已使用过';
	}elseif ($str == '3003'){
		return $_a.'卡已作废';
	}elseif ($str == '3004'){
		return $_a.'卡已冻结';
	}elseif (in_array($str, explode(',', '23,32,1003,7046,7049,7060,7063,7065'))){
		return $_a.'不支持的卡类型或金额';
	}elseif (in_array($str, explode(',', '1004,1005,7051,8001'))){
		return $_a.'卡号或密码错误';
	}elseif (in_array($str, explode(',', '25,77,1002,1006,7054,7055,7056,7064,8002'))){
		return $_a.'卡过期或者已销';
	}elseif (in_array($str, explode(',', '12,41,53,63,76,7034'))){
		return $_a.'订单号有误或者重复';
	}elseif (in_array($str, explode(',', '1002,1007,7000'))){
		return $_a.'卡内余额不足';
	}else{
		return $_a.'充值失败';
	}
}
?>