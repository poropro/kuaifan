<?php
/*
 * 简繁互换
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');


if ($_POST['dosubmit']){
	require(KF_INC_PATH.'libs/data/taiwan/t2s.data.php');
	$GLOBALS['s2t_table'] = array_flip($GLOBALS['t2s_table']);
	$utf8_st_class=new utf8_ts($tableDir);
	if ($_POST['type']){
		$_timere = $utf8_st_class-> utf8_t2s($_POST['text']);
	}else{
		$_timere = $utf8_st_class-> utf8_s2t($_POST['text']);
	}
	$smarty->assign('text', $_timere);
}

?>