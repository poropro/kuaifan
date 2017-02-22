<?php
 /*
 * 信息模块
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
if(!defined('IN_KUAIFAN')) exit('Access Denied!');


if ($_GET['c'] == 'list') {
	kf_class::run_sys_func('neirong');
	$up_dir_0 = "uploadfiles/content/em/";
	$row = $db->getall("SELECT * FROM ".table('biaoqing')." WHERE `is`=0 ORDER BY listorder DESC,id ASC");
	$_arr = array();
	foreach($row as $_v) {
		$_arr[$_v['em']] = get_em($_v['em'], 1);
	}
	echo json_encode($_arr);
	exit();
}
?>