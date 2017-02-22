<?php
/*
 * 回复心情
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

$xinqingarr = get_cache("xinqing");
$smarty->assign('xinqingarr', $xinqingarr);

if ($_POST['dosubmit']) {
	$data = array();
	for($a=1;$a<=10;$a++){ 
		$data[$a] = array('use'=>$_POST['xq_a_'.$a], 'name'=>$_POST['xq_b_'.$a], 'pic'=>$_POST['xq_c_'.$a]);
	}
	$cache_file_path =KF_ROOT_PATH. "caches/cache_xinqing.php";
	write_static_cache($cache_file_path,$data);
	admin_log("修改内容心情。", $admin_val['name']);
	showmsg("系统提示", "修改成功！");
}
?>