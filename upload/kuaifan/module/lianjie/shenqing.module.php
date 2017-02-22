<?php
/*
 * 友链申请
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

$peizhi = getcache(KF_ROOT_PATH. "caches/caches_peizhi_mokuai/cache.lianjie.php");
if (empty($peizhi['denglu'])){
	require(KF_INC_PATH.'denglu.php');
}

if ($_POST['dosubmit']) {
	if (empty($_POST['title'])) showmsg("系统提示", "请输入网站名称");
	if (empty($_POST['titlej'])) showmsg("系统提示", "请输入网站简称");
	if (empty($_POST['url']) || $_POST['url'] == "http://") showmsg("系统提示", "请输入网站地址");
	if (get_strlen($_POST['title']) < 4 || get_strlen($_POST['title']) > 12)  showmsg("系统提示", "网站名称 最低4字,最多12字");
	if (get_strlen($_POST['titlej']) != 2)  showmsg("系统提示", "网站名称 最低4字,最多12字");
	$row = $db->getone("SELECT * FROM ".table('lianjie_fenlei')." WHERE `catid`='{$_POST['catid']}' ORDER BY listorder DESC");
	if (empty($row)) showmsg("系统提示", "请选择正确的分类");
	if (substr($_POST['url'], 0, 4) != "http") $_POST['url'] = "http://".$_POST['url'];
	$_type = ($row['type'] == 1)?"1":"0";
	$info = array(
		'title' => $_POST['title'],
		'titlej' => $_POST['titlej'],
		'url' => $_POST['url'],
		'userid' => US_USERID,
		'catid' => $row['catid'],
		'catid_cn' => $row['title'],
		'type' => $_type,
		'content' => $_POST['content'],
		'inputtime' => SYS_TIME,
		'read' => '0',
		'readip' => '',
		'readtime' => '0',
		'from' => '0',
		'fromip' => '',
		'fromtime' => '0',
		'fromnum' => ($row['type'] == 2)?$row['type_num']:'0',
		'zhichi' => '0',
		'buzhichi' => '0',
		'listorder' => '0',
	);
	$lid = inserttable(table('lianjie'), $info, true);
	if (empty($lid)){
		showmsg("系统提示", "申请失败，请稍后再试！");
	}else{
		$param = kf_class::run_sys_class('param');
		$param->set_cookie('lianjier_'.$lid, $lid, SYS_TIME+3600);
		//
		if (US_USERID > 0) {
			$msg = '<br/>请进入“管理我的友链”查看回链地址。';
		}else{
			$msg = '<br/>回链地址：<a href="'.get_link('m','&amp;','1').'&amp;id='.$lid.'">'.get_link('m','&amp;','1').'&amp;id='.$lid.'</a>';
		}
		$links[0]['title'] = '继续申请';
		$links[0]['href'] = get_link();
		if (US_USERID > 0) {
			$links[1]['title'] = '管理我的友链';
			$links[1]['href'] = get_link("c").'&amp;c=guanli';
		}
		$links[2]['title'] = '返回友链首页';
		$links[2]['href'] = get_link("c").'&amp;c=index';
		if ($row['type'] == '2'){
			showmsg("系统提示", "申请成功，当链入流量达到{$row['type_num']}时自动通过审核！".$msg, $links);
		}elseif ($row['type'] == '1'){
			showmsg("系统提示", "申请成功，已通过审核！".$msg, $links);
		}else{
			showmsg("系统提示", "申请成功，请等待管理员审核！".$msg, $links);
		}
	}
}

$row = $db->getall("SELECT * FROM ".table('lianjie_fenlei')." ORDER BY listorder DESC");
$fenleiarr = array();
foreach($row as $_v) {
	$fenleiarr[$_v['title']] = $_v['catid'];
}
$smarty->assign('fenleiarr', $fenleiarr);
?>