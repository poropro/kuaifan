<?php
$_GET  = addslashes_deep($_GET);
$_POST  = addslashes_deep($_POST);
$_COOKIE   = addslashes_deep($_COOKIE);
$_REQUEST  = addslashes_deep($_REQUEST);
/**
 * Enter description here ...
 */

$php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
$sys_protocal.= isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
$sys_protocal.= $_SERVER['REQUEST_URI']?$_SERVER['REQUEST_URI']:$php_self;
$sys_protocal = substr($sys_protocal, 0, strrpos($sys_protocal,'/')).'/';

if (!empty($_POST)){
	$post_string = '';
	foreach ($_POST as $_k => $_v){
		$post_string.= "{$_k}={$_v}&";
	}
	$post_string = rtrim($post_string, '&');
	echo request_by_other($sys_protocal.'index.php?m=huiyuan&c=sms&api=1', $post_string);
}else{
	echo file_get_contents($sys_protocal.'index.php?m=huiyuan&c=sms&api=1');
}



/**
 * POST 提交数据
 * 使用方法：
 * $post_string = "app=request&version=beta";
 * request_by_other('http://kuaifan.net/restServer.php',$post_string);
 */
function request_by_other($remote_server, $post_string)
{
	$context = array(
		'http' => array(
			'method' => 'POST',
			'header' => 'Content-type: application/x-www-form-urlencoded' .
						'\r\n'.'User-Agent : Jimmy\'s POST Example beta' .
						'\r\n'.'Content-length:' . strlen($post_string) + 8,
			'content' => $post_string)
	);
	$stream_context = stream_context_create($context);
	$data = file_get_contents($remote_server, false, $stream_context);
	return $data;
}

function addslashes_deep($value){
	if (empty($value)){
		return $value;
	}else{
		if (!get_magic_quotes_gpc()){
			$value=is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
		}
		$value=is_array($value) ? array_map('addslashes_deep', $value) : mystrip_tags($value);
		return $value;
	}
}
function mystrip_tags($string){
	$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);
	$string = strip_tags($string);
	return $string;
}
?>