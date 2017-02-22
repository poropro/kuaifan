<?php
/*
 * 短信系统
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */

if(!defined('IN_KUAIFAN')) exit('Access Denied!');

//配置
if ($_GET['a']=='peizhi'){
	$sms['sms_zl'] = $_CFG['sms_zl'];
	$sms['sms_key'] = $_CFG['sms_key'];
	if (!empty($_POST['dosubmit'])){
		//
		$sms['sms_zl'] = $_POST['sms_zl'];
		$sms['sms_key'] = $_POST['sms_key'];
		$db->query("UPDATE ".table('peizhi')." SET value='{$_POST['sms_zl']}' WHERE name='sms_zl'");
		$db->query("UPDATE ".table('peizhi')." SET value='{$_POST['sms_key']}' WHERE name='sms_key'");
		refresh_cache('peizhi');
		//
		$settingarr = array();
		$settingarr['zhuce'] = $_POST['zhuce'];
		$settingarr['zhuce_open'] = intval($_POST['zhuce_open']);
		$settingarr['zhaohui'] = $_POST['zhaohui'];
		$settingarr['zhaohui_open'] = intval($_POST['zhaohui_open']);
		$settingarr['zhuce_sms'] = intval($_POST['zhuce_sms']);
		if (!$db->query("UPDATE ".table('peizhi_mokuai')." SET setting='".array2string($settingarr)."' WHERE module='duanxin'")){
			$smarty->assign('dosubmit', '2');
		}else{
			$smarty->assign('dosubmit', '1');
		}
	}
	$smarty->assign('sms', $sms);
	$row = $db->getone("select * from ".table('peizhi_mokuai')." WHERE module='duanxin' LIMIT 1");
	$smarty->assign('peizhi', string2array($row['setting']));
}
//预览
if ($_GET['a']=='yulan'){
	kf_class::run_sys_func('ubb');
	$row = $db->getone("select * from ".table('peizhi_mokuai')." WHERE module='duanxin' LIMIT 1");
	$smarty->assign('peizhi', string2array($row['setting']));
}
?>