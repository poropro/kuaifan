<?php
/*
 * ucenter配置
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');



if (!empty($_POST['dosubmit'])){
	if (empty($_POST['UC_DBCHARSET'])) $_POST['UC_DBCHARSET'] = 'utf8';
	$uccof = "";
	$uccof.= "define('UC_CONNECT', 'mysql');\r\n";
	foreach($_POST as $k => $v){
		if (substr($k, 0, 3) == 'UC_'){
			$uccof.= "define('{$k}', '{$v}');\r\n";
		}
	}
	$uccof.= "define('UC_DBCONNECT', '0');\r\n";
	$uccof.= "define('UC_CHARSET', '".CHARSET."');\r\n";
	$uccof.= "define('UC_PPP', '20');\r\n";
	write_ucenter_cache($uccof);
	admin_log("修改了ucenter配置。", $admin_val['name']);

	$links[0]['title'] = '继续配置';
	$links[0]['href'] = get_link();
	$links[1]['title'] = '返回后台首页';
	$links[1]['href'] = $_admin_indexurl;
	showmsg("系统提醒", "保存成功！", $links);
}

include KF_ROOT_PATH.'caches/config_uc.php';

//写入文本
function write_ucenter_cache($config_val)
{
	$cache_file_path = KF_ROOT_PATH.'caches/config_uc.php';
	$content = "<?php\r\n";
	$content .= $config_val;
	$content .= "?>";
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
?>