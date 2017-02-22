<?php
@set_time_limit(1000);
header("Content-Type:text/html;charset=utf-8");
if(phpversion() < '5.3.0') set_magic_quotes_runtime(0);
if(phpversion() < '5.2.0') exit('您的php版本过低，不能安装本软件，请升级到5.2.0或更高版本再安装，谢谢！');
define('IN_KUAIFAN', true);
define('INSTALL', true);
$_GET['vs'] = 2;
require_once(dirname(dirname(__FILE__)).'/include/base.php');
if(file_exists(KF_ROOT_PATH.'caches/install.lock')) exit('您已经安装过KuaiFanCMS,如果需要重新安装，请删除 ./caches/install.lock 文件！');

$step = trim($_REQUEST['step']) ? trim($_REQUEST['step']) : 1;
if(strrpos(strtolower(PHP_OS),"win") === FALSE) {
	define('ISUNIX', TRUE);
} else {
	define('ISUNIX', FALSE);
}
$steps = include KF_ROOT_PATH.'install/step.inc.php';

$mode = 0777;

switch($step)
{
    case '1': //安装许可协议
		$license = file_get_contents(KF_ROOT_PATH."install/license.txt");
		include KF_ROOT_PATH."install/step/step".$step.".tpl.php";

		break;
	
	case '2':  //环境检测 (FTP帐号设置）
        $PHP_GD  = '';
		if(extension_loaded('gd')) {
			if(function_exists('imagepng')) $PHP_GD .= 'png';
			if(function_exists('imagejpeg')) $PHP_GD .= ' jpg';
			if(function_exists('imagegif')) $PHP_GD .= ' gif';
		}
		$PHP_JSON = '0';
		if(extension_loaded('json')) {
			if(function_exists('json_decode') && function_exists('json_encode')) $PHP_JSON = '1';
		}
		//新加fsockopen 函数判断,此函数影响安装后会员注册及登录操作。
		if(function_exists('fsockopen')) {
			$PHP_FSOCKOPEN = '1';
		}
        $PHP_DNS = preg_match("/^[0-9.]{7,15}$/", @gethostbyname('www.baidu.com')) ? 1 : 0;
		//是否满足KuaiFanCMS安装需求
		$is_right = (phpversion() >= '5.2.0' && extension_loaded('mysql') && $PHP_JSON && $PHP_GD && $PHP_FSOCKOPEN) ? 1 : 0;		
		include KF_ROOT_PATH."install/step/step".$step.".tpl.php";
		break;
	case '3': //检测目录属性
    make_dir('../templates/templet_cache/_cache/');
		$chmod_file = 'chmod.txt';
		$selectmod = $needmod.$selectmod;
		$selectmods = explode(',',$selectmod);
		$files = file(KF_ROOT_PATH."install/".$chmod_file);		
		foreach($files as $_k => $file) {
			$file = str_replace('*','',$file);
			$file = trim($file);
			if(is_dir(KF_ROOT_PATH.$file)) {
				$is_dir = '1';
				$cname = '目录';
				//继续检查子目录权限，新加函数
				$write_able = writable_check(KF_ROOT_PATH.$file);
			} else {
				$is_dir = '0';
				$cname = '文件';
			}
			//新的判断
			if($is_dir =='0' && is_writable(KF_ROOT_PATH.$file)) {
				$is_writable = 1;
			} elseif($is_dir =='1' && dir_writeable(KF_ROOT_PATH.$file)){
				$is_writable = $write_able;
				if($is_writable=='0'){
					$no_writablefile = 1;
				}
			}else{
				$is_writable = 0;
 				$no_writablefile = 1;
  			}
							
			$filesmod[$_k]['file'] = $file;
			$filesmod[$_k]['is_dir'] = $is_dir;
			$filesmod[$_k]['cname'] = $cname;			
			$filesmod[$_k]['is_writable'] = $is_writable;
		}
		if(dir_writeable(KF_ROOT_PATH)) {
			$is_writable = 1;
		} else {
			$is_writable = 0;
		}
		$filesmod[$_k+1]['file'] = '网站根目录';
		$filesmod[$_k+1]['is_dir'] = '1';
		$filesmod[$_k+1]['cname'] = '目录';			
		$filesmod[$_k+1]['is_writable'] = $is_writable;						
		include KF_ROOT_PATH."install/step/step".$step.".tpl.php";
		break;
	case '4': //配置帐号 （MYSQL帐号、管理员帐号、）
		include KF_ROOT_PATH."install/step/step".$step.".tpl.php";
		break;
	case '5': //安装详细过程
		extract($_POST);
		include KF_ROOT_PATH."install/step/step".$step.".tpl.php";
		break;
	case '6'://完成安装
		file_put_contents(KF_ROOT_PATH.'caches/install.lock','');
		//copy(KF_ROOT_PATH."install/main/index.htm",KF_ROOT_PATH."index.htm");
		$installurl = "http://download.kuaifan.net/install-url/".base64_encode(get_url());
		include KF_ROOT_PATH."install/step/step".$step.".tpl.php";
		//删除安装目录
		__removeDir(KF_ROOT_PATH.'install/');
		break;
	
	case 'dbtest'://数据库测试
		extract($_GET);
		if(!@mysql_connect($dbhost, $dbuser, $dbpass)) {
			exit('2');
		}
		$server_info = mysql_get_server_info();
		if($server_info < '4.0') exit('6');
		if(!mysql_select_db($dbname)) {
			if(!@mysql_query("CREATE DATABASE `$dbname`")) exit('3');
			mysql_select_db($dbname);
		}
		$tables = array();
		$query = mysql_query("SHOW TABLES FROM `$dbname`");
		while($r = mysql_fetch_row($query)) {
			$tables[] = $r[0];
		}
		if($tables && in_array($pre.'guanliyuan', $tables)) {
			exit('0');
		}
		else {
			exit('1');
		}
		break;
	case 'installmodule': //执行SQL
		extract($_POST);
		$GLOBALS['dbcharset'] = $dbcharset;
		$PHP_SELF = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : (isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : $_SERVER['ORIG_PATH_INFO']);
		$rootpath = str_replace('\\','/',dirname($PHP_SELF));	
		$rootpath = substr($rootpath,0,-7);
		$rootpath = strlen($rootpath)>1 ? $rootpath : "/";	
		if($module == 'admin') {
			if ($pre != 'kf_' && (strpos($pre, "kf_") !== false)){
				echo "表前缀不能使用“{$pre}”。（禁止使用包含“kf_”但又不是“kf_”的字符，例如: 把“{$pre}”改成“".str_replace('kf_', 'ab_', $pre)."”试试）";
				exit;
			}
			if(!preg_match("/^[a-zA-Z]{1}[a-zA-Z0-9_]{2,9}+$/",$pre)){
				echo "表前缀不能使用“{$pre}”。（必须是字母加数字或下划线组成的3到10位字符串）";
				exit;
			}
			$auth_key = random(16, '1294567890abcdefghigklmnopqrstuvwxyzABCDEFGHIGKLMNOPQRSTUVWXYZ');
			$db_config = '<?php
				$dbhost   = "'.$dbhost.'";

				$dbname   = "'.$dbname.'";

				$dbuser   = "'.$dbuser.'";

				$dbpass   = "'.$dbpass.'";

				$dbcharset = "'.str_replace("-","",$dbcharset).'";

				$pre    = "'.$pre.'";

				$KF_cookiedomain = "";

				$KF_cookiepath =  "/";

				$KF_pwdhash = "'.$auth_key.'";

				define("CHARSET","'.$dbcharset.'");
			
			?>';
			$cache_file_path =KF_ROOT_PATH. "caches/config.php";
			write_static_cache_install($cache_file_path, $db_config, 1);

			$lnk = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Not connected : ' . mysql_error());
			$version = mysql_get_server_info();

			if($version > '4.1' && $dbcharset) {
				mysql_query("SET NAMES '".str_replace("-","",$dbcharset)."'");
			}
			
			if($version > '5.0') {
				mysql_query("SET sql_mode=''");
			}

			if(!@mysql_select_db($dbname)){
				@mysql_query("CREATE DATABASE $dbname");
				if(@mysql_error()) {
					echo 1;exit;
				} else {
					mysql_select_db($dbname);
				}
			}
			$_SESSION['insqlnum'] = 0;
			$_SESSION['insqlarr'] = array();
			$dbfile =  'kuaifan_db.sql';	
			if(file_exists(KF_ROOT_PATH."install/main/".$dbfile)) {
				$sql = file_get_contents(KF_ROOT_PATH."install/main/".$dbfile);
				_sql_execute($sql);
				//创建网站创始人
				if($dbcharset=='gbk') $username = iconv('UTF-8','GBK',$username);
				$password_md5 = md5s($password);
				$email = trim($email);				
				_sql_execute("INSERT INTO `".$pre."guanliyuan` (`rank`,`name`,`pass`,`purview`,`email`,`site`) VALUES('超级管理员','{$username}','{$password_md5}','all','{$email}','1')");
				//设置默认站点1域名
				_sql_execute("INSERT INTO `".$pre."zhandian` (`name`,`site`) VALUES ('默认网站', '1');");
				//更新域名 和 根目录
				$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
				$sys_protocal.= (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
				$_PHP_SELF = strtolower($_SERVER['PHP_SELF']);
				$_PHP_SELF = substr($PHP_SELF, 0, strrpos($PHP_SELF,'install/install.php'));
				_sql_execute("UPDATE `".$pre."peizhi` SET value='".$sys_protocal."' WHERE name='site_domain';");
				_sql_execute("UPDATE `".$pre."peizhi` SET value='".$_PHP_SELF."' WHERE name='site_dir';");
			} else {
				echo '2';//数据库文件不存在
				exit;
			}
		} elseif ($module == 'sqlnum') {
			$insqlval = "";
			if (count($_SESSION['insqlarr']) > 0){
				foreach ($_SESSION['insqlarr'] as $_val) {
					$_val = str_replace("'","",$_val);
					if ($_val) $insqlval.= "{$_val};\\r\\n";
				}
			}
			if (!empty($insqlval)) {
				echo "程序安装失败，执行SQL失败：<a href=\"#sqlerra\" onClick=\"document.getElementById('sqlerrdiv').style.display='block';document.getElementById('sqlerrtext').innerHTML='{$insqlval}';\" style=\"color:#FF0000;text-decoration:underline;\">".count($_SESSION['insqlarr'])."行(点击查看详情)</a>";
			}else{
				echo "执行SQL：".$_SESSION['insqlnum']."行";
			}
			exit;
		} elseif ($module == 'neirong') {
			//内容模块
		} elseif ($module == 'expand') {
			//扩展功能
		} elseif ($module == 'huiyuan') {
			//会员模块
		}
		//........
		echo $module;
		break;	
	case 'testdata'://安装测试数据	
		$lnk = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Not connected : ' . mysql_error());
		$version = mysql_get_server_info();			
		if($version > '4.1' && $dbcharset) {
			mysql_query("SET NAMES '".str_replace("-","",$dbcharset)."'");
		}			
		if($version > '5.0') {
			mysql_query("SET sql_mode=''");
		}			
		mysql_select_db($dbname);
		if(file_exists(KF_ROOT_PATH."install/main/testsql.sql"))
		{
			$sql = file_get_contents(KF_ROOT_PATH."install/main/testsql.sql");
			_sql_execute($sql);
			//解压测试数据包
			if(file_exists(KF_ROOT_PATH."install/main/test.zip")){
        kf_class::run_sys_class('zip','',0);
        $archive  = new PHPZip();
        $zipfile   = "main/test.zip";
        $savepath  = "../";
        $array     = $archive->GetZipInnerFilesInfo($zipfile);
        $filecount = 0;
        $dircount  = 0;
        $failfiles = array();
        set_time_limit(300);
        for($i=0; $i<count($array); $i++) {
            if($array[$i][folder] == 0){
                if($archive->unZip($zipfile, $savepath, $i) > 0){
                    $filecount++;
                }else{
                    $failfiles[] = $array[$i][filename];
                }
            }else{
                $dircount++;
            }
        }
        __fujian_del(KF_ROOT_PATH.'install/main/test.zip');
      }
		}
		break;	
	case 'deltestdata'://删除测试数据	
    if (file_exists(KF_ROOT_PATH."install/main/test.zip")){
      __fujian_del(KF_ROOT_PATH.'install/main/test.zip');
    }
		break;	
	case 'dbtest'://数据库测试
		extract($_GET);
		if(!@mysql_connect($dbhost, $dbuser, $dbpass)) {
			exit('2');
		}
		$server_info = mysql_get_server_info();
		if($server_info < '4.0') exit('6');
		if(!mysql_select_db($dbname)) {
			if(!@mysql_query("CREATE DATABASE `$dbname`")) exit('3');
			mysql_select_db($dbname);
		}
		$tables = array();
		$query = mysql_query("SHOW TABLES FROM `$dbname`");
		while($r = mysql_fetch_row($query)) {
			$tables[] = $r[0];
		}
		if($tables && in_array($pre.'module', $tables)) {
			exit('0');
		}
		else {
			exit('1');
		}
		break;
	case 'cache_all'://更新缓存		
		kf_class::run_sys_class('mysql','',0);
		$db = new mysql($dbhost,$dbuser,$dbpass,$dbname);
		unset($dbhost,$dbuser,$dbpass,$dbname);
		kf_class::run_sys_func('admin');
		//网站配置
		refresh_cache('peizhi');
		//模型
		refresh_cache_all('neirong_moxing');
		//模型字段
		$arr = $db->getall("SELECT * FROM ".table('neirong_moxing')." ORDER BY id asc");
		foreach($arr as $_value) {cache_field($_value['id']);}
		//栏目
		refresh_cache_column(1);
		//排版
		refresh_cache_paiban();
		//会员组
		refresh_cache_all('huiyuan_zu','','groupid');
		//会员模型
		refresh_cache_all('huiyuan_moxing');
		//会员模型字段
		$arr = $db->getall("SELECT * FROM ".table('huiyuan_moxing')." ORDER BY id asc");
		foreach($arr as $_value) {cache_field_huiyuan($_value['id']);}
		//会员分组
		refresh_cache_all('huiyuan_zu','','groupid');
		//模块前台地址
		refresh_cache_mokuaiurl();
		//模块配置
		refresh_peizhi_mokuai();
		//删除模板缓存
		__removeDir(KF_ROOT_PATH.'templates/templet_cache/_cache/');
		__removeDir(KF_ROOT_PATH.'templates/templet_cache/');
		break;
}


//写入文本
function write_static_cache_install($cache_file_path, $config_arr ,$arr = '')
{
	if ($arr){
		$content = $config_arr;
	}else{
		$content = "<?php\r\n";
		$content .= "\$data = " . var_export($config_arr, true) . ";\r\n";
		$content .= "?>";
	}
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


function format_textarea($string) {
	return nl2br(str_replace(' ', '&nbsp;', htmlspecialchars($string)));
}

function dir_writeable($dir) {
	$writeable = 0;
	if(is_dir($dir)) {  
        if($fp = @fopen("$dir/chkdir.test", 'w')) {
            @fclose($fp);      
            @unlink("$dir/chkdir.test"); 
            $writeable = 1;
        } else {
            $writeable = 0; 
        } 
	}
	return $writeable;
}

function writable_check($path){
	$dir = '';
	$is_writable = '1';
	if(!is_dir($path)){return '0';}
	$dir = opendir($path);
 	while (($file = readdir($dir)) !== false){
		if($file!='.' && $file!='..'){
			if(is_file($path.'/'.$file)){
				//是文件判断是否可写，不可写直接返回0，不向下继续
				if(!is_writable($path.'/'.$file)){
 					return '0';
				}
			}else{
				//目录，循环此函数,先判断此目录是否可写，不可写直接返回0 ，可写再判断子目录是否可写 
				$dir_wrt = dir_writeable($path.'/'.$file);
				if($dir_wrt=='0'){
					return '0';
				}
   				$is_writable = writable_check($path.'/'.$file);
 			}
		}
 	}
	return $is_writable;
}

function _sql_execute($sql,$r_tablepre = '',$s_tablepre = 'kf_') {
    $sqls = _sql_split($sql,$r_tablepre,$s_tablepre);
	if(is_array($sqls))
    {
		foreach($sqls as $sql)
		{
			if(trim($sql) != '')
			{
				$con = mysql_query($sql);
				$_SESSION['insqlnum']++;
				if (!$con) $_SESSION['insqlarr'][] = $sql;
			}
		}
	}
	else
	{
		$con = mysql_query($sqls);
		$_SESSION['insqlnum']++;
		if (!$con) $_SESSION['insqlarr'][] = $sqls;
	}
	return true;
}
function _sql_split($sql,$r_tablepre = '',$s_tablepre='kf_') {
	global $dbcharset,$pre;
	$r_tablepre = $r_tablepre ? $r_tablepre : $pre;
	if(mysql_get_server_info > '4.1' && $dbcharset)
	{
		$sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=".str_replace("-","",$dbcharset),$sql);
	}
	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
  $sys_protocal.= (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
  $sql = str_replace("{:domain}", $sys_protocal, $sql);
	if($r_tablepre != $s_tablepre) $sql = str_replace($s_tablepre, $r_tablepre, $sql);
	$sql = str_replace("\r", "\n", $sql);
	$ret = array();
	$num = 0;
	$queriesarray = explode(";\n", trim($sql));
	unset($sql);
	foreach($queriesarray as $query)
	{
		$ret[$num] = '';
		$queries = explode("\n", trim($query));
		$queries = array_filter($queries);
		foreach($queries as $query)
		{
			$str1 = substr($query, 0, 1);
			if($str1 != '#' && $str1 != '-') $ret[$num] .= $query;
		}
		$num++;
	}
	return $ret;
}

function random($length, $chars = '0123456789') {
	$hash = '';
	$max = strlen($chars) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $chars[mt_rand(0, $max)];
	}
	return $hash;
}
//删除文件
function __fujian_del($str_file_path) {
	if (!unlink($str_file_path)){
		return false;
	}else{
		return true;
	}
}
//删除文件与目录
function __removeDir($dirName){
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
			is_dir($dir)?__removeDir($dir):@unlink($dir); //判断$dir是否为目录，如果是目录则递归调用__removeDir($dirName)函数，将其中的文件和目录都删除；如果不是目录，则删除该文件
		}
	}
	closedir($handle);
	return rmdir($dirName) ;
}
?>