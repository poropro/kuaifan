<?php
/*
 * 查看会员
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

require(KF_INC_PATH.'denglu.php');
//会员组
$grouplist = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_zu.php');
//会员所属模型
$modellistarr = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_moxing.php');
//加载会员资料
if ($_GET['username']){
	$row = $db->getone("select * from ".table('huiyuan')." WHERE username='".$_GET['username']."' LIMIT 1");
}else{
	$row = $db->getone("select * from ".table('huiyuan')." WHERE userid='".intval($_GET['userid'])."' LIMIT 1");
}
if (empty($row)){
	if ($_GET['go_url']){
		$links[-1]['title'] = '返回上一页';
		$links[-1]['href'] = goto_url($_GET['go_url']);
	}
	$links[0]['title'] = '返回会员中心';
	$links[0]['href'] = get_link('c|sid|go_url')."&amp;c=index&amp;sid=".$_GET['sid'];
	$links[1]['title'] = '返回网站首页';
	$links[1]['href'] = kf_url('index');
	showmsg("系统提醒", "会员不存在！", $links);
}
$row['nickname'] = $row['nickname']?$row['nickname']:$row['username'];
$row['field'] = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'model'.DIRECTORY_SEPARATOR.'model_field_'.$row['modelid'].'.huiyuan.cache.php');
$row['detail'] = $db -> getone("select * from ".table('huiyuan_diy_'.$modellistarr[$row['modelid']]['tablename'])." WHERE userid = {$row['userid']} LIMIT 1");
if (SYS_TIME - $row['indate'] < $_CFG['lonline']*60){
	$row['indate_cn'] = "在线";
}else{
	$row['indate_cn'] = "离线";
}
$smarty->assign('huiyuan', $row);
$smarty->assign('grouplist', $grouplist[$row['groupid']]);
$smarty->assign('modellistarr', $modellistarr[$row['modelid']]);
//加载会员动态
$rowtongji = $db -> getall("select * from ".table('tongji')." WHERE userid={$row['userid']} ORDER BY `time` DESC LIMIT 0, 5");
$smarty->assign('tongji', $rowtongji);

?>