<?php
/*
 * 安全配置
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');


if ($_GET['a'] == "white") {
	$cache_file_path =KF_ROOT_PATH. "caches/cache_minrefreshwhitelist.php";
	$white = get_cache('minrefreshwhitelist');
	if (!empty($_GET['delkey'])){
		if (!isset($white[$_GET['delkey']])) {
			showmsg("系统提醒", "地址不存在，可能已经被删除！");
		}
		unset($white[$_GET['delkey']]);
		write_static_cache($cache_file_path, $white);
		admin_log("删除防两次刷新间隔白名单。", $admin_val['name']);
		//
		$links[0]['title'] = '返回继续';
		$links[0]['href'] = get_link();
		$links[1]['title'] = '返回后台首页';
		$links[1]['href'] = $_admin_indexurl;
		showmsg("系统提醒", "删除成功！", $links);
	}
	if (!empty($_POST['dosubmit'])){
		$addwhite = strtolower(trim($_POST['addwhite']));
		if (!$addwhite) {
			showmsg("系统提醒", "地址不能为空！");
		}
		if (substr($addwhite,0,7) != "http://" && substr($addwhite,0,8) != "https://") {
			showmsg("系统提醒", "地址格式不正确！");
		}
		$addwhite = str_replace("&amp;","&",$addwhite);
		$white[md5($addwhite)] = $addwhite;

		write_static_cache($cache_file_path, $white);
		admin_log("添加防两次刷新间隔白名单。", $admin_val['name']);
		//
		$links[0]['title'] = '返回继续';
		$links[0]['href'] = get_link();
		$links[1]['title'] = '返回后台首页';
		$links[1]['href'] = $_admin_indexurl;
		showmsg("系统提醒", "添加成功！", $links);
	}
	foreach($white AS $k=>$v) {
		$white[$k] = str_replace("&", "&amp;", $v);
	}
	$smarty->assign('white', $white);
}else{
	if (!empty($_POST['dosubmit'])){
		$links[0]['title'] = '返回后台首页';
		$links[0]['href'] = $_admin_indexurl;
		$verification = array();
		foreach($_POST as $k => $v){
			if (substr($k, 0, 13) == "verification_") {
				$verification[substr($k, 13)] = $v;
			}else{
				!$db->query("UPDATE ".table('peizhi')." SET `value`='{$v}' WHERE `name`='{$k}'")?showmsg("系统提醒", "更新设置失败！", $links):"";
			}
		}
		!$db->query("UPDATE ".table('peizhi_mokuai')." SET `setting`='".array2string($verification)."' WHERE `module`='yanzhengma'")?showmsg("系统提醒", "验证码启动设置失败！", $links):"";
		refresh_cache('peizhi');
		refresh_peizhi_mokuai('yanzhengma');
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
	$verification = $db -> getone("select * from ".table('peizhi_mokuai')." WHERE module='yanzhengma'");
	$smarty->assign('verification',string2array($verification['setting']));
	$smarty->assign('white', get_cache('minrefreshwhitelist'));
	$smarty->assign('peizhi', get_cache('peizhi'));
}

?>