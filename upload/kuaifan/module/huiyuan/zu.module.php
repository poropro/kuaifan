<?php
/*
 * 查看用户组
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

	
$group_list = array();
$_n = 1;
foreach ($grouplist as $_k => $_v){
	$_v['_n'] = $_n++;
	$_v['starimg'] = IMG_PATH.'icon/xing-'.$_v['starnum'].'.gif';
	$group_list[$_k] = $_v;
}

$smarty->assign('group_list', $group_list);
?>