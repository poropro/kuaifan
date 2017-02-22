<?php
/*
 * 后台系统
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');
kf_class::run_sys_func('admin');
unset($_GET['sid']);

//后台关闭缓存
$smarty->caching = false;

//后台强制更新缓存
//$smarty->force_compile = true;

//js 路径
define('AJS_PATH',$_CFG['site_domain'].TEM_PATH.'statics/admin/js/');
//css 路径
define('ACSS_PATH',$_CFG['site_domain'].TEM_PATH.'statics/admin/css/');
//img 路径
define('AIMG_PATH',$_CFG['site_domain'].TEM_PATH.'statics/admin/images/');

//back_backhttp();
$_admin_indexurl = get_link("m|allow|vs","",1);
$smarty->assign('admin_indexurl', $_admin_indexurl);

/* 判断后台域名 */
if ($_CFG['admin_url']) {
	if ($_CFG['admin_url'] != $_SERVER['SERVER_NAME']) {
		$links[0]['title'] = '返回网站首页';
		$links[0]['href'] = kf_url('index');
		showmsg("系统提醒", "正在返回网站首页！", $links, $links[0]['href']);
	}
}

/* 判断登录 */
if ($_GET['c'] != 'login'){
	require(KF_INC_PATH.'denglu_admin.php');
}

/* 检测新版本升级 */
if ($_CFG['shengjiauto'] > 0){
	if ($_GET['c'] == 'hulueshengji'){
		!$db->query("UPDATE ".table('peizhi')." SET value='".$timestamp."' WHERE name='shengjiauto_time'")?showmsg("系统提醒", "忽略失败！", $links):"";
		refresh_cache('peizhi');
		$links[0]['title'] = '返回后台首页';
		$links[0]['href'] = $_admin_indexurl;
		showmsg("系统提醒", "保存成功！", $links, $_admin_indexurl, 1);
	}
	if ($_GET['c'] != 'shengji' && $_GET['c'] != 'login'){
		$_shengjitime = intval(strtotime(date("Y-m-d",$_CFG['shengjiauto_time']))-strtotime(date("Y-m-d")));
		if ($_shengjitime<0) $_shengjitime = $_shengjitime*(-1);
		if (intval($_shengjitime/86400) >= intval($_CFG['shengjiauto'])){
			$system_info = array();
			$system_info['version'] = KUAIFAN_VERSION;
			$system_info['release'] = KUAIFAN_RELEASE;
            $_patchurl = "http://download.kuaifan.net/develop/getlist/program_1.php?to=".KUAIFAN_RELEASE;
			if (!varify_url($_patchurl)){
				$db->query("UPDATE ".table('peizhi')." SET value='".$timestamp."' WHERE name='shengjiauto_time'");
				refresh_cache('peizhi');
				$links[0]['title'] = '返回后台首页';
				$links[0]['href'] = $_admin_indexurl;
				showmsg("系统提醒", "互联网连接失败无法检测新版本, 已经忽略本次检测！", $links);
			}
			$patch_charset = str_replace('-', '', CHARSET);
			$pathlist_str = @file_get_contents($_patchurl."&char=".$patch_charset);
            if ($pathlist_str) {
                $allpathlist = json_decode($pathlist_str, true);
                if (count($allpathlist['list']) > 0) {
                    $links[0]['title'] = '查看详情';
                    $links[0]['href'] = $_admin_indexurl.'&amp;c=shengji';
                    $links[1]['title'] = '忽略本次并提醒';
                    $links[1]['href'] = $_admin_indexurl.'&amp;c=hulueshengji';
                    showmsg("升级提醒", "发现有新版本程序可升级！", $links);
                }
            }
            $db->query("UPDATE ".table('peizhi')." SET `value`='".$timestamp."' WHERE `name`='shengjiauto_time'");
            refresh_cache('peizhi');
		}
	}
}

/* 定义模板路径 */
$_GET['a'] = $_GET['a']?$_GET['a']:"index";
$templatefile.= "/".$_GET['a'];
?>