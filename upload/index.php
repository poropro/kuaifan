<?php
/*
* ============================================================================
* 版权所有: 快范网络，并保留所有权利。
* 网站地址: http://www.kuaifan.net；
* ----------------------------------------------------------------------------
* 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
* 使用；不允许对程序代码以任何形式任何目的的再发布。
* ============================================================================
*/

define('IN_KUAIFAN', true);
$alias="KF_index";
require(dirname(__FILE__).'/include/base.php');

//返回存入cookie
$backarr = set_backhttp();
$backhttp = $backarr["back"];
$_backhttp = $backarr["_back"];
unset($backarr);


//模板变量
$_GET['m'] = $_GET['m']?$_GET['m']:'index';
$templatedis = $_GET['m'];		//模板目录名
$templatefile = $_get_c_val = $_GET['c']?$_GET['c']:"index"; 		//模板文件名

//只加载元素
if ($_GET['m'] == 'api'){
    //回滚返回地址backhttp
    back_backhttp();
    //防SQL入注
    if ($_CFG['open_sql_true']) exit('0');
    //链接数据库
    kf_class::run_sys_class('mysql','',0);
    $db = new mysql($dbhost,$dbuser,$dbpass,$dbname);
    unset($dbhost,$dbuser,$dbpass,$dbname);
    //加载关键文件
    if (file_exists(KF_ROOT_PATH.'kuaifan/'.$templatedis.'.class.php')) {
        include KF_ROOT_PATH.'kuaifan/'.$templatedis.'.class.php';
    }
    if (file_exists(KF_ROOT_PATH.'kuaifan/module/'.$_GET['m'].'/'.$_get_c_val.'.module.php')) {
        include KF_ROOT_PATH.'kuaifan/module/'.$_GET['m'].'/'.$_get_c_val.'.module.php';
    }
    exit();
}

//加载Smarty
require(KF_INC_PATH.'libs/Smarty.class.php');
$smarty = new Smarty;

//配置Smarty
if ($_CFG['template_lifetime'] > 0){
    $smarty->caching = true; //使用缓存
    $smarty->cache_lifetime = $_CFG['template_lifetime']; //缓存生存时间变量
}else{
    $smarty->caching = false; //关闭缓存
}
$smarty->template_dir = DIR_PATH; //模板路径
$smarty->compile_dir = 'templates/templet_cache/';  //模板编译路径
$smarty->cache_dir = 'templates/templet_cache/_cache/';  //模板缓存
$smarty->left_delimiter ="{#";
$smarty->right_delimiter ="#}";

//自定义变量、函数
$smarty->assign('VS', $_GET['vs']);
$smarty->assign('TIME', SYS_TIME);
$smarty->assign('TIME2', fenmiao());
$smarty->assign('KUAIFAN', $_CFG);
$smarty->assign('backhttp', $backhttp);
$smarty->registerPlugin("function", "form", "format_form", false);
$smarty->registerPlugin("function", "kuaifan", "format_kuaifan", false);
$smarty->registerPlugin("block", "nocache", "format_block_nocache", false);
$smarty->registerPlugin("block", "ubb", "format_ubb", false);
$smarty->registerPlugin("block", "wml", "format_wml", false);

//判断网站是否关闭
if ($_CFG['isclose'] == '1'){
    if ($_GET['m'] != 'admin'){
        $links[0]['title'] = '稍后继续访问';
        $links[0]['href'] = "#close";
        $smarty->assign('nodb', 1);
        showmsg("暂时关站", "网站暂时关闭<br/>暂关原因: ".$_CFG['close_reason'], $links);
    }
}

//防SQL入注
if ($_CFG['open_sql_true']) {
    $links[0]['title'] = '返回继续';
    $links[0]['href'] = "-1";
    $links[1]['title'] = '返回网站首页';
    $links[1]['href'] = kf_url('index');
    $smarty->assign('nodb', 1);
    showmsg("安全提示", "输入内容存在危险字符，安全起见，已被本站拦截！", $links);
}

//两次刷新间隔
if ($_CFG['minrefreshtime']) {
	$_mwhitelist = get_cache('minrefreshwhitelist');
	if ($_mwhitelist) {
		if (is_array($_mwhitelist) && in_array(get_url(), $_mwhitelist)) {
			$_mwhitelist = true;
		}
	}
	if (!is_bool($_mwhitelist)) {
		list($usec, $sec) = explode(' ', microtime());
		$__minrefreshtime = ((float)$usec + (float)$sec);
		if (($__minrefreshtime - $_SESSION['minrefreshtime'])*1000 < $_CFG['minrefreshtime']){
			$links[0]['title'] = '继续访问';
			$links[0]['href'] = get_link();
			$links[1]['title'] = '返回网站首页';
			$links[1]['href'] = kf_url('index');
			$smarty->assign('nodb', 1);
			showmsg("系统提醒", "亲，您的稍作忒快了，先休息一下吧！", $links);
		}else{
			$_SESSION['minrefreshtime'] = $__minrefreshtime;
		} unset($usec,$sec);
	}
}

//CSRF防御
check_csrf_token();

//链接数据库
kf_class::run_sys_class('mysql','',0);
$db = new mysql($dbhost,$dbuser,$dbpass,$dbname);
unset($dbhost,$dbuser,$dbpass,$dbname);

//会员信息函数
require(KF_INC_PATH.'huiyuan.php');
$smarty->assign('SID', US_SID);
$smarty->cache_dir = 'templates/templet_cache/_cache/'.US_USERID.'/';  //模板缓存

//SEO相关传递（前）
$_SEO = array();

//新版本功能（插件）
if (file_exists(KF_ROOT_PATH.'kuaifan/addons/'.$_GET['m'].'/site.php')) {
    if (!$_SEO['title']) $_SEO['title'] = $_CFG['site_name'];
    $smarty->assign('SEO', $_SEO);
    include KF_INC_PATH.'addons.php';
}

//加载关键文件
if (file_exists(KF_ROOT_PATH.'kuaifan/'.$templatedis.'.class.php')) {
    include KF_ROOT_PATH.'kuaifan/'.$templatedis.'.class.php';
}
if (file_exists(KF_ROOT_PATH.'kuaifan/module/'.$_GET['m'].'/'.$_get_c_val.'.module.php')) {
    include KF_ROOT_PATH.'kuaifan/module/'.$_GET['m'].'/'.$_get_c_val.'.module.php';
}
//拓展、插件
if (file_exists(KF_ROOT_PATH.'kuaifan/plugins/'.$templatedis.'.class.php')) {
    include KF_ROOT_PATH.'kuaifan/plugins/'.$templatedis.'.class.php';
}
if (file_exists(KF_ROOT_PATH.'kuaifan/plugins/'.$_GET['m'].'/'.$_get_c_val.'.module.php')) {
    include KF_ROOT_PATH.'kuaifan/plugins/'.$_GET['m'].'/'.$_get_c_val.'.module.php';
}

//SEO相关传递（后）
if (!$_SEO['title']) $_SEO['title'] = $_CFG['site_name'];
$smarty->assign('SEO', $_SEO);

//加载模板文件
$smarty->display(get_tpl($templatedis.'/'.$templatefile), __smarty_display());
unset($smarty);
?>