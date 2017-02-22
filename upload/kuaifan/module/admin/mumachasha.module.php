<?php
/*
 * 木马查杀
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');


kf_class::run_sys_func('scan');
//生成MD5
if ($_GET['md5creat']){
	set_time_limit(120);
	$pro = isset($_GET['pro']) && intval($_GET['pro']) ? intval($_GET['pro']) : 1;
	if (substr(KF_ROOT_PATH,-1)!=DIRECTORY_SEPARATOR && substr(KF_ROOT_PATH,-1)=="/"){
		$_path = rtrim(KF_ROOT_PATH,'/').'\\';
	}else{
		$_path = KF_ROOT_PATH;
	}
	$links[0]['title'] = '返回页面';
	$links[0]['href'] = get_link("md5creat|pro");
	$links[1]['title'] = '返回后台首页';
	$links[1]['href'] = $_admin_indexurl;
	switch ($pro) {
		case '1'://统计文件
			showmsg("生成进行中", "正在对所的PHP文件生成MD5验证码，这个过程比较耗时，请等候...", $links, get_link("pro")."&amp;pro=2", 1);
			break;
		case '2':
			$list = scan_file_lists($_path, 1, 'php', 0, 1);
			write_static_cache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_scan'.DIRECTORY_SEPARATOR.'md5_'.date('Y-m-d').'.cache.php',$list);
			showmsg("木马查杀", "生成完成！", $links, $links[0]['href']);
			break;
	}
}
//查看木马
if ($_GET['url']){
	$links[0]['title'] = '返回页面';
	$links[0]['href'] = get_link("report|url").'&amp;report=1';
	$links[1]['title'] = '返回后台首页';
	$links[1]['href'] = $_admin_indexurl;
	$url = new_stripslashes(urldecode(trim($_GET['url'])));
	$url = str_replace("..","",$url);
	if (!file_exists(KF_ROOT_PATH.$url)) {
		showmsg("木马查杀", "文件不存在！", $links);
	}
	$html = file_get_contents(KF_ROOT_PATH.$url);
	//判断文件名，如果是database.php 对里面的关键字符进行替换
	$basename = basename($url);
	if($basename == "config.php"){
		//$html = str_replace();
		showmsg("木马查杀", "重要文件，不允许在线查看！", $links);
	}
	$file_list = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_scan'.DIRECTORY_SEPARATOR.'scan_bad_file.cache.php');
	if (isset($file_list[$url]['func']) && is_array($file_list[$url]['func']) && !empty($file_list[$url]['func'])) foreach ($file_list[$url]['func'] as $key=>$val)
	{
		$func[$key] = strtolower($val[1]);
	}
	if (isset($file_list[$url]['code']) && is_array($file_list[$url]['code']) && !empty($file_list[$url]['code'])) foreach ($file_list[$url]['code'] as $key=>$val)
	{
		$code[$key] = strtolower($val[1]);
	}
	if (isset($func)) $func = array_unique($func);
	if (isset($code)) $code = array_unique($code);
	$_timeval = '[扫描结果]<br/>-------------<br/>';
	if (isset($func)){
		$_timeval.= '特征函数:';
		if ($func) {
			foreach ($func as $val) {
				if($val) {
					$_timeval.= $val."/";
				}
			}
		}
		$_timeval = rtrim($_timeval,'/').'<br/>';
	}
	if (isset($code)){
		$_timeval.= '特征代码:';
		if($code) {
			foreach ($code as $val) {
				if($val) {
					$_timeval.= htmlentities($val)."/";
				}
			}
		}
		$_timeval = rtrim($_timeval,'/').'<br/>';
	}
	$_timeval.= '文件源码:<br/>';
	$_timeval.= nl2br(htmlspecialchars($html));
	showmsg("查看扫描结果", rtrim($_timeval,'<br/>'), $links);
}
//扫描完成
if ($_GET['report']){
	$badfiles = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_scan'.DIRECTORY_SEPARATOR.'scan_bad_file.cache.php');
	$links[0]['title'] = '返回页面';
	$links[0]['href'] = get_link("report");
	$links[1]['title'] = '返回后台首页';
	$links[1]['href'] = $_admin_indexurl;
	if (empty($badfiles)) {
		showmsg("木马查杀", "没有找到扫描结果，请重新扫描。", $links);
	}else{
		//$smarty->assign('badfiles', $badfiles);
		$_timeval = '[扫描结果]<br/>-------------<br/>文件地址|特征函数次数|特征代码次数<br/>';
		foreach($badfiles as $k=>$v) {
			$_timeval.= $_CFG['site_dir'].$k;
			$_timeval.= '|';
			//
			if(isset($v['func'])){
				$_timeval.= count($v['func']);
			}else{
				$_timeval.= '0';
			}
			$_timeval.= '|';
			//
			if(isset($v['code'])){
				$_timeval.= count($v['code']);
			}else{
				$_timeval.= '0';
			}
			$_timeval.= ' <a href="'.get_link("url|report").'&amp;url='.urlencode($k).'">-&gt;查看</a>';
			$_timeval.= '<br/>';
			//
			if(isset($v['func'])){
				foreach ($v['func'] as $keys=>$vs)
				{
					$d[$keys] = strtolower($vs[1]);
				}
				$d = array_unique($d);
				$_timeval.= '特征函数:';
				$_vs = '';
				foreach ($d as $vs)
				{
					$_vs.= $vs."/";
				}
				$_timeval.= rtrim($_vs,'/');
			}
			$_timeval.= '<br/>';

		}
		showmsg("扫描结果", rtrim($_timeval,'<br/>'), $links);
	}
}
//进行特征代码过滤
if ($_GET['code']){
	@set_time_limit(600);
	$file_list = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_scan'.DIRECTORY_SEPARATOR.'scan_list.cache.php');
	$scan = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_scan'.DIRECTORY_SEPARATOR.'scan_config.cache.php');
	$badfiles = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_scan'.DIRECTORY_SEPARATOR.'scan_bad_file.cache.php');
	if (substr(KF_ROOT_PATH,-1)!=DIRECTORY_SEPARATOR && substr(KF_ROOT_PATH,-1)=="/"){
		$_path = rtrim(KF_ROOT_PATH,'/').'\\';
	}else{
		$_path = KF_ROOT_PATH;
	}
	if (isset($scan['code']) && !empty($scan['code'])) {
		foreach ($file_list as $key=>$val) {
			$html = file_get_contents($_path.$key);
			if(stristr($key, '.php.') != false || preg_match_all('/[^a-z]?('.$scan['code'].')/i', $html, $state, PREG_SET_ORDER)) {
				$badfiles[$key]['code'] = $state;
			}
			if(strtolower(substr($key, -4)) == '.php' && function_exists('zend_loader_file_encoded') && zend_loader_file_encoded($_path.$key)) {
				$badfiles[$key]['zend'] = 'zend encoded';
			}
		}
	}
	write_static_cache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_scan'.DIRECTORY_SEPARATOR.'scan_bad_file.cache.php',$badfiles);
	$links[0]['title'] = '返回页面';
	$links[0]['href'] = get_link("code");
	$links[1]['title'] = '返回后台首页';
	$links[1]['href'] = $_admin_indexurl;
	showmsg("木马查杀", "扫描完成！", $links, get_link("report|code").'&amp;report=1', 1);
}
//进行特征函数过滤
if ($_GET['func']){
	@set_time_limit(600);
	$file_list = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_scan'.DIRECTORY_SEPARATOR.'scan_list.cache.php');
	$scan = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_scan'.DIRECTORY_SEPARATOR.'scan_config.cache.php');
	if (substr(KF_ROOT_PATH,-1)!=DIRECTORY_SEPARATOR && substr(KF_ROOT_PATH,-1)=="/"){
		$_path = rtrim(KF_ROOT_PATH,'/').'\\';
	}else{
		$_path = KF_ROOT_PATH;
	}
	if (isset($scan['func']) && !empty($scan['func'])) {
		foreach ($file_list as $key=>$val) {
			$html = file_get_contents($_path.$key);
			if(stristr($key,'.php.') != false || preg_match_all('/[^a-z]?('.$scan['func'].')\s*\(/i', $html, $state, PREG_SET_ORDER)) {
				$badfiles[$key]['func'] = $state;
			}
		}
	}
	if(!isset($badfiles)) $badfiles = array();
	write_static_cache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_scan'.DIRECTORY_SEPARATOR.'scan_bad_file.cache.php',$badfiles);
	$links[0]['title'] = '返回页面';
	$links[0]['href'] = get_link("func");
	$links[1]['title'] = '返回后台首页';
	$links[1]['href'] = $_admin_indexurl;
	showmsg("木马查杀", "特征函数过滤完成，进行特征代码过滤...", $links, get_link("code|func").'&amp;code=1', 1);
}
//对文件进行筛选
if ($_GET['filter']){
	$scan_list = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_scan'.DIRECTORY_SEPARATOR.'scan_list.cache.php');
	$scan = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_scan'.DIRECTORY_SEPARATOR.'scan_config.cache.php');
	if (file_exists($scan['md5_file'])) {
		$old_md5 = include $scan['md5_file'];
		foreach ($scan_list as $k=>$v) {
			if ($v == $old_md5[$k]) {
				unset($scan_list[$k]);
			}
		}
	}
	write_static_cache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_scan'.DIRECTORY_SEPARATOR.'scan_list.cache.php',$scan_list);
	$links[0]['title'] = '返回页面';
	$links[0]['href'] = get_link("filter");
	$links[1]['title'] = '返回后台首页';
	$links[1]['href'] = $_admin_indexurl;
	showmsg("木马查杀", "文件筛选完成，进行特征函数过滤...", $links, get_link("func|filter").'&amp;func=1', 1);
}
//对要进行扫描的文件进行统计
if ($_GET['do']){
	$scan = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_scan'.DIRECTORY_SEPARATOR.'scan_config.cache.php');
	set_time_limit(120);
	$scan['dir'] = string2array($scan['dir']);
	$scan['file_type'] = explode('|', $scan['file_type']);
	$list = array();
	if (substr(KF_ROOT_PATH,-1)!=DIRECTORY_SEPARATOR && substr(KF_ROOT_PATH,-1)=="/"){
		$_path = rtrim(KF_ROOT_PATH,'/').'\\';
	}else{
		$_path = KF_ROOT_PATH;
	}
	foreach ($scan['dir'] as $v) {
		if (substr($v,-8,8)!='.tpl.php' && substr($v,-14,14)!='.tpl.cache.php'){
			$v = $_path.$v;
			if (is_dir($v)) {
				foreach ($scan['file_type'] as $k ) {
					$list = array_merge($list, scan_file_lists($v.DIRECTORY_SEPARATOR, 1, $k, 0, 1, 1));
				}
			} else {
				$list = array_merge($list, array(str_replace($_path, '', $v)=>md5_file($v)));
			}
		}
	}
	write_static_cache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_scan'.DIRECTORY_SEPARATOR.'scan_list.cache.php',$list);
	$links[0]['title'] = '返回页面';
	$links[0]['href'] = get_link("do");
	$links[1]['title'] = '返回后台首页';
	$links[1]['href'] = $_admin_indexurl;
	showmsg("木马查杀", "文件统计完成, 开始文件筛选...", $links, get_link("filter|do").'&amp;filter=1', 1);
}

//进行配置文件更新
if ($_POST['dosubmit']){
	$info = array();
	$info['file_type'] = $_POST['file_type'];
	$info['func'] = $_POST['func'];
	$info['code'] = $_POST['code'];
	$info['md5_file'] = $_POST['md5_file'];
	$_timearr = array();
	foreach ($_POST as $_k => $_v){
		if (substr($_k,0,9)=='mumaname_' && $_v){
			$_timearr[substr($_k,9)]= $_v;
		}
	}
	$dir = new_stripslashes($_timearr);
	$info['dir'] = var_export($dir, true);
	write_static_cache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_scan'.DIRECTORY_SEPARATOR.'scan_config.cache.php',$info);
	$links[0]['title'] = '返回页面';
	$links[0]['href'] = get_link("dosubmit");
	$links[1]['title'] = '返回后台首页';
	$links[1]['href'] = $_admin_indexurl;
	showmsg("木马查杀", "配置保存成功，开始文件统计...", $links, get_link("do|dosubmit").'&amp;do=1', 1);
}
/*
 * **********
 * */
if (substr(KF_ROOT_PATH,-1)!=DIRECTORY_SEPARATOR && substr(KF_ROOT_PATH,-1)=="/"){
	$list = glob(rtrim(KF_ROOT_PATH,'/').'\*');
}else{
	$list = glob(KF_ROOT_PATH.'*');
}
$dir = $file= array();
foreach ($list as $v){
	$filename = basename($v);
	if (is_dir($v)) {
		$dir[$filename]['title'] = $filename;
		$dir[$filename]['url'] = '<img src="'.IMG_PATH.'folder.gif"/> '.$filename.'';
		$dir[$filename]['v'] = str_replace(':','\:',$v);
	} elseif (substr(strtolower($v), -3, 3)=='php'){
		$file[$filename]['title'] = $filename;
		$file[$filename]['url'] = '<img src="'.IMG_PATH.'file.gif"/> '.$filename.'';
		$file[$filename]['v'] = str_replace(':','\:',$v);
	} else {
		continue;
	}
}
$listarr = array_merge($dir, $file);
if (file_exists(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_scan')) {
	if (substr(KF_ROOT_PATH,-1)!=DIRECTORY_SEPARATOR && substr(KF_ROOT_PATH,-1)=="/"){
		$_path = rtrim(KF_ROOT_PATH,'/').'\\';
	}else{
		$_path = KF_ROOT_PATH;
	}
	$md5_file_list = glob($_path.'caches'.DIRECTORY_SEPARATOR.'caches_scan'.DIRECTORY_SEPARATOR.'md5_*.php');
	if(is_array($md5_file_list)) {
		foreach ($md5_file_list as $k=>$v) {
			$md5_file_list[$v] = basename($v);
			unset($md5_file_list[$k]);
		}
	}
}
$safe = array ('file_type' => 'php|js','code' => '','func' => 'com|system|exec|eval|escapeshell|cmd|passthru|base64_decode|gzuncompress','dir' => '', 'md5_file'=>'');
$scan = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_scan'.DIRECTORY_SEPARATOR.'scan_config.cache.php');
if (is_array($scan) && !empty($scan)) {
	$scan = array_merge($safe, $scan);
} else {
	$scan = $safe;
}
$scan['dir'] = string2array($scan['dir']);
$_listarr = array();
foreach ($listarr as $_k => $_v){
	$_v['d'] = $scan['dir'][str_replace('.','',$_v['title'])];
	$_listarr[$_k] = $_v;
}
kf_class::run_sys_class('form','',0);
$__md5_file = form::select($md5_file_list,$scan['md5_file'],'name="md5_file'.fenmiao().'"');
$smarty->assign('scanarr', $scan);
$smarty->assign('listarr', $_listarr);
$smarty->assign('md5_file', $__md5_file);

?>