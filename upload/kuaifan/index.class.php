<?php
 /*
 * 首页排版模块
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
kf_class::run_sys_func('index');

$_SEO['title'] = $_CFG['index_title'];
$_SEO['keywords'] = $_CFG['index_keywords'];
$_SEO['description'] = $_CFG['index_description'];
//新页面
if ($_GET['id']){
	$wheresql = " WHERE type_en = 'page' AND id = ".intval($_GET['id']);
	$val = $db -> getone("select * from ".table('paiban').$wheresql);
	if ($val){
		$val['body2'] = string2array($val['body']);
		$_SEO['title'] = $val['body2']['title'];
		$_SEO['keywords'] = $val['body2']['keywords'];
		$_SEO['description'] = $val['body2']['description'];
	}
}
$smarty->assign('pageid', intval($_GET['id']));
?>