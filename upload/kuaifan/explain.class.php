<?php
 /*
 * 帮助模块
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/

if(!defined('IN_KUAIFAN')) exit('Access Denied!');
kf_class::run_sys_func('ubb');


if ($_GET['id']){
	$wheresql = " WHERE id = ".intval($_GET['id']);
	$val = $db -> getone("select * from ".table('bangzhu').$wheresql." LIMIT 1");
	if ($val){
		if (!$val['v']) $val['v'] = '所有版本';
		$val['body'] = nl2br(htmlspecialchars($val['body']));
    $val['body'] = preg_replace("/\{ubb set=(.+?)\}/e","ubb('\\1')",$val['body']); 
		$smarty -> assign('get_val', $val);
	}
}
?>