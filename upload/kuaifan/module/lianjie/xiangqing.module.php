<?php
/*
 * 友链详情
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

$row = $db->getone("select * from ".table('lianjie')." WHERE id='{$_GET['id']}' LIMIT 1");
if (empty($row)) showmsg("系统提醒", "友情链接不存在！");
$rowf = $db->getone("select * from ".table('lianjie_fenlei')." WHERE catid='{$row['catid']}'");
$param = kf_class::run_sys_class('param');
if ($_GET['z']){
	if (!$param->get_cookie('lianjiez_'.$_GET['id'])) {
		if ($_GET['z'] == '1'){
			$db -> query("update ".table('lianjie')." set zhichi=zhichi+1 WHERE id='{$_GET['id']}'");
		}else{
			$db -> query("update ".table('lianjie')." set buzhichi=buzhichi+1 WHERE id='{$_GET['id']}'");
		}
		$param->set_cookie('lianjiez_'.$_GET['id'], $_GET['id'], SYS_TIME+3600);
		tiaozhuan(get_link('z'));
	}
}
if ($_GET['dosubmit'] || $rowf['islink']){
	if ($peizhi['shuachu']=='ip'){
		$ljshua = $db->getone("select * from ".table('lianjie_data')." WHERE `type`=0 AND `id`='{$row['id']}' AND `ip`='".$online_ip."' AND `inputtime`>".strtotime(date("Y-m-d"),SYS_TIME));
	}elseif ($peizhi['shuachu']=='session'){
		$ljshua = $_SESSION['lianjie_'.$_GET['id']];
	}else{
		$ljshua = $param->get_cookie('lianjie_'.$_GET['id']);
	}
	if (!$ljshua) {
		//
		$db -> query("update ".table('lianjie')." set `read`=`read`+1,readip='".$online_ip."',readtime='".SYS_TIME."' WHERE id='{$_GET['id']}'");
		//
		$_array = array();
		$_array['id'] = $row['id'];
		$_array['catid'] = $row['catid'];
		$_array['type'] = 0;
		$_array['ip'] = $online_ip;
		$_array['userid'] = US_USERID;
		$_array['username'] = US_USERNAME;
		$_array['inputtime'] = SYS_TIME;
		inserttable(table('lianjie_data'), $_array);
		if ($peizhi['shuachu']=='ip'){
			//
		}elseif ($peizhi['shuachu']=='session'){
			$_SESSION['lianjie_'.$_GET['id']] = $_GET['id'];
		}else{
			$param->set_cookie('lianjie_'.$_GET['id'], $_GET['id'], SYS_TIME+3600);
		}
	}
	//
	tiaozhuan($row['url']);
}
$row['readtime'] = $row['readtime']?date('Y-m-d H:i:s',$row['readtime']):'无记录';
$row['fromtime'] = $row['fromtime']?date('Y-m-d H:i:s',$row['fromtime']):'无记录';
$smarty->assign('YL', $row);
?>