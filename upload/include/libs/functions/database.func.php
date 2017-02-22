<?php
 /**
 * 数据库备份恢复
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

kf_class::run_sys_func('dir');
/**
 * 数据库导出方法
 * @param $tables 数据表数据组
 * @param $sqlcompat 数据库兼容类型
 * @param $sqlcharset 数据库字符
 * @param $sizelimit 卷大小
 * @param $action 操作
 * @param $fileid 卷标
 * @param $random 随机字段
 * @param $tableid 
 * @param $startfrom 
 * @param $tabletype 备份数据库类型 （非kuaifan数据与kuaifan数据）
 */
function export_database($tables,$sqlcompat,$sqlcharset,$sizelimit,$action,$fileid,$random,$tableid,$startfrom,$tabletype) {
	global $db;
	$dumpcharset = $sqlcharset ? $sqlcharset : str_replace('-', '', CHARSET);

	$fileid = ($fileid != '') ? $fileid : 1;		
	if(!isset($tables) || !is_array($tables)) showmsg("系统提醒", "请选择数据表");
	if($fileid==1) $random = mt_rand(1000, 9999);
	if($db->dbversion() > '4.1'){
		if($sqlcharset) {
			$db->query("SET NAMES '".$sqlcharset."';\r\n\r\n");
		}
		if($sqlcompat == 'MYSQL40') {
			$db->query("SET SQL_MODE='MYSQL40'");
		} elseif($sqlcompat == 'MYSQL41') {
			$db->query("SET SQL_MODE=''");
		}
	}
	
	$tabledump = '';

	$tableid = ($tableid!= '') ? $tableid - 1 : 0;
	$startfrom = ($startfrom != '') ? intval($startfrom) : 0;
	for($i = $tableid; $i < count($tables) && strlen($tabledump) < $sizelimit * 1000; $i++) {
		global $startrow;
		$offset = 100;
		if(!$startfrom) {
			if($tables[$i]!=DB_PRE.'session') {
				$tabledump .= "DROP TABLE IF EXISTS `$tables[$i]`;\r\n";
			}
			$createtable = $db->query("SHOW CREATE TABLE `$tables[$i]` ");
			$create = $db->getone("SHOW CREATE TABLE ".$tables[$i]);
			$tabledump .= $create['Create Table'].";\r\n\r\n";
			$db->free_result($createtable);
						
			if($sqlcompat == 'MYSQL41' && $db->dbversion() < '4.1') {
				$tabledump = preg_replace("/TYPE\=([a-zA-Z0-9]+)/", "ENGINE=\\1 DEFAULT CHARSET=".$dumpcharset, $tabledump);
			}
			if($db->dbversion() > '4.1' && $sqlcharset) {
				$tabledump = preg_replace("/(DEFAULT)*\s*CHARSET=[a-zA-Z0-9]+/", "DEFAULT CHARSET=".$sqlcharset, $tabledump);
			}
			if($tables[$i]==DB_PRE.'session') {
				$tabledump = str_replace("CREATE TABLE `".DB_PRE."session`", "CREATE TABLE IF NOT EXISTS `".DB_PRE."session`", $tabledump);
			}
		}

		$numrows = $offset;
		while(strlen($tabledump) < $sizelimit * 1000 && $numrows == $offset) {
			if($tables[$i]==DB_PRE.'session' || $tables[$i]==DB_PRE.'member_cache') break;
			$sql = "SELECT * FROM `$tables[$i]` LIMIT $startfrom, $offset";
			$rows = $db->query($sql);
			$numfields = $db->num_fields($rows);
			$numrows = $db->num_rows($rows);
			$r = array();
			while ($row = $db->fetch_array($rows, MYSQL_NUM)) {
				$r[] = $row;
				$comma = "";
				$tabledump .= "INSERT INTO `$tables[$i]` VALUES(";
				for($j = 0; $j < $numfields; $j++) {
					$tabledump .= $comma."'".mysql_escape_string($row[$j])."'";
					$comma = ",";
				}
				$tabledump .= ");\r\n";
			}
			$db->free_result($rows);
			$startfrom += $offset;
			
		}
		$tabledump .= "\r\n";
		$startrow = $startfrom;
		$startfrom = 0;
		//替换allow身份标识
		global $pre;
		if ($tables[$i] == $pre.'guanliyuan' && $_GET['allow']) {
			$tabledump = str_replace("'{$_GET['allow']}'", "'{kf_temp::guanliyuan-allow}'", $tabledump);
		}
	}
	$_timeurl = get_link("dosubmit|sizelimit|sqlcompat|sqlcharset|tableid|fileid|startfrom|random|tabletype|pdo_select");
	if(trim($tabledump)) {
		$tabledump = "# kuaifan bakfile\r\n# version:KUAIFAN V4\r\n# time:".date('Y-m-d H:i:s')."\r\n# type:kuaifan\r\n# kuaifan:http://www.kuaifan.net\r\n# --------------------------------------------------------\r\n\r\n\r\n".$tabledump;
		$tableid = $i;
		$filename = $tabletype.'_'.date('Ymd').'_'.$random.'_'.$fileid.'.sql';
		$altid = $fileid;
		$fileid++;
		$bakfile_path = KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'bakup'.DIRECTORY_SEPARATOR.'default';
		if(!dir_create($bakfile_path)) {
			showmsg("系统提醒", "目录无法创建");
		}
		$bakfile = $bakfile_path.DIRECTORY_SEPARATOR.$filename;
		if(!is_writable(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'bakup')) showmsg("系统提醒", "目录无法创建");
		file_put_contents($bakfile, $tabledump);
		@chmod($bakfile, 0777);
		if(!EXECUTION_SQL) $filename = '分卷：'.$altid.'#';
		$links[0]['title'] = '备份中心';
		$links[0]['href'] = $_timeurl;
		$links[1]['title'] = '返回后台首页';
		$links[1]['href'] = get_link("m|allow|vs","",1);
		$_timeurl.= '&amp;dosubmit=1&amp;sizelimit='.$sizelimit.'&amp;sqlcompat='.$sqlcompat.'&amp;sqlcharset='.$sqlcharset.'&amp;tableid='.$tableid.'&amp;fileid='.$fileid.'&amp;startfrom='.$startrow.'&amp;random='.$random.'&amp;tabletype='.$tabletype.'&amp;pdo_select=default';
		showmsg("系统提醒", "备份文件 {$filename} 写入成功",$links,$_timeurl,1);
	} else {
	   $bakfile_path = KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'bakup'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR;
	   file_put_contents($bakfile_path.'index.html','');
	   $links[0]['title'] = '备份中心';
	   $links[0]['href'] = $_timeurl;
	   $links[1]['title'] = '返回后台首页';
	   $links[1]['href'] = get_link("m|allow|vs","",1);
	   showmsg("系统提醒", "备份成功！",$links);
	}
}

/**
 * 数据库恢复
 * @param $filename
 */
function import_database($filename, $fileid = '1') {
	if($filename && fileext($filename)=='sql') {
		$filepath = KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'bakup'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.$filename;
		if(!file_exists($filepath)) showmsg("系统提醒", "对不起{$filepath} 数据库文件不存在");;
		$sql = file_get_contents($filepath);
		sql_execute($sql);
		showmsg("系统提醒", "{$filename} 中的数据已经成功导入到数据库！");
	} else {
		$fileid = $fileid ? $fileid : 1;
		$_pre = $filename;
		$filename = $filename.$fileid.'.sql';
		$filepath = KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'bakup'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.$filename;
		$_timeurl = get_link("dosubmit|confirm|pdoname|pre|fileid");
		if(file_exists($filepath)) {
			$sql = file_get_contents($filepath);
			$sql = str_replace("'{kf_temp::guanliyuan-allow}'", "'{$_GET['allow']}'", $sql);
			sql_execute($sql);
			$fileid++;
			$links[0]['title'] = '还原中心';
			$links[0]['href'] = $_timeurl;
			$links[1]['title'] = '返回后台首页';
			$links[1]['href'] = get_link("m|allow|vs","",1);
			$_timeurl.= '&amp;dosubmit=1&amp;confirm=1&amp;pre='.$_pre.'&amp;fileid='.$fileid.'&amp;pdoname=default';
			showmsg("系统提醒", "数据文件 {$filename} 恢复成功！",$links,$_timeurl,1);
		} else {
			$links[0]['title'] = '还原中心';
			$links[0]['href'] = $_timeurl;
			$links[1]['title'] = '返回后台首页';
			$links[1]['href'] = get_link("m|allow|vs","",1);
			showmsg("系统提醒", "数据库恢复成功，建议您<a href='".get_link("m|allow|vs","",1)."&amp;c=huancun'>更新一下缓存</a>！",$links);
		}
	}
}

function get_optimize_list($only = false)
{
	global $db;
	$row_arr = array();
    if ($only) {
        $result = $db->query("SHOW TABLE STATUS");
    }else{
        $result = $db->query("SHOW TABLE STATUS FROM `{$db->db_name}` WHERE Data_free>0");
    }
	while($row = $db->fetch_array($result))
	{
		if ($row['Data_free']=="0")
		{
			$row['Data_free']="-";
		}
		if ($row['Data_free']>1 && $row['Data_free']<1024)
		{
			$row['Data_free']=$row['Data_free']." byte";
		}
		elseif($row['Data_free']>1024 && $row['Data_free']<1048576)
		{
			$row['Data_free']=number_format(($row['Data_free']/1024),1)." KB";
		}
		elseif($row['Data_free']>1048576)
		{
			$row['Data_free']=number_format(($row['Data_free']/1024/1024),1)." MB";
		}
		$row['Data_length']=$row['Data_length']+$row['Index_length'];
		//--
		if ($row['Data_length']=="0")
		{
			$row['Data_length']="-";
		}
		elseif($row['Data_length']<1048576)
		{
			$row['Data_length']=number_format(($row['Data_length']/1024),1)." KB";
		}
		elseif($row['Data_length']>1048576)
		{
			$row['Data_length']=number_format(($row['Data_length']/1024/1024),1)." MB";
		}
		$row_arr[] = $row;
	}
	return $row_arr;
}
function escape_str($str){
	$str=mysql_escape_string($str);
	$str=str_replace('\\\'','\'\'',$str);
	$str=str_replace("\\\\","\\\\\\\\",$str);
	$str=str_replace('$','\$',$str);
	return $str;
}
?>