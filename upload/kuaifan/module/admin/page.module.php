<?php
/*
 * 页面伪静态
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');



$KFpage = 'KF_index,KF_paibanpage,KF_neironglist,KF_neirongshow,KF_neirongreply';
$KFpagearr = array(
	'KF_index'=>'网站首页',
	'KF_paibanpage'=>'新建页面',
	'KF_neironglist'=>'内容列表',
	'KF_neirongshow'=>'内容详情',
	'KF_neirongreply'=>'评论列表',
);

			
//

if (!empty($_POST['dosubmit'])){
	$_pagesub = array();
	foreach($KFpagearr as $k=>$v){
		$_pagesub[$k]['file'] = 'index.php';
		$_pagesub[$k]['rewrite'] = $_POST[$k.'_rewrite'];
		$_pagesub[$k]['url-1'] = $_POST[$k.'_url-1'];
		$_pagesub[$k]['url-2'] = $_POST[$k.'_url-2'];
		$_pagesub[$k]['url-3'] = $_POST[$k.'_url-3'];
		$_pagesub[$k]['url-4'] = $_POST[$k.'_url-4'];
		$_pagesub[$k]['url-5'] = $_POST[$k.'_url-5'];
		$_pagesub[$k]['alias'] = $k;
		$_pagesub[$k]['body'] = $v;
	}
	//
	$db -> query("update ".table('peizhi_mokuai')." set `setting`='".array2string($_pagesub)."' WHERE `module`='page'");
	refresh_peizhi_mokuai();
	$cache_file_path =KF_ROOT_PATH. "caches/cache_page.php";
	write_static_cache($cache_file_path, $_pagesub);
	admin_log("修改了页面伪静态。", $admin_val['name']);
	$links[0]['title'] = '重新修改';
	$links[0]['href'] = get_link();
	$links[1]['title'] = '返回后台首页';
	$links[1]['href'] = $_admin_indexurl;
	showmsg("系统提醒", "提交成功！", $links);
}

foreach($KFpagearr as $k=>$v){
	$smarty->assign($k, $_PAGE[$k]);
}
?>