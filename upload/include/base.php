<?php
/**
 * KFCMS 共用配置文件
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
@session_start();
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

//定义框架路径
define('KF_INC_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR);

//定义主目录路径
define('KF_ROOT_PATH',dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR);

//过滤错误信息
error_reporting(E_ERROR);

//加载数据库信息文件
require(KF_ROOT_PATH.'caches/config.php');
if (empty($dbhost)) {
	if(!defined('INSTALL')) {
    header("Location: install/index.htm");
    exit();
	}
}

//加载版本信息文件
require(KF_INC_PATH.'kfcms_version.php');

//计算加载时间
$__run_time = kf_class::run_sys_class('runtime');
$__run_time -> start();

//加载公用函数库
kf_class::run_sys_func('global');
kf_class::run_sys_func('extention');

//过滤 GET html标签提交
$_GET  = addslashes_deep($_GET); 
//过滤 COOKIE html标签提交
$_COOKIE   = addslashes_deep($_COOKIE);
//过滤 REQUEST html标签提交
$_REQUEST  = addslashes_deep($_REQUEST);

//设置本地时差
date_default_timezone_set("PRC");

//赋值时间IP
$timestamp = time();
$online_ip = getip();

//加载页面数据
$_PAGE = get_cache('page');
//加载配置数据
$_CFG = get_cache('peizhi');
//网站域名配置
$_CFG['site_domain'] = !isset($_SERVER['HTTP_HOST'])?'':isset($_SERVER['SERVER_PORT'])&&$_SERVER['SERVER_PORT']=='443'?'https://':'http://'.$_SERVER['HTTP_HOST'];
//POST处理
if (!empty($_CFG['open_post'])){
    if ($_CFG['open_post']=='addslashes'){
        if (!get_magic_quotes_gpc()) $_POST = new_addslashes($_POST);
    }elseif ($_CFG['open_post']=='stripslashes'){
        $_POST = new_stripslashes($_POST);
    }elseif ($_CFG['open_post']=='htmlspecialchars'){
        $_POST = new_html_special_chars($_POST);
    }elseif ($_CFG['open_post']=='strip_tags'){
        $_POST = new_strip_tags($_POST);
    }
}

//载入360SQL防入注
if ($_CFG['open_sql'] == '1') require(KF_INC_PATH.'safe/360webscan.php'); 

//处理配置数据
$_CFG['site'] = 1;
$_CFG['version'] = KUAIFAN_VERSION;
$_CFG['site_template'] = $_CFG['site_dir'].'templates/'.$_CFG['template_dir']; //模板目录


define('SYS_TIME', $timestamp);
//定义网站根路径
define('WEB_PATH',$_CFG['site_dir']);
//定义模板相对路径
define('DIR_PATH','templates/'.$_CFG['template_dir']);
//定义模板根目录
define('TEM_PATH',$_CFG['site_dir'].'templates/'.$_CFG['template_dir']);
//statics 路径
define('ST_PATH',$_CFG['site_domain'].TEM_PATH.'statics/');
//js 路径
define('JS_PATH',$_CFG['site_domain'].TEM_PATH.'statics/js/');
//css 路径
define('CSS_PATH',$_CFG['site_domain'].TEM_PATH.'statics/css/');
//img 路径
define('IMG_PATH',$_CFG['site_domain'].TEM_PATH.'statics/images/');

//浏览版本
$_GET['vs'] = intval($_GET['vs']);
//缓存版本
if (empty($_GET['vs'])){
  if (!empty($_SESSION['vs'])) $_GET['vs'] = $_SESSION['vs'];
}
//自动识别
if (empty($_GET['vs'])){
	kf_class::run_sys_class('mobile','',0);
	$_detect = new Mobile_Detect();
	$__banben_arr = getcache(KF_ROOT_PATH. "caches/caches_peizhi_mokuai/cache.banben.php");
	if ($__banben_arr['isAuto'] == '1'){
		//用户自定义
		if ($_detect->isMobile()){
			if ($_detect->isAndroidOS()){
				$_GET['vs'] = $__banben_arr['isAndroidOS'];
			}elseif ($_detect->isBlackBerryOS()){
				$_GET['vs'] = $__banben_arr['isBlackBerryOS'];
			}elseif ($_detect->isPalmOS()){
				$_GET['vs'] = $__banben_arr['isPalmOS'];
			}elseif ($_detect->isSymbianOS()){
				$_GET['vs'] = $__banben_arr['isSymbianOS'];
			}elseif ($_detect->isWindowsMobileOS()){
				$_GET['vs'] = $__banben_arr['isWindowsMobileOS'];
			}elseif ($_detect->isWindowsPhoneOS()){
				$_GET['vs'] = $__banben_arr['isWindowsPhoneOS'];
			}elseif ($_detect->isiOS()){
				$_GET['vs'] = $__banben_arr['isiOS'];
			}elseif ($_detect->isMeeGoOS()){
				$_GET['vs'] = $__banben_arr['isMeeGoOS'];
			}elseif ($_detect->isMaemoOS()){
				$_GET['vs'] = $__banben_arr['isMaemoOS'];
			}elseif ($_detect->isJavaOS()){
				$_GET['vs'] = $__banben_arr['isJavaOS'];
			}elseif ($_detect->iswebOS()){
				$_GET['vs'] = $__banben_arr['iswebOS'];
			}elseif ($_detect->isbadaOS()){
				$_GET['vs'] = $__banben_arr['isbadaOS'];
			}elseif ($_detect->isBREWOS()){
				$_GET['vs'] = $__banben_arr['isBREWOS'];
			}else{
				$_GET['vs'] = $__banben_arr['isOther'];
			}
		}elseif ($_detect->isTablet()){
			$_GET['vs'] = $__banben_arr['isTablet'];
		}else{
			if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 9.0')){    
				$_GET['vs'] = $__banben_arr['isIE'];
			}elseif(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 8.0')){    
				$_GET['vs'] = $__banben_arr['isIE'];
			}elseif(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 7.0')){    
				$_GET['vs'] = $__banben_arr['isIE'];
			}elseif(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 6.0')){    
				$_GET['vs'] = $__banben_arr['isIE'];
			}elseif(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Firefox')){    
				$_GET['vs'] = $__banben_arr['isFirefox'];
			}elseif(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Chrome')){    
				$_GET['vs'] = $__banben_arr['isChrome'];
			}elseif(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Safari')){    
				$_GET['vs'] = $__banben_arr['isSafari'];
			}elseif(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Opera')){    
				$_GET['vs'] = $__banben_arr['isOpera'];
			}else{
				$_GET['vs'] = $__banben_arr['iswebOther'];
			}
		}
	}else{
		//系统定义
		if ($_detect->isMobile()){
			if ($_detect->isAndroidOS() || $_detect->isiOS()){
				$_GET['vs'] = 3;
			}else{
				$_GET['vs'] = 2;
			}
		}elseif ($_detect->isTablet()){
			$_GET['vs'] = 4;
		}else{
			$_GET['vs'] = 5;
		}
	}
}
//强制版本
if ($_CFG['vs'] > 0 && $_GET['m'] != 'admin') {
	if ($_CFG['vs'] == '99') {
		if (empty($__banben_arr)) $__banben_arr = getcache(KF_ROOT_PATH. "caches/caches_peizhi_mokuai/cache.banben.php");
		if ($_GET['vs'] == 1 && $__banben_arr['isvs1'] > 0) $_GET['vs'] = $__banben_arr['isvs1'];
		if ($_GET['vs'] == 2 && $__banben_arr['isvs2'] > 0) $_GET['vs'] = $__banben_arr['isvs2'];
		if ($_GET['vs'] == 3 && $__banben_arr['isvs3'] > 0) $_GET['vs'] = $__banben_arr['isvs3'];
		if ($_GET['vs'] == 4 && $__banben_arr['isvs4'] > 0) $_GET['vs'] = $__banben_arr['isvs4'];
		if ($_GET['vs'] == 5 && $__banben_arr['isvs5'] > 0) $_GET['vs'] = $__banben_arr['isvs5'];
	}else{
		$_GET['vs'] = $_CFG['vs'];
	}
}
if ($_GET['vs'] < 1) $_GET['vs'] = 1;
if ($_GET['vs'] > 5) $_GET['vs'] = 5;
$_SESSION['vs'] = $_GET['vs'];

//处理返回来源地址变量
if ($_GET['go_url']) {
	$_GET['go_url'] = urlencode($_GET['go_url']);
}

/**
 *
 */
class kf_class {
	
	/**
	 * 初始化应用程序
	 */
	public static function creat_app() {
		return self::run_sys_class('application');
	}
	/**
	 * 加载系统类方法
	 * @param string $classname 类名
	 * @param string $path 扩展地址
	 * @param intger $initialize 是否初始化
	 */
	public static function run_sys_class($classname, $path = '', $initialize = 1) {
			return self::_run_class($classname, $path, $initialize);
	}
	
	/**
	 * 加载应用类方法
	 * @param string $classname 类名
	 * @param string $m 模块
	 * @param intger $initialize 是否初始化
	 */
	public static function run_app_class($classname, $m = '', $initialize = 1) {
		$m = empty($m) && defined('ROUTE_M') ? ROUTE_M : $m;
		if (empty($m)) return false;
		return self::_run_class($classname, 'modules'.DIRECTORY_SEPARATOR.$m.DIRECTORY_SEPARATOR.'classes', $initialize);
	}
	
	/**
	 * 加载数据模型
	 * @param string $classname 类名
	 */
	public static function run_model($classname) {
		return self::_run_class($classname,'model');
	}
		
	/**
	 * 加载类文件函数
	 * @param string $classname 类名
	 * @param string $path 扩展地址
	 * @param intger $initialize 是否初始化
	 */
	private static function _run_class($classname, $path = '', $initialize = 1) {
		static $classes = array();
		if (empty($path)) $path = 'libs'.DIRECTORY_SEPARATOR.'classes';

		$key = md5($path.$classname);
		if (isset($classes[$key])) {
			if (!empty($classes[$key])) {
				return $classes[$key];
			} else {
				return true;
			}
		}
		if (file_exists(KF_INC_PATH.$path.DIRECTORY_SEPARATOR.$classname.'.class.php')) {
			include KF_INC_PATH.$path.DIRECTORY_SEPARATOR.$classname.'.class.php';
			$name = $classname;
			if ($my_path = self::my_path(KF_INC_PATH.$path.DIRECTORY_SEPARATOR.$classname.'.class.php')) {
				include $my_path;
				$name = 'MY_'.$classname;
			}
			if ($initialize) {
				$classes[$key] = new $name;
			} else {
				$classes[$key] = true;
			}
			return $classes[$key];
		} else {
			return false;
		}
	}
	
	/**
	 * 加载系统的函数库
	 * @param string $func 函数库名
	 */
	public static function run_sys_func($func) {
		return self::_run_func($func);
	}
	
	/**
	 * 自动加载autoload目录下函数库
	 * @param string $func 函数库名
	 */
	public static function run_load_func($path='') {
		return self::_run_load_func($path);
	}
	
	/**
	 * 加载应用函数库
	 * @param string $func 函数库名
	 * @param string $m 模型名
	 */
	public static function run_app_func($func, $m = '') {
		$m = empty($m) && defined('ROUTE_M') ? ROUTE_M : $m;
		if (empty($m)) return false;
		return self::_run_func($func, 'modules'.DIRECTORY_SEPARATOR.$m.DIRECTORY_SEPARATOR.'functions');
	}
	
	/**
	 * 加载插件类库
	 */
	public static function run_plugin_class($classname, $identification = '' ,$initialize = 1) {
		$identification = empty($identification) && defined('PLUGIN_ID') ? PLUGIN_ID : $identification;
		if (empty($identification)) return false;
		return kf_class::run_sys_class($classname, 'plugin'.DIRECTORY_SEPARATOR.$identification.DIRECTORY_SEPARATOR.'classes', $initialize);
	}
	
	/**
	 * 加载插件函数库
	 * @param string $func 函数文件名称
	 * @param string $identification 插件标识
	 */
	public static function run_plugin_func($func,$identification) {
		static $funcs = array();
		$identification = empty($identification) && defined('PLUGIN_ID') ? PLUGIN_ID : $identification;
		if (empty($identification)) return false;
		$path = 'plugin'.DIRECTORY_SEPARATOR.$identification.DIRECTORY_SEPARATOR.'functions'.DIRECTORY_SEPARATOR.$func.'.func.php';
		$key = md5($path);
		if (isset($funcs[$key])) return true;
		if (file_exists(KF_INC_PATH.$path)) {
			include KF_INC_PATH.$path;
		} else {
			$funcs[$key] = false;
			return false;
		}
		$funcs[$key] = true;
		return true;
	}
	
	/**
	 * 加载插件数据模型
	 * @param string $classname 类名
	 */
	public static function run_plugin_model($classname,$identification) {
		$identification = empty($identification) && defined('PLUGIN_ID') ? PLUGIN_ID : $identification;
		$path = 'plugin'.DIRECTORY_SEPARATOR.$identification.DIRECTORY_SEPARATOR.'model';
		return self::_run_class($classname,$path);
	}
	
	/**
	 * 加载函数库
	 * @param string $func 函数库名
	 * @param string $path 地址
	 */
	private static function _run_func($func, $path = '') {
		static $funcs = array();
		if (empty($path)) $path = 'libs'.DIRECTORY_SEPARATOR.'functions';
		$path .= DIRECTORY_SEPARATOR.$func.'.func.php';
		$key = md5($path);
		if (isset($funcs[$key])) return true;
		if (file_exists(KF_INC_PATH.$path)) {
			include KF_INC_PATH.$path;
		} else {
			$funcs[$key] = false;
			return false;
		}
		$funcs[$key] = true;
		return true;
	}
	
	/**
	 * 加载函数库
	 * @param string $func 函数库名
	 * @param string $path 地址
	 */
	private static function _run_load_func($path = '') {
		if (empty($path)) $path = 'libs'.DIRECTORY_SEPARATOR.'functions'.DIRECTORY_SEPARATOR.'autoload';
		$path .= DIRECTORY_SEPARATOR.'*.func.php';
		$auto_funcs = glob(PC_PATH.DIRECTORY_SEPARATOR.$path);
		if(!empty($auto_funcs) && is_array($auto_funcs)) {
			foreach($auto_funcs as $func_path) {
				include $func_path;
			}
		}
	}
	/**
	 * 是否有自己的扩展文件
	 * @param string $filepath 路径
	 */
	public static function my_path($filepath) {
		$path = pathinfo($filepath);
		if (file_exists($path['dirname'].DIRECTORY_SEPARATOR.'MY_'.$path['basename'])) {
			return $path['dirname'].DIRECTORY_SEPARATOR.'MY_'.$path['basename'];
		} else {
			return false;
		}
	}
	
	/**
	 * 加载配置文件
	 * @param string $file 配置文件
	 * @param string $key  要获取的配置荐
	 * @param string $default  默认配置。当获取配置项目失败时该值发生作用。
	 * @param boolean $reload 强制重新加载。
	 */
	public static function run_config($file, $key = '', $default = '', $reload = false) {
		static $configs = array();
		if (!$reload && isset($configs[$file])) {
			if (empty($key)) {
				return $configs[$file];
			} elseif (isset($configs[$file][$key])) {
				return $configs[$file][$key];
			} else {
				return $default;
			}
		}
		$path = CACHE_PATH.'caches'.DIRECTORY_SEPARATOR.$file.'.php';
		if (file_exists($path)) {
			$configs[$file] = include $path;
		}
		if (empty($key)) {
			return $configs[$file];
		} elseif (isset($configs[$file][$key])) {
			return $configs[$file][$key];
		} else {
			return $default;
		}
	}
	/**
	 * 加载UCenter
	 */
	public static function ucenter() {
		include KF_ROOT_PATH.'caches/config_uc.php';
		if (UC_USE == '1'){
			require_once KF_ROOT_PATH.'kuaifan/api/uc_client/client.php';
		}
	}
}
?>