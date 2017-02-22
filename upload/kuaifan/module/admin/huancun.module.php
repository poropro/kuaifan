<?php
/*
 * 更新缓存
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');


$links[0]['title'] = '返回后台首页';
$links[0]['href'] = $_admin_indexurl;
$cachenum = 12;
switch ($_GET['a']){
	case "1": //网站配置
		refresh_cache('peizhi');
		showmsg("系统提醒", "更新网站配置缓存成功！(1/{$cachenum})", $links, get_link("a")."&amp;a=2", 1);
		break;
	case "2": //模型
		refresh_cache_all('neirong_moxing');
		showmsg("系统提醒", "更新模型缓存成功！(2/{$cachenum})", $links, get_link("a")."&amp;a=3", 1);
		break;
	case "3": //模型字段
		$arr = $db->getall("SELECT * FROM ".table('neirong_moxing')." ORDER BY id asc");
		foreach($arr as $_value) {
			cache_field($_value['id']);
		}
		showmsg("系统提醒", "更新模型字段缓存成功！(3/{$cachenum})", $links, get_link("a")."&amp;a=4", 1);
		break;
	case "4": //栏目
		refresh_cache_column($_CFG['site']);
		showmsg("系统提醒", "更新栏目缓存成功！(4/{$cachenum})", $links, get_link("a")."&amp;a=5", 1);
		break;
	case "5": //排版
		refresh_cache_paiban();
		showmsg("系统提醒", "更新排版缓存成功！(5/{$cachenum})", $links, get_link("a")."&amp;a=6", 1);
		break;
	case "6": //会员组
		refresh_cache_all('huiyuan_zu','','groupid');
		showmsg("系统提醒", "更新会员组缓存成功！(6/{$cachenum})", $links, get_link("a")."&amp;a=7", 1);
		break;
	case "7": //会员模型
		refresh_cache_all('huiyuan_moxing');
		showmsg("系统提醒", "更新会员模型缓存成功！(7/{$cachenum})", $links, get_link("a")."&amp;a=8", 1);
		break;
	case "8": //会员模型字段
		$arr = $db->getall("SELECT * FROM ".table('huiyuan_moxing')." ORDER BY id asc");
		foreach($arr as $_value) {
			cache_field_huiyuan($_value['id']);
		}
		showmsg("系统提醒", "更新会员模型字段缓存成功！(8/{$cachenum})", $links, get_link("a")."&amp;a=9", 1);
		break;
	case "9": //会员分组
		refresh_cache_all('huiyuan_zu','','groupid');
		showmsg("系统提醒", "更新会员分组缓存成功！(9/{$cachenum})", $links, get_link("a")."&amp;a=10", 1);
		break;
	case "10": //模块前台地址
		refresh_cache_mokuaiurl();
		showmsg("系统提醒", "更新模块地址缓存成功！(10/{$cachenum})", $links, get_link("a")."&amp;a=11", 1);
		break;
	case "11": //模块配置
		refresh_peizhi_mokuai();
		showmsg("系统提醒", "模块配置！(11/{$cachenum})", $links, get_link("a")."&amp;a=del", 1);
		break;
	case "del": //删除模板缓存
		removeDir(KF_ROOT_PATH.'templates/templet_cache/_cache/');
		removeDir(KF_ROOT_PATH.'templates/templet_cache/');
		removeDir(KF_ROOT_PATH.DIR_PATH.'index/caches_paiban/');
		showmsg("系统提醒", "删除模板缓存成功！(12/{$cachenum})", $links, get_link("a")."&amp;a=ok", 1);
		break;
	case "ok": //更新缓存结束
		showmsg("系统提醒", "<a href=\"".get_link("a")."\">重新更新</a><br/>所有缓存更新完毕！", $links);
		break;
	default:
		showmsg("系统提醒", "准备开始更新缓存！", $links, get_link("a")."&amp;a=1");
}

?>