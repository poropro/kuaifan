<?php
/*
 * 在线充值
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */

if(!defined('IN_KUAIFAN')) exit('Access Denied!');

switch ($_GET['a']) {
	case 'ruzhang': //入账
		if ($_POST['dosubmit']){
			$_POST['num'] = intval($_POST['num']);
			if (!$_POST['username']) showmsg("系统提示", "请输入“用户名”！");
			if (!$_POST['num']) showmsg("系统提示", "请输入有效的“充值额度”！");
			if (!isset($_POST['type'])) showmsg("系统提示", "请选择“充值类型”！");
			if (!$_POST['add']) showmsg("系统提示", "请选择“增加减少”！");
			if (!$_POST['title']) showmsg("系统提示", "请输入“交易备注”！");
			if ($_POST['type']!='0' && $_POST['type']!='1') showmsg("系统提示", "请选择有效的“充值类型”！");
			if ($_POST['usertype']!='username' && $_POST['usertype']!='userid') showmsg("系统提示", "请选择有效的“用户类型”！");
			
			$row = $db->getone("select * from ".table('huiyuan')." WHERE `{$_POST['usertype']}`='".$_POST['username']."' LIMIT 1");
			if (empty($row)) showmsg("系统提示", "会员不存在！");
			
			if ($_POST['add']=='add'){
				$_POST['num'] = abs($_POST['num']);
			}elseif ($_POST['add']=='cut'){
				$_POST['num'] = abs($_POST['num'])*-1;
			}else{
				showmsg("系统提示", "请选择有效的“增加减少”！");
			}
			kf_class::run_sys_func('huiyuan');
			set_jiangfa($row['userid'], $_POST['num'], $_POST['type'], $_POST['title']);
			kf_class::run_sys_func('xinxi');
			$msg = '系统给您的帐号';
			$msg.= $_POST['add']=='add'?"充值":"扣除";
			$msg.= "了".abs($_POST['num']);
			$msg.= $_POST['type']=='0'?"积分":$_CFG['amountname'];
			$msg.= "。留言: ".$_POST['title'];
			add_message($row['username'], 0, '系统充值信息', $msg);
			$links[0]['title'] = '继续充值';
			$links[0]['href'] = get_link();
			$links[1]['title'] = '返回在线充值';
			$links[1]['href'] = get_link("a");
			showmsg("系统提醒", "充值入账成功！", $links);
		}
		break;
	case 'jilu': //账户记录
		$_GET['userid'] = $_POST['userid']?$_POST['userid']:$_GET['userid'];
		if (empty($_GET['userid'])) unset($_GET['userid']);
		break;
	case 'jiluinfo': //记录详情
    $wheresql = "del=0 AND id='{$_GET['id']}'";
    $row = $db->getone("select * from ".table('jiangfa')." WHERE {$wheresql} LIMIT 1");
    if (empty($row)) showmsg("系统提醒", "此记录已经不存在！");
    $row['type_cn']=$row['type']?'元':'分';
    $row['type_cn2']=$row['type']?$_CFG['amountname']:'积分';
    $row['add_cn']=($row['add']=='cut')?'支出':'收入';
    $row['huiyuan'] = $db->getone("select * from ".table('huiyuan')." WHERE userid={$row['userid']} LIMIT 1");
    $ip_area = kf_class::run_sys_class('ip_area');
    $ip_city = $ip_area->get($row['ip']);
    if ($ip_city == 'Unknown' || $ip_city == 'IANA' || $ip_city == 'RIPE'){
      $ip_city_arr = $ip_area->getcitybyapi($row['ip']);
      $ip_city = $ip_city_arr['city'];
    }
    $row['ip_cn'] = $ip_city?"({$ip_city})":"";
    $smarty->assign('jilu', $row);
		break;
	case 'shanchu': //删除记录
		$wheresql = "del=0 AND id='{$_GET['id']}'";
		$row = $db->getone("select * from ".table('jiangfa')." WHERE {$wheresql} LIMIT 1");
		if (empty($row)) showmsg("系统提醒", "此记录已经不存在！");
		if ($_GET['dosubmit']){
			$db -> query("update ".table('jiangfa')." set `del` = '1' WHERE {$wheresql}");
			$links[0]['title'] = '返回记录列表';
			$links[0]['href'] = get_link("a|id|dosubmit").'&amp;a=jilu';
			$links[1]['title'] = '返回在线充值';
			$links[1]['href'] = $_admin_indexurl."&amp;c=chongzhi";
			showmsg("系统提醒", "删除成功！", $links);
		}
		$links[0]['title'] = '返回详情';
		$links[0]['href'] = get_link("a").'&amp;a=jilu';
		$links[1]['title'] = '返回记录列表';
		$links[1]['href'] = get_link("a|id").'&amp;a=jilu';
		$_time = '<br/> <a href="'.get_link().'&amp;dosubmit=1">确定删除</a>';
		showmsg("系统提醒", "你确定删除此记录吗？".$_time, $links);
		break;
	case 'dingdan': //订单列表
		$_GET['userid'] = $_POST['userid']?$_POST['userid']:$_GET['userid'];
		if (empty($_GET['userid'])) unset($_GET['userid']);
		break;
	case 'xiangqing': //订单详情
		kf_class::run_sys_func('huiyuan');
		$row = $db->getone("select * from ".table('dingdan')." WHERE id='{$_GET['id']}' LIMIT 1");
		if (empty($row)) showmsg("系统提醒", "此订单已经不存在！");
		$row['huiyuan'] = $db->getone("select * from ".table('huiyuan')." WHERE userid={$row['userid']} LIMIT 1");
		$row['maijia'] = get_user($row['touserid']);
		$row['price_type_cn']=($row['price_type']=='point')?'积分':$_CFG['amountname'];
		if ($row['status']=='0'){ //0正常(下单未付款)，1已付款，2已发货，10已收货(交易成功)，99关闭交易
			$row['status_cn'] = '未付款,等待付款';
		}elseif ($row['status']=='1'){
			$row['status_cn'] = '已付款,等待发货';
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
		break;
	case 'fahuo': //发货
		$wheresql = "id='{$_GET['id']}'";
		$row = $db->getone("select * from ".table('dingdan')." WHERE {$wheresql} LIMIT 1");
		if (empty($row)) showmsg("系统提醒", "此订单已经不存在！");
		if ($row['status']!='1' || $row['touserid']!='0' || $row['toadmin']!='1'){
			showmsg("系统提醒", "此订单不在可发货状态！");
		}
		$links[0]['title'] = '回订单详情';
		$links[0]['href'] = get_link("dosubmit|a").'&amp;a=xiangqing';
		$links[1]['title'] = '返回订单列表';
		$links[1]['href'] = get_link("dosubmit|a|id").'&amp;a=dingdan';
		if ($_GET['dosubmit']){
			kf_class::run_sys_func('dingdan');
			kf_class::run_sys_func('huiyuan');
			dingdan_set($row['id'], 2);
			admin_log("发货订单ID:{$row['id']},标题:{$row['title']}！", $admin_val['name']);
			showmsg("系统提醒", "操作成功！", $links);
		}
		showmsg("系统提醒", "确定“已收到款/发货”操作？<br/><a href='".get_link("dosubmit")."&amp;dosubmit=1'>点此确定</a>", $links);
		break;
	case 'zhifu': //支付

		break;
	case 'peizhi': //支付配置
    if (is_num($_GET['id'])){
      $wheresql = "id='{$_GET['id']}'";
    }else{
      $wheresql = "path='{$_GET['id']}'";
    }
    $row = $db->getone("select * from ".table('zhifu')." WHERE {$wheresql} LIMIT 1");
    if (empty($row)) showmsg("系统提醒", "此功能不存在！");
    $row['syscontent'] = chongzhi_ubbsys($row['syscontent']);
    $row['setting'] = string2array($row['setting']);
    $row['setting_field'] = string2array($row['setting_field']);
    $smarty->assign('zhifu', $row);
    if ($_POST['dosubmit']){
      $payarr = $setarr = array();
      foreach ($_POST as $_k=>$_v) {
        if (substr($_k, 0, 4) == 'pay_'){
          $payarr[substr($_k, 4)] = $_v;
        }
        if (substr($_k, 0, 8) == 'setting_'){
          $setarr[substr($_k, 8)] = $_v;
        }
      }
      if (!empty($setarr)) $payarr['setting'] = array2string($setarr);
      if (!empty($payarr)) updatetable(table('zhifu'), $payarr, $wheresql);
      showmsg("系统提醒", "修改成功！");
    }
		break;
}


//
function chongzhi_ubbsys($str){
	return $str?preg_replace("/GET\[(.+?)\]/e","chongzhi_ubbget('\\1')",$str):'';
}
function chongzhi_ubbget($str){
	return $str?$_GET[$str]:'';
}
?>