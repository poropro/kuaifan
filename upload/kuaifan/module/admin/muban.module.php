<?php
/*
 * 模板风格
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */

if(!defined('IN_KUAIFAN')) exit('Access Denied!');

//备份
if ($_GET['beifen']=='1'){
	if (substr($_GET['f'], -4, 4) == '.tpl' || substr($_GET['f'], -4, 4) == '.css' || substr($_GET['f'], -3, 3) == '.js'){
		$links[0]['title'] = '返回上一级';
		$links[0]['href'] = get_link('beifen|f').'&amp;f='.urlencode($_GET['f']);
		$_dir = KF_ROOT_PATH.str_replace('|',DIRECTORY_SEPARATOR,$_GET['f']);
		$newfile = KF_ROOT_PATH.'templates'.DIRECTORY_SEPARATOR.'templet_backup'.DIRECTORY_SEPARATOR;
		$newfile.= substr(str_replace('|', '-', $_GET['f']), 0, -4).DIRECTORY_SEPARATOR; mkdir($newfile);
		$newfile.= $timestamp.'-'.getsecond().'.'.substr($_GET['f'], -4, 4).'.bak';
		if (file_exists($_dir)) {
			if (!copy($_dir, $newfile)) {
				showmsg("系统提醒", "备份失败，请稍后再试！",$links);
			}else{
				admin_log("备份模板文件".str_replace('|', '/', $_GET['f'])."。", $admin_val['name']);
				showmsg("系统提醒", "备份成功！",$links);
			}
		}else{
			showmsg("系统提醒", "文件不存在！");
		}
	}
}
//删除备份
if ($_GET['beifen']=='2'){
	if (substr($_GET['f'], -4, 4) == '.tpl' || substr($_GET['f'], -4, 4) == '.css' || substr($_GET['f'], -3, 3) == '.js'){
		$links[0]['title'] = '返回上一级';
		$links[0]['href'] = get_link('beifen|f').'&amp;f='.urlencode($_GET['f']);
		$_dir = KF_ROOT_PATH.'templates'.DIRECTORY_SEPARATOR.'templet_backup'.DIRECTORY_SEPARATOR;
		$_dir.= substr(str_replace('|', '-', $_GET['f']), 0, -4).DIRECTORY_SEPARATOR;
		if (!removeDir($_dir)) {
			showmsg("系统提醒", "删除失败，请稍后再试！",$links);
		}else{
			admin_log("删除模板备份".str_replace('|', '/', $_GET['f'])."。", $admin_val['name']);
			showmsg("系统提醒", "删除成功！",$links);
		}

	}
}
//还原
if ($_POST['reduction']){
	$_dir = KF_ROOT_PATH.str_replace('|',DIRECTORY_SEPARATOR,$_GET['f']);
	$bakfile = KF_ROOT_PATH.'templates'.DIRECTORY_SEPARATOR.'templet_backup'.DIRECTORY_SEPARATOR;
	$bakfile.= substr(str_replace('|', '-', $_GET['f']), 0, -4).DIRECTORY_SEPARATOR;
	$bakfile.= $_POST['reduction'];
	$links[0]['title'] = '返回上一级';
	$links[0]['href'] = get_link('beifen|f').'&amp;f='.urlencode($_GET['f']);
	if (file_exists($bakfile)) {
		if (!copy($bakfile, $_dir)) {
			showmsg("系统提醒", "还原失败，请稍后再试！",$links);
		}else{
			admin_log("还原模板文件".str_replace('|', '/', $_GET['f'])."。", $admin_val['name']);
			showmsg("系统提醒", "还原成功！",$links);
		}
	}else{
		showmsg("系统提醒", "文件不存在！");
	}
}
//修改保存
if ($_POST['filedata']){
	$_dir = KF_ROOT_PATH.str_replace('|',DIRECTORY_SEPARATOR,$_GET['f']);
	$filedata = trim($_POST['filedata']);
	$links[0]['title'] = '返回上一级';
	$links[0]['href'] = get_link('f').'&amp;f='.urlencode($_GET['f']);
	if (file_exists($_dir)) {
		write_flie_text($_dir,$filedata);
		admin_log("修改模板文件".str_replace('|', '/', $_GET['f'])."。", $admin_val['name']);
		showmsg("系统提醒", "保存成功！",$links);
	}else{
		showmsg("系统提醒", "要保存的文件不存在！");
	}
}
//新建文件
if ($_POST['newfile']){
	if (empty($_GET['f'])){
		$_GET['f'] = "templates".DIRECTORY_SEPARATOR.$_CFG['template_dir'];
		$_GET['f'] = str_replace(array('/','\\'), '|', $_GET['f']);
	}
	$_dir = KF_ROOT_PATH.str_replace('|',DIRECTORY_SEPARATOR,$_GET['f']);
	$_dir.= $_POST['newfile'].'.tpl';
	if (is_english(str_replace('.','',$_POST['newfile']),1) != '1'){
		showmsg("系统提醒", "名称只能为数字、字母、下划线！");}
	$fp = fopen($_dir,"w+");
	$links[0]['title'] = '继续新建';
	$links[0]['href'] = get_link();
	$links[1]['title'] = '返回列表页';
	$links[1]['href'] = get_link('a|f').'&amp;a=index&amp;f='.urlencode($_GET['f']);
	if (!$fp){
		showmsg("系统提醒", "新建失败，请稍后再试！",$links);
	}else{
		admin_log("新建模板文件".str_replace('|', '/', $_GET['f']).$_POST['newfile'].".tpl。", $admin_val['name']);
		showmsg("系统提醒", "新建成功！",$links);
	}
	fclose($fp);
}
if ($_GET['a'] == 'xinjian'){
	if (empty($_GET['f'])){
		$_GET['f'] = "templates".DIRECTORY_SEPARATOR.$_CFG['template_dir'];
		$_GET['f'] = str_replace(array('/','\\'), '|', $_GET['f']);
	}
	$f = str_replace(array('/','\\','|'), DIRECTORY_SEPARATOR, $_CFG['site_dir'].$_GET['f']);
	$smarty->assign('f', $f);
}

//查看说明文件
if (substr($_GET['f'], -10, 10)=='readme.txt'){
	$_dir = KF_ROOT_PATH.str_replace('|',DIRECTORY_SEPARATOR,$_GET['f']);
	$links[0]['title'] = '返回上一级';
	$links[0]['href'] = get_link('a|f').'&amp;a=index&amp;f='.urlencode(substr($_GET['f'],0,strripos(rtrim($_GET['f'],'|'),'|')).'|');
	if (file_exists($_dir)) {
		$readmedata = nl2br(htmlspecialchars(file_get_contents($_dir)));
		showmsg("说明文件", '<a href="'.$links[0]['href'].'">返回</a><br/>'.$readmedata, $links);
	} else {
		showmsg("系统提醒", "说明文件不存在！",$links);
	}
}

//列表部分
$dir = KF_ROOT_PATH.'templates'.DIRECTORY_SEPARATOR.$_CFG['template_dir'];
$_GET['f'] = trim($_GET['f']);
if (empty($_GET['f'])) unset($_GET['f']);
$_f = explode('|',$_GET['f']);
if (substr($_GET['f'], 0, 10) == 'templates|' && (count($_f)>3 || (count($_f)>2 && (substr($_GET['f'],-4,4)=='.tpl' || substr($_GET['f'],-4,4)=='.css' || substr($_GET['f'],-3,3)=='.js')))){
	$_dir = KF_ROOT_PATH.str_replace('|',DIRECTORY_SEPARATOR,$_GET['f']);
	if (is_dir($_dir)) {
		$dir = $_dir;
	}elseif (substr(strtolower($_dir), -4, 4)=='.tpl' || substr(strtolower($_dir), -4, 4)=='.css' || substr(strtolower($_dir), -3, 3)=='.js'){
		//打开模板
		$links[0]['title'] = '返回上一级';
		$links[0]['href'] = get_link('a|f').'&amp;a=index&amp;f='.urlencode(substr($_GET['f'],0,strripos(rtrim($_GET['f'],'|'),'|')).'|');
		if (!is_writable($_dir)) {
			showmsg("系统提醒", "文件不可写或者不存在！",$links);
		}
		if (file_exists($_dir)) {
			$data = htmlspecialchars(file_get_contents($_dir));
		} else {
			showmsg("系统提醒", "文件不存在！",$links);
		}
		$smarty->assign('filedata', $data);
		$smarty->assign('gotoupurl', $links[0]['href']);
		//获取备份
		$_bak = KF_ROOT_PATH.'templates'.DIRECTORY_SEPARATOR.'templet_backup'.DIRECTORY_SEPARATOR;
		$_bak.= substr(str_replace('|', '-', $_GET['f']), 0, -4).DIRECTORY_SEPARATOR;
		$_baklist = glob($_bak.'*');
		$_bakarr = $_bakarrlist = array();
		foreach ($_baklist as $v){
			$filename = basename($v);
			$filearr = explode('-', $filename);
			$_bakarrlist[date('Y-m-d H:i:s',$filearr[0])] = $filename;
			$_bakarr[$filename]['title'] = $filename;
			$_bakarr[$filename]['time'] = date('Y-m-d H:i:s',$filearr[0]);
			$_bakarr[$filename]['v'] = $v;
		}
		$_bakarrlist = array_reverse($_bakarrlist);
		$smarty->assign('bakarr', $_bakarr);
		$smarty->assign('bakarrlist', $_bakarrlist);
	}
}
//路径
if ($_GET['a'] == 'index') {
	$_df = str_replace(array(KF_ROOT_PATH, '/', '\\'), '|', $dir);
	$folderpath = $folder_1 = "";
	$folderurl = get_link('a|f');
	foreach(explode('|', $_df) as $v){
		$folder_1.= ($v)?$v.'|':'';
		$folderpath.= ($v)?'/<a href="'.$folderurl.'&amp;a=index&amp;f='.urlencode($folder_1).'">'.$v.'</a>':''; 
	}
	$smarty->assign('folderpath', $folderpath);
}elseif ($_GET['a'] == 'bianji') {
	$folderpath = $folder_1 = "";
	$folderurl = get_link('a|f');
	foreach($_f as $v){
		$folder_1.= ($v)?$v.'|':'';
		if (is_dir(KF_ROOT_PATH.str_replace('|',DIRECTORY_SEPARATOR,$folder_1))){
			$folderpath.= ($v)?'/<a href="'.$folderurl.'&amp;a=index&amp;f='.urlencode($folder_1).'">'.$v.'</a>':''; 
		}else{
			$folderpath.= ($v)?'/'.$v:''; 
		}
	}
	$smarty->assign('folderpath', $folderpath);
}
//模板文件名称 start
$mubanarr = getcache(KF_ROOT_PATH.DIR_PATH.'config.php');
$conname = str_replace(array(KF_ROOT_PATH, "/", "\\"), array("", "-", "-"), $dir);
$conname = trim($conname, "-");
if ($_POST['dosubmit']){
	$_arr = array();
	foreach ($_POST as $_k => $_v) {
		if (substr($_k, 0, 4) == "con_"){
			$_arr[substr($_k, 4)] = $_v;
		}else{
            $_arr[$_k] = $_v;
		}
	}
	$mubanarr[$conname] = $_arr;
	write_static_cache(KF_ROOT_PATH.DIR_PATH.'config.php' ,$mubanarr);
}
$conarr = $mubanarr[$conname];

//模板文件名称 end

if (substr($dir,-1)!=DIRECTORY_SEPARATOR && substr($dir,-1)=="/"){
	$list = glob(rtrim($dir,'/').'\*');
}else{
	$list = glob($dir.'*');
}
if (file_exists($dir.'readme.txt')) {
	$smarty->assign('readmedata', str_replace(array('/', '\\'), '|', str_replace(KF_ROOT_PATH, '', $dir.'readme.txt')));
}
$dir = $file= array();
if (is_dir($_dir)) {
	$dir['uponeleveldir']['title'] = '上一层目录';
	$dir['uponeleveldir']['url'] = '<img src="'.IMG_PATH.'folder.gif"/>上一层目录';
	$dir['uponeleveldir']['input'] = '';
	$dir['uponeleveldir']['f'] = substr($_GET['f'],0,strripos(rtrim($_GET['f'],'|'),'|')).'|';
}
foreach ($list as $v){
	$filename = basename($v);
	if (is_dir($v)) {
		$dir[$filename]['title'] = $filename;
		$dir[$filename]['url'] = '<img src="'.IMG_PATH.'folder.gif"/>'.$filename.'';
		$dir[$filename]['input'] = '<input type="text" name="'.inputname($filename).fenmiao().'" size="10" value="'.$conarr[inputname($filename)].'"/>';
		$dir[$filename]['f'] = str_replace(KF_ROOT_PATH,'',$v).DIRECTORY_SEPARATOR;
		$dir[$filename]['f'] = str_replace(array('/', '\\'), '|', $dir[$filename]['f']);
		$dir[$filename]['a'] = 'index';
		$dir[$filename]['vsname'] = '';
	} elseif (substr(strtolower($v), -4, 4)=='.tpl' || substr(strtolower($v), -4, 4)=='.css' || substr(strtolower($v), -3, 3)=='.js'){
		$file[$filename]['title'] = $filename;
		$file[$filename]['url'] = '<img src="'.IMG_PATH.'file.gif"/>'.$filename.'';
		$file[$filename]['input'] = '<input type="text" name="'.inputname($filename).fenmiao().'" size="10" value="'.$conarr[inputname($filename)].'"/>';
		$file[$filename]['f'] = str_replace(KF_ROOT_PATH,'',$v);
		$file[$filename]['f'] = str_replace(array('/', '\\'), '|', $file[$filename]['f']);
		$file[$filename]['a'] = 'bianji';
		$file[$filename]['vsname'] = '';
	} else {
		continue;
	}
}
$listarr = array_merge($dir, $file);
$smarty->assign('listarr', $listarr);


function getsecond() {
	list($usec, $sec) = explode(" ", microtime());
	$msec=round($usec*1000);
	return substr('0000'.$msec, -4, 4);
}
function inputname($filename){
	return str_replace('.', '_', $filename);
}
?>