<?php
/*
 * 网站配置
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

if ($_GET['a'] == "banben"){
	if (!empty($_POST['dosubmit'])){
		foreach($_POST as $k => $v){
			if (substr($k,0,2)!='is') unset($_POST[$k]);
		}
		$links[0]['title'] = '返回后台首页';
		$links[0]['href'] = $_admin_indexurl;
		!$db->query("UPDATE ".table('peizhi_mokuai')." SET setting='".array2string($_POST)."' WHERE module='banben'")?showmsg("系统提醒", "更新设置失败！", $links):"";
		refresh_peizhi_mokuai();
		if ($_POST['isvs1']>0 || $_POST['isvs2']>0 || $_POST['isvs3']>0 || $_POST['isvs4']>0 || $_POST['isvs5']>0){
			!$db->query("UPDATE ".table('peizhi')." SET value='99' WHERE name='vs'")?showmsg("系统提醒", "更新设置失败！", $links):"";
			refresh_cache('peizhi');
		}
		admin_log("修改了版本高级配置。", $admin_val['name']);
		$links[1]['title'] = '继续配置';
		$links[1]['href'] = get_link();
		$links = array_reverse($links);
		showmsg("系统提醒", "保存成功！", $links);
	}
	
	$row = $db -> getone("select * from ".table('peizhi_mokuai')." WHERE `module`='banben'");
	$smarty->assign('banben',string2array($row['setting']));
}else{

	if (!empty($_POST['dosubmit'])){
		$links[0]['title'] = '返回后台首页';
		$links[0]['href'] = $_admin_indexurl;
		foreach($_POST as $k => $v){
			!$db->query("UPDATE ".table('peizhi')." SET value='{$v}' WHERE name='{$k}'")?showmsg("系统提醒", "更新设置失败！", $links):"";
		}
		refresh_cache('peizhi');
		if ($_GET['c'] == 'anquan'){
			admin_log("修改了安全配置。", $admin_val['name']);
		}else{
			admin_log("修改了网站配置。", $admin_val['name']);
		}
		$links[1]['title'] = '继续配置';
		$links[1]['href'] = get_link();
		$links = array_reverse($links);
		showmsg("系统提醒", "保存成功！", $links);
	}
	$smarty->assign('peizhi',get_cache('peizhi'));

	$_path = 'templates/';
	$_path = KF_ROOT_PATH.str_replace('/*', DIRECTORY_SEPARATOR, $_path);
	$templetarr = array();
	$_patharr = glob($_path . '*', GLOB_BRACE);
	foreach ($_patharr as $_val) {
		$_name = basename($_val);
		if (substr($_name, 0 , 8) != 'templet_' && is_dir($_val)){
			$_val = str_replace('.tpl', '', $_val);
			$templetarr[$_name] = $_name . '/';
		}
	}
	$smarty->assign('templetarr',$templetarr);

}
?>