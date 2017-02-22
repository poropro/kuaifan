<?php
 /*
 * cms 管理中心共用函数
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
 if(!defined('IN_KUAIFAN'))
 {
 	die('Access Denied!');
 }
//生成栏目缓存
function refresh_cache_column($str)
{
	global $db;
	$siteid = intval($str);
	if ($siteid < 1) $siteid = 1; 
	$config_arr = array();
	$cache_file_path =KF_ROOT_PATH. "caches/column/cache.".$siteid.".php";
	$sql = "select * from ".table('neirong_lanmu')." WHERE site = {$siteid} ORDER BY listorder asc,id asc";
	$arr = $db->getall($sql);
	foreach ($arr as $r) {
		$config_arr[$r['id']] = $r;
	}
	write_static_cache($cache_file_path,$config_arr);
}
//生成排版缓存
function refresh_cache_paiban($_where = 'del=0')
{
	global $db;
	$config_arr = array();
	
	$sql = "SELECT * FROM ".table('paiban');
	if ($_where) $sql = "SELECT * FROM ".table('paiban')." where ".$_where;
	$arr = $db->getall($sql);
	foreach ($arr as $r) {
		$config_arr[$r['site']][$r['id']] = $r;
	}
	foreach ($config_arr as $k => $r) {
		$cache_file_path =KF_ROOT_PATH. "caches/caches_paiban/cache.".$k.".php";
		write_static_cache($cache_file_path,$r);
	}
	if (empty($config_arr)){
		$cache_file_path =KF_ROOT_PATH. "caches/caches_paiban/cache.1.php";
		write_static_cache($cache_file_path, array());
	}
}
//生成配置模块缓存
function refresh_peizhi_mokuai($_module = '')
{
	global $db;
	$sql = "SELECT * FROM ".table('peizhi_mokuai');
	if (!empty($_module)) $sql = "SELECT * FROM ".table('peizhi_mokuai')." WHERE module='".$_module."'";
	$arr = $db->getall($sql);
	foreach ($arr as $r) {
		$cache_file_path =KF_ROOT_PATH. "caches/caches_peizhi_mokuai/cache.".$r['module'].".php";
		write_static_cache($cache_file_path, string2array($r['setting']));
	}
}
//生成字段缓存
function cache_field($modelid = 0) {
	global $db;
	$field_array = array();
	$sql = "SELECT * FROM ".table('neirong_moxing_ziduan')." WHERE modelid = ".intval($modelid)." AND status != 1 ORDER BY listorder asc,id asc";
	$arr = $db->getall($sql);
	foreach($arr as $_value) {
		$setting = string2array($_value['setting']);
		$_value = array_merge($_value,$setting);
		$field_array[$_value['field']] = $_value;
	}
	$cache_file_path =KF_ROOT_PATH. "caches/model/model_field_".$modelid.".cache.php";
	write_static_cache($cache_file_path,$field_array);
	refresh_cache_all('neirong_moxing');
	return true;
}
//生成字段缓存(会员)
function cache_field_huiyuan($modelid = 0) {
	global $db;
	$field_array = array();
	$sql = "SELECT * FROM ".table('huiyuan_moxing_ziduan')." WHERE modelid = ".intval($modelid)." AND status != 1 ORDER BY listorder asc,id asc";
	$arr = $db->getall($sql);
	foreach($arr as $_value) {
		$setting = string2array($_value['setting']);
		$_value = array_merge($_value,$setting);
		$field_array[$_value['field']] = $_value;
	}
	$cache_file_path =KF_ROOT_PATH. "caches/model/model_field_".$modelid.".huiyuan.cache.php";
	write_static_cache($cache_file_path,$field_array);
	refresh_cache_all('neirong_moxing');
	return true;
}
//删除字段缓存
function cache_field_del($modelid = 0) {
	$cache_file_path =KF_ROOT_PATH. "caches/model/model_field_".$modelid.".cache.php";
	if (!unlink($cache_file_path)){
		return false;
	}else{
		return true;
	}
}
//删除字段缓存(会员)
function cache_field_huiyuan_del($modelid = 0) {
	$cache_file_path =KF_ROOT_PATH. "caches/model/model_field_".$modelid.".huiyuan.cache.php";
	if (!unlink($cache_file_path)){
		return false;
	}else{
		return true;
	}
}
//生成数据表缓存(全部)
function refresh_cache_all($cachename, $_where = '', $field_array = 'id')
{
	global $db;
	$config_arr = array();
	$cache_file_path =KF_ROOT_PATH. "caches/cache_".$cachename.".php";
	$sql = "SELECT * FROM ".table($cachename);
	if ($_where) $sql = "SELECT * FROM ".table($cachename)." where ".$_where;
	$arr = $db->getall($sql);
	foreach ($arr as $r) {
		$config_arr[$r[$field_array]] = $r;
	}
	write_static_cache($cache_file_path,$config_arr);
}
//生成模块前台地址缓存
function refresh_cache_mokuaiurl()
{
	global $db;
	$config_arr = array();
	$cache_file_path =KF_ROOT_PATH. "caches/cache_mokuai_url.php";
	$sql = "SELECT * FROM ".table('peizhi_mokuai_url')." ORDER BY module asc,urlid asc";
	$arr = $db->getall($sql);
	foreach ($arr as $r) {
		$config_arr[$r['module'].'-'.$r['urlid']] = $r;
	}
	write_static_cache($cache_file_path,$config_arr);
}
//生成配置缓存
function refresh_cache($cachename, $_where = '')
{
	global $db;
	$config_arr = array();
	$cache_file_path =KF_ROOT_PATH. "caches/cache_".$cachename.".php";
	$sql = "SELECT * FROM ".table($cachename);
	if ($_where) $sql = "SELECT * FROM ".table($cachename)." where ".$_where;
	$arr = $db->getall($sql);
		foreach($arr as $key=> $val)
		{
		$config_arr[$val['name']] = $val['value'];
		}
	write_static_cache($cache_file_path,$config_arr);
}
//生成内嵌广告缓存
function refresh_neirong_guanggao()
{
	global $db;
	$arrnr = $arrpl = "";
	$arr = $db->getall("SELECT * FROM ".table("neirong_guanggao")." ORDER BY `order` DESC,`id` DESC");
	foreach($arr as $key=> $val){
		$arrtemp = "	\$ubbbl = __ubb_neirong_guanggao_turn('{$val['title']}','{$val['url']}',\$ubbbl,{$val['islogin']},{$val['wap']},'{$val['stype']}');\r\n";
		if ($val['type'] == '内容'){
			$arrnr.= $arrtemp;
		}elseif ($val['type'] == '评论'){
			$arrpl.= $arrtemp;
		}else{
			$arrnr.= $arrtemp; $arrpl.= $arrtemp;
		}
	}
	$cache_file_path = KF_ROOT_PATH. "caches/cache_neirong_guanggao.php";
	$content = "<?php\r\n";
	//内容
	$content .= "function __ubb_neirong_guanggao(\$ubbbl){ \r\n";
	$content .= $arrnr;
	$content .= "	return \$ubbbl;\r\n";
	$content .= "}\r\n";
	//评论
	$content .= "\r\n";
	$content .= "function __ubb_pinglun_guanggao(\$ubbbl){ \r\n";
	$content .= $arrpl;
	$content .= "	return \$ubbbl;\r\n";
	$content .= "}\r\n";
	//函数
	$content .= "function __ubb_neirong_guanggao_turn(\$title, \$strtxt, \$text, \$islogin = 0, \$wap = 0, \$stype = ''){"
				. "\r\n if (\$islogin == 1){"
				. "\r\n 	//仅登录"
				. "\r\n 	if (US_USERID <= 0) return \$text;"
				. "\r\n }elseif (\$islogin == 2){"
				. "\r\n 	//仅游客"
				. "\r\n 	if (US_USERID > 0) return \$text;"
				. "\r\n }"
				. "\r\n if (\$wap > 0){"
				. "\r\n 	//限制版本"
				. "\r\n 	if (\$wap != \$_GET['vs']) return \$text;"
				. "\r\n }"
				. "\r\n if (\$stype == 'ubb'){"
				. "\r\n 	\$strtxt = ubb(\$strtxt);"
				. "\r\n }elseif (\$stype == 'wml'){"
				. "\r\n 	\$strtxt = wml(\$strtxt);"
				. "\r\n }elseif (\$stype == 'link'){"
				. "\r\n 	\$strtxt = str_replace(\"{sid}\",\$_GET['sid'],\$strtxt);"
				. "\r\n 	\$strtxt = str_replace(\"{vs}\",\$_GET['vs'],\$strtxt);"
				. "\r\n 	\$strtxt = \"<a href=\\\"{\$strtxt}\\\" target=\\\"_blank\\\">{\$title}</a>\";"
				. "\r\n }"
				. "\r\n \$ubbbl = str_replace(\$title, \$strtxt, \$text);"
				. "\r\n return \$ubbbl;"
				. "\r\n }"
				. "\r\n";
	$content .= "?>";
	make_dir(dirname($cache_file_path));
	if (!file_put_contents($cache_file_path, $content, LOCK_EX))
	{
		$fp = @fopen($cache_file_path, 'wb+');
		if (!$fp)
		{
			exit('生成缓存文件失败');
		}
		if (!@fwrite($fp, trim($content)))
		{
			exit('生成缓存文件失败');
		}
		@fclose($fp);
	}
}
//写入文本
function write_static_cache($cache_file_path, $config_arr)
{
	$content = "<?php\r\n";
	$content .= "\$data = " . var_export($config_arr, true) . ";\r\n";
	$content .= "?>";
	make_dir(dirname($cache_file_path));
	if (!file_put_contents($cache_file_path, $content, LOCK_EX))
	{
		$fp = @fopen($cache_file_path, 'wb+');
		if (!$fp)
		{
			exit('生成缓存文件失败');
		}
		if (!@fwrite($fp, trim($content)))
		{
			exit('生成缓存文件失败');
		}
		@fclose($fp);
	}
}
//写入管理员操作日志
function admin_log($str, $user, $log_type=0, $log_site=1)
{
 	global $db, $timestamp,$online_ip,$_CFG;
	if ($_CFG['admin_islog']=='1'){
		$sql = "INSERT INTO ".table('guanliyuan_rizhi')." (name, time, body, ip, type, site) VALUES ('$user', '$timestamp', '".htmlspecialchars($str,ENT_QUOTES)."','$online_ip','".intval($log_type)."', '".intval($log_site)."')"; 
		return $db->query($sql);
	}
}
//获取上级目录栏目
function nr_shangjilanmu($str, $afferent = ''){
 	global $db;
	$lanmuid = intval($str);
	if ($lanmuid == 0) return $afferent?"0,{$afferent}":"0";
	$__lanmudb = "WHERE id={$lanmuid}";
	$__lanmudb = $db -> getone("select * from ".table('neirong_lanmu')." {$__lanmudb} LIMIT 1");
	if (!empty($__lanmudb)){
		$_afferent = $afferent?"{$__lanmudb['id']},{$afferent}":"{$__lanmudb['id']}";
		return nr_shangjilanmu($__lanmudb['parentid'], $_afferent);
	}else{
		return $afferent?"{$__lanmudb['id']},{$afferent}":"{$__lanmudb['id']}";
	}
}
//获取(并更新)子级目录栏目
function nr_zijilanmu($str, $afferent = ''){
 	global $db;
	$lanmuid = intval($str);
	if ($lanmuid > 0) {
		$sql = "select * from ".table('neirong_lanmu')." WHERE parentid = {$lanmuid} ORDER BY id asc";
		$result = $db->query($sql);$_ii = 0;$_tt = '';
		while($_row = $db->fetch_array($result)){
			$_tt .= ($_ii>0)?",{$_row['id']}":"{$_row['id']}";
			$_ii++;
		}
		$db -> query("update ".table('neirong_lanmu')." set arrchildid='{$_tt}' WHERE id={$lanmuid}");
	}
}
//删除文件
function fujian_del($str_file_path) {
	if (!unlink($str_file_path)){
		return false;
	}else{
		return true;
	}
}
//删除文件与目录
function removeDir($dirName){
	if(!is_dir($dirName)){ //如果传入的参数不是目录，则为文件，应将其删除
		@unlink($dirName);//删除文件
		return false;
	}
	$handle = @opendir($dirName); //如果传入的参数是目录，则使用opendir将该目录打开，将返回的句柄赋值给$handle
	while(($file = @readdir($handle)) !== false) //这里明确地测试返回值是否全等于（值和类型都相同）FALSE，否则任何目录项的名称求值为 FALSE 的都会导致循环停止（例如一个目录名为“0”）。
	{
		if($file!='.'&&$file!='..') //在文件结构中，都会包含形如“.”和“..”的向上结构，但是它们不是文件或者文件夹
		{
			$dir = $dirName . '/' . $file; //当前文件$dir为文件目录+文件
			is_dir($dir)?removeDir($dir):@unlink($dir); //判断$dir是否为目录，如果是目录则递归调用reMoveDir($dirName)函数，将其中的文件和目录都删除；如果不是目录，则删除该文件
		}
	}
	closedir($handle);
	return rmdir($dirName) ;
}
//检测网络是否连接
function varify_url($url){  
	$check = @fopen($url,"r");  
	if($check){  
		 $status = true;  
	}else{  
		 $status = false;  
	}    
	return $status;  
}
?>