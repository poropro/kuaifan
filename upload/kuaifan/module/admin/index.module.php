<?php
/*
 * 后台首页
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');


$_admintop = '';
//订单处理
$t_count=$db->get_total("SELECT COUNT(*) AS num FROM ".table('dingdan')." WHERE status=1 AND touserid=0 AND toadmin=1");
if ($t_count > 0){
	$_admintop.= '<b>待发货订单</b>:<a href="'.$_admin_indexurl.'&amp;c=chongzhi&amp;a=dingdan&amp;status=1">'.$t_count.'</a><br/>';
}
$smarty->assign('admintop', $_admintop);
//获取模块列表
$mokuaiarr = $db->getall("SELECT * FROM ".table('peizhi_mokuai')." WHERE iscore=1 AND disabled=0 ORDER BY listorder ASC");
$mokuaival = '';$_n = 0;
foreach ($mokuaiarr as $_v){
	if ($_n > 0){
		$mokuaival.= ($_n % 2 == 0)?'<br/>':'|';
	}
	if ($_v['url']){
		$mokuaival.= '<a href="'.$_admin_indexurl.$_v['url'].'">'.$_v['name'].'</a>';
	}else{
		$mokuaival.= $_v['name'];
	}
	$_n++;
}
$smarty->assign('mokuaiarr', $mokuaiarr);
$smarty->assign('mokuaival', $mokuaival);

?>