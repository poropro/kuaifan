<?php
/*
 * 友链首页
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');
$peizhi = getcache(KF_ROOT_PATH. "caches/caches_peizhi_mokuai/cache.lianjie.php");

//链入
if ($_GET['amp;id'] > 0) $_GET['id'] = $_GET['amp;id'];
if ($_GET['id']){
	$row = $db->getone("select * from ".table('lianjie')." WHERE id='{$_GET['id']}' LIMIT 1");
	if (!empty($row)){
		$param = kf_class::run_sys_class('param');
		//
		if ($peizhi['shuajin']=='ip'){
			$ljshua = $db->getone("select * from ".table('lianjie_data')." WHERE `type`=1 AND `id`='{$row['id']}' AND `ip`='".$online_ip."' AND `inputtime`>".strtotime(date("Y-m-d"),SYS_TIME));
		}elseif ($peizhi['shuajin']=='session'){
			$ljshua = $_SESSION['lianjier_'.$_GET['id']];
		}else{
			$ljshua = $param->get_cookie('lianjier_'.$_GET['id']);
		}
		if (!$ljshua) {
			//
			if (empty($row['type']) && intval($row['from'] + 1) >= $row['fromnum']){
				$db -> query("update ".table('lianjie')." set `from`=`from`+1,fromip='".$online_ip."',fromtime='".SYS_TIME."',type=1 WHERE id='{$_GET['id']}'");
			}else{
				$db -> query("update ".table('lianjie')." set `from`=`from`+1,fromip='".$online_ip."',fromtime='".SYS_TIME."' WHERE id='{$_GET['id']}'");
			}
			//
			$_array = array();
			$_array['id'] = $row['id'];
			$_array['catid'] = $row['catid'];
			$_array['type'] = 1;
			$_array['ip'] = $online_ip;
			$_array['userid'] = US_USERID;
			$_array['username'] = US_USERNAME;
			$_array['inputtime'] = SYS_TIME;
			inserttable(table('lianjie_data'), $_array);
			if ($peizhi['shuajin']=='ip'){
				//
			}elseif ($peizhi['shuajin']=='session'){
				$_SESSION['lianjier_'.$_GET['id']] = $_GET['id'];
			}else{
				$param->set_cookie('lianjier_'.$_GET['id'], $_GET['id'], SYS_TIME+3600);
			}
		}
		tiaozhuan(get_link('vs|sid', '', 1));
	}else{
		unset($_GET['id']);
	}
}

$wheresql= "`type`=1";
$wheresql.= isset($_GET['catid'])?" AND `catid`='{$_GET['catid']}'":"";
$wheresql.= isset($_GET['key'])?" AND (`title` like '%{$_GET['key']}%' OR `catid_cn` = '{$_GET['key']}')":"";
$wheresql = ltrim(ltrim($wheresql),'AND');
$ordersql = (isset($_GET['order']))?"`{$_GET['order']}`":"`listorder`";
$ordersql.= " DESC";
$smarty->assign('wheresql', $wheresql);
$smarty->assign('ordersql', $ordersql);
//
$url = get_link('order');
if ($_GET['order']=='listorder'){
	$orderlink = '排序:默认|<a href="'.$url.'&amp;order=inputtime">最新</a>|<a href="'.$url.'&amp;order=read">最热</a>|<a href="'.$url.'&amp;order=from">来访</a>|<a href="'.$url.'&amp;order=fromtime">最后来访</a>';
}elseif ($_GET['order']=='inputtime'){
	$orderlink = '排序:<a href="'.$url.'&amp;order=listorder">默认</a>|最新|<a href="'.$url.'&amp;order=read">最热</a>|<a href="'.$url.'&amp;order=from">来访</a>|<a href="'.$url.'&amp;order=fromtime">最后来访</a>';
}elseif ($_GET['order']=='fromtime'){
	$orderlink = '排序:<a href="'.$url.'&amp;order=listorder">默认</a>|<a href="'.$url.'&amp;order=inputtime">最新</a>|<a href="'.$url.'&amp;order=read">最热</a>|<a href="'.$url.'&amp;order=from">来访</a>|最后来访';
}elseif ($_GET['order']=='read'){
	$orderlink = '排序:<a href="'.$url.'&amp;order=listorder">默认</a>|<a href="'.$url.'&amp;order=inputtime">最新</a>|最热|<a href="'.$url.'&amp;order=from">来访</a>|<a href="'.$url.'&amp;order=fromtime">最后来访</a>';
}elseif ($_GET['order']=='from'){
	$orderlink = '排序:<a href="'.$url.'&amp;order=listorder">默认</a>|<a href="'.$url.'&amp;order=inputtime">最新</a>|<a href="'.$url.'&amp;order=read">最热</a>|来访|<a href="'.$url.'&amp;order=fromtime">最后来访</a>';
}else{
	$orderlink = '排序:<a href="'.$url.'&amp;order=listorder">默认</a>|<a href="'.$url.'&amp;order=inputtime">最新</a>|<a href="'.$url.'&amp;order=read">最热</a>|<a href="'.$url.'&amp;order=from">来访</a>|<a href="'.$url.'&amp;order=fromtime">最后来访</a>';
}
$smarty->assign('orderlink', $orderlink);
?>