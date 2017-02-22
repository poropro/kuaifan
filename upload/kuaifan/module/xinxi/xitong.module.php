<?php
/*
 * 系统信息
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

if ($_GET['del'] == 'reply'){
	$_xt_arr = $db->getall("select * from ".table('xinxi_xitong')." WHERE status=1 AND id not in (SELECT group_message_id FROM ".table('xinxi_data')." WHERE userid={$huiyuan_val['userid']}) ORDER BY inputtime DESC");
	foreach($_xt_arr as $_val) {
		inserttable(table('xinxi_data'), array('userid'=>$huiyuan_val['userid'],'group_message_id'=>$_val['id'],'retime'=>SYS_TIME));
	}
	$links[0]['title'] = '信息列表';
	$links[0]['href'] = get_link("del");
	$links[1]['title'] = '返回会员中心';
	$links[1]['href'] = get_link("vs|sid",'','1').'&amp;m=huiyuan&amp;c=index';
	showmsg("系统提醒", "全部设为已读成功！", $links);
}
if ($_GET['id']){
	$links[0]['title'] = '信息列表';
	$links[0]['href'] = get_link("id");
	$links[1]['title'] = '返回会员中心';
	$links[1]['href'] = get_link("vs|sid",'','1').'&amp;m=huiyuan&amp;c=index';
	$xinxi = $db->getone("select * from ".table('xinxi_xitong')." WHERE status=1 AND id='{$_GET['id']}' LIMIT 1");
	if (empty($xinxi)) showmsg("系统提醒", "此系统信息不存在！", $links);
	if (!$db->get_total("SELECT COUNT(*) AS num FROM ".table('xinxi_data')." WHERE userid={$huiyuan_val['userid']} AND group_message_id=".$xinxi['id'])){
		inserttable(table('xinxi_data'), array('userid'=>$huiyuan_val['userid'],'group_message_id'=>$xinxi['id'],'retime'=>SYS_TIME));
	}
	$html = '
				查看系统的信息<br/>
				-------------<br/>
				标题: '.$xinxi['subject'].'<br/>
				时间: '.date("Y-m-d H:i:s",$xinxi['inputtime']).'<br/>
				内容: '.$xinxi['content'].'
					';
	showmsg("查看系统信息", $html, $links);
}
$smarty->assign('userid', $huiyuan_val['userid']);
$smarty->assign('groupid', $huiyuan_val['groupid']);

?>