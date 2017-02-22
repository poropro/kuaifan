<?php
/*
 * 会员中心
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

require(KF_INC_PATH.'denglu.php');
$huiyuan_val['nickname'] = $huiyuan_val['nickname']?$huiyuan_val['nickname']:$huiyuan_val['username'];
if (SYS_TIME - $huiyuan_val['indate'] < $_CFG['lonline']*60){
	$huiyuan_val['indate_cn'] = "在线";
}else{
	$huiyuan_val['indate_cn'] = "离线";
}
$smarty->assign('huiyuan', $huiyuan_val);

//会员组
$grouplist = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_zu.php');
//收信箱
$new_arr = array();
$new_arr['shouxin'] = $db->get_total("SELECT COUNT(*) AS num FROM ".table('xinxi')." WHERE send_to_id='{$huiyuan_val['username']}' AND folder='inbox' AND status='1'");
$new_arr['shouxin'] = empty($new_arr['shouxin'])?"":"({$new_arr['shouxin']})";
//系统信息
$new_arr['xitong'] = $db->get_total("SELECT COUNT(*) AS num FROM ".table('xinxi_xitong')." WHERE typeid='1' AND groupid='{$huiyuan_val['groupid']}' AND status='1' AND id not in (SELECT group_message_id FROM ".table('xinxi_data')." WHERE userid='{$huiyuan_val['userid']}')");
$new_arr['xitong'] = empty($new_arr['xitong'])?"":"({$new_arr['xitong']})";

$smarty->assign('new_arr', $new_arr);
$smarty->assign('grouplist', $grouplist[$huiyuan_val['groupid']]);

?>