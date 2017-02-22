<?php
/*
 * 查看用户组详情
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

$grouplist = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_zu.php');
$group_id = $grouplist[$_GET['groupid']];
if (empty($group_id)) showmsg("系统提醒", "此分组不存在！");
$group_id['starimg'] = IMG_PATH.'icon/xing-'.$group_id['starnum'].'.gif';
//获取上一级积分
$group_list2 = $db -> getone("select * from ".table('huiyuan_zu')." WHERE point<{$group_id['point']} ORDER BY `point` DESC  LIMIT 1");
$group_id['point_back'] = $group_list2['point'];
	
$smarty->assign('group_id', $group_id);
?>