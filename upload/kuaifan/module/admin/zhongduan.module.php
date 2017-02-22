<?php
/*
 * 终端信息
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');


$system_info = array();
$system_info['version'] = KUAIFAN_VERSION;
$system_info['release'] = KUAIFAN_RELEASE;
$system_info['os'] = PHP_OS;
$system_info['web_server'] = $_SERVER['SERVER_SOFTWARE'];
$system_info['php_ver'] = PHP_VERSION;
$system_info['mysql_ver'] = $db->dbversion();
$system_info['max_filesize'] = ini_get('upload_max_filesize');
$smarty->assign('system_info', $system_info);

?>