<?php
/*
 * 友链管理
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

if ($_GET['del']){
	$row = $db->getone("select * from ".table('lianjie')." WHERE id='{$_GET['del']}' LIMIT 1");
	if (empty($row)) showmsg("系统提醒", "要删除的友情链接不存在！");
	if ($row['userid'] != US_USERID){
		showmsg("系统提醒", "参数错误，非法操作！");
	}
	if ($_GET['dosubmit']){
		$_wheresql = " WHERE catid='{$row['catid']}' AND id='{$row['id']}'";
		$db->query("Delete from ".table('lianjie')." {$_wheresql}");
		$db->query("Delete from ".table('lianjie_data')." {$_wheresql}");
		$links[0]['title'] = '返回我的友链';
		$links[0]['href'] = get_link("del|dosubmit");
		showmsg("系统提醒", "删除成功！", $links);
		
	}else{
		$links[0]['title'] = '确定删除';
		$links[0]['href'] = get_link('dosubmit').'&amp;dosubmit=1';
		$links[1]['title'] = '管理我的友链';
		$links[1]['href'] = get_link("del");
		showmsg("系统提醒", "确定删除此链接并且不能恢复吗？！", $links);
	}
}
?>