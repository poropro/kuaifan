<?php
/*
 * 收件箱
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

if ($_GET['del'] == 'all'){
	$whereval = "send_to_id = '{$huiyuan_val['username']}'";
	if ($_GET['dosubmit']){
		//ucenter全部删除信息
		kf_class::ucenter();
		if (UC_USE == '1'){
			if ($huiyuan_val['ucuserid'] > 0){
				$_arr = array();
				$pmarr = $db -> getall("select DISTINCT authorid from ".table('xinxi')." WHERE {$whereval} AND authorid > 0");
				foreach ($pmarr as $_val) {
					$_arr[] = $_val['authorid'];
				}
				if (!empty($_arr)){
					uc_pm_deleteuser($huiyuan_val['ucuserid'], $_arr);
				}
			}
		}
		//
		$db -> query("update ".table('xinxi')." set `folder`='outbox' WHERE {$whereval}");
		$links[0]['title'] = '返回收件列表';
		$links[0]['href'] = get_link("del|dosubmit");
		$links[1]['title'] = '返回会员中心';
		$links[1]['href'] = get_link("vs|sid",'','1').'&amp;m=huiyuan&amp;c=index';
		showmsg("系统提醒", "删除所有收件成功！", $links);
	}
	$links[0]['title'] = '确定删除';
	$links[0]['href'] = get_link()."&amp;dosubmit=1";
	$links[1]['title'] = '返回收件列表';
	$links[1]['href'] = get_link("del");
	showmsg("系统提醒", "确定删除所有收件并且不可恢复吗？", $links);
}elseif ($_GET['del'] == 'reply'){
	$whereval = "send_to_id = '{$huiyuan_val['username']}' AND status=1";
	//ucenter全部标记已读
	kf_class::ucenter();
	if (UC_USE == '1'){
		if ($huiyuan_val['ucuserid'] > 0){
			$_arr = array();
			$pmarr = $db -> getall("select DISTINCT plid from ".table('xinxi')." WHERE {$whereval} AND plid > 0");
			foreach ($pmarr as $_val) {
				$_arr[] = $_val['plid'];
			}
			if (!empty($_arr)){
				uc_pm_readstatus($huiyuan_val['ucuserid'], array(), $_arr, 0);
			}
		}
	}
	//
	$db -> query("update ".table('xinxi')." set `status`='0' WHERE {$whereval}");
	$links[0]['title'] = '返回收件列表';
	$links[0]['href'] = get_link("del|dosubmit");
	$links[1]['title'] = '返回会员中心';
	$links[1]['href'] = get_link("vs|sid",'','1').'&amp;m=huiyuan&amp;c=index';
	showmsg("系统提醒", "全部设为已读成功！", $links);
}elseif (!empty($_GET['del'])){
	$whereval = "messageid in ({$_GET['del']}) AND send_to_id = '{$huiyuan_val['username']}'";
	//ucenter删除短消息
	kf_class::ucenter();
	if (UC_USE == '1'){
		if ($huiyuan_val['ucuserid'] > 0){
			$_arr = array();
			$pmarr = $db -> getall("select pmid from ".table('xinxi')." WHERE {$whereval} AND pmid > 0");
			foreach ($pmarr as $_val) {
				$_arr[] = $_val['pmid'];
			}
			if (!empty($_arr)){
				uc_pm_delete($huiyuan_val['ucuserid'], 'inbox',  $_arr);
			}
		}
	}
	//
	$db -> query("update ".table('xinxi')." set `folder`='outbox' WHERE {$whereval}");
	$links[0]['title'] = '返回收件列表';
	$links[0]['href'] = get_link("del|dosubmit");
	$links[1]['title'] = '返回会员中心';
	$links[1]['href'] = get_link("vs|sid",'','1').'&amp;m=huiyuan&amp;c=index';
	showmsg("系统提醒", "删除收件成功！", $links);
}

$grouplist = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_zu.php');
$group_id = $grouplist[$huiyuan_val['groupid']];
$smarty->assign('username', $huiyuan_val['username']);
$smarty->assign('group_id', $group_id);

?>