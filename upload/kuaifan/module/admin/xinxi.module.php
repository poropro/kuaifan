<?php
/*
 * 信息系统
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');
kf_class::run_sys_func('xinxi');

//单发信息
if ($_GET['a'] == 'danfa'){
	if ($_POST['dosubmit']){
		$links[0]['title'] = '返回继续发送';
		$links[0]['href'] = get_link();
		if (!$_POST['username']) showmsg("系统提醒", "请输入“用户名”、“用户ID”！", $links);
		if (!$_POST['subject']) showmsg("系统提醒", "请输入“标题”！", $links);
		if (!$_POST['content']) showmsg("系统提醒", "请输入“内容”！", $links);
		if (strlen($_POST['subject']) < 2 || strlen($_POST['subject']) > 80) showmsg("系统提醒", "“标题”长度范围在2到80个字符！", $links);
		if (strlen($_POST['content']) < 2 || strlen($_POST['content']) > 500) showmsg("系统提醒", "“内容”长度范围在2到500个字符！", $links);
		
		$usertype = $_POST['usertype']?$_POST['usertype']:'username';
		$row = $db->getone("select * from ".table('huiyuan')." WHERE {$usertype} = '{$_POST['username']}' LIMIT 1");
		if (empty($row)) showmsg("系统提醒", "你要发送的会员不存在！", $links);
		$subject = new_html_special_chars($_POST['subject']);
		$content = new_html_special_chars($_POST['content']);
		add_message($row['username'],0,$subject,$content);
		$links[1]['title'] = '返回信息列表';
		$links[1]['href'] = get_link('a').'&amp;a=index';
		admin_log("给“{$row['username']}”发送信息：{$subject}。", $admin_val['name']);
		showmsg("系统提醒", "信息发送成功！", $links);
	}
}

//群发信息
if ($_GET['a'] == 'qunfa'){
	//会员组
	$grouplist = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_zu.php');
	$grouplistarr = array();
	foreach ($grouplist as $_k => $_v) {
		if ($_k != 8){
			$grouplistarr[$_v['name']] = $_k;
		}
	}
	$smarty->assign('grouplist', $grouplistarr);
	if ($_POST['dosubmit']){
		$links[0]['title'] = '返回继续发送';
		$links[0]['href'] = get_link();
		if (!$_POST['subject']) showmsg("系统提醒", "请输入“标题”！", $links);
		if (!$_POST['content']) showmsg("系统提醒", "请输入“内容”！", $links);
		if (strlen($_POST['subject']) < 2 || strlen($_POST['subject']) > 80) showmsg("系统提醒", "“标题”长度范围在2到80个字符！", $links);
		if (strlen($_POST['content']) < 2 || strlen($_POST['content']) > 500) showmsg("系统提醒", "“内容”长度范围在2到500个字符！", $links);
		if (empty($grouplist[$_POST['groupid']])) showmsg("系统提醒", "你要发送的会员组不存在！", $links);
		$subject = new_html_special_chars($_POST['subject']);
		$content = new_html_special_chars($_POST['content']);
		$message = array ();
		$message['typeid'] = 1;
		$message['groupid'] = $_POST['groupid'];
		$message['groupid_cn'] = $grouplist[$_POST['groupid']]['name'];
		$message['subject'] = $subject;
		$message['content'] = $content;
		$message['inputtime'] = SYS_TIME;
		$message['status'] = 1;
		if (!inserttable(table('xinxi_xitong'), $message)){
			showmsg("系统提醒", "发送失败，请稍后再试！");
		}else{
			$links[1]['title'] = '返回信息列表';
			$links[1]['href'] = get_link('a').'&amp;a=xitong';
			admin_log("群发信息给“{$grouplist[$_POST['groupid']]['name']}”的会员：{$subject}。", $admin_val['name']);
			showmsg("系统提醒", "群发信息成功！", $links);
		}
		
	}
}
//系统群发信息列表
if ($_GET['a'] == 'xitong'){
	if ($_REQUEST['key']){
		$_GET['key'] = $_REQUEST['key'];
		$wheresql = " `subject` like '%{$_GET['key']}%'";
	}else{
		$wheresql = "";
	}
	$smarty->assign('wheresql', $wheresql);
}
//信息详情
if ($_GET['a'] == 'xiangqing'){
	$row = $db->getone("select * from ".table('xinxi')." WHERE `messageid` = '{$_GET['messageid']}' LIMIT 1");
	if (empty($row)) showmsg("系统提醒", "你要查看的信息不存在！");
	$smarty->assign('X', $row);
}
//系统信息详情
if ($_GET['a'] == 'xtxiangqing'){
	$row = $db->getone("select * from ".table('xinxi_xitong')." WHERE `id` = '{$_GET['id']}' LIMIT 1");
	if (empty($row)) showmsg("系统提醒", "你要查看的系统信息不存在！");
	$smarty->assign('X', $row);
}
//删除至回收站
if ($_GET['a'] == 'delh'){
	$whereval = "messageid = '{$_GET['messageid']}'";
	$db -> query("update ".table('xinxi')." set `folder`='outbox' WHERE {$whereval}");
	admin_log("将信息删除至回收站, messageid:{$_GET['messageid']}", $admin_val['name']);
	showmsg("系统提醒", "操作成功！");
}
//拉出回收站
if ($_GET['a'] == 'dell'){
	$whereval = "messageid = '{$_GET['messageid']}'";
	$db -> query("update ".table('xinxi')." set `folder`='inbox' WHERE {$whereval}");
	admin_log("将信息拉出回收站, messageid:{$_GET['messageid']}", $admin_val['name']);
	showmsg("系统提醒", "操作成功！");
}
//删除信息
if ($_GET['del']){
	if ($admin_val['id']!='1') showmsg("系统提醒", "只有网站创始人才可以操作！");
	if ($_GET['del'] == 'huishou'){
		if ($_GET['dosubmit']){
			$db->query("Delete from ".table('xinxi')." WHERE folder='outbox'");
			$links[0]['title'] = '返回列表页面';
			$links[0]['href'] = get_link("del|dosubmit");
			$links[1]['title'] = '返回后台首页';
			$links[1]['href'] = $_admin_indexurl;
			admin_log("清空回收站信息！", $admin_val['name']);
			showmsg("系统提醒", "清空回收站信息成功！", $links);
		}
		$links[0]['title'] = '确定清空';
		$links[0]['href'] = get_link()."&amp;dosubmit=1";
		$links[1]['title'] = '返回列表页面';
		$links[1]['href'] = get_link("del");
		showmsg("系统提醒", "确定清空回收站信息并且不可恢复吗？", $links);
	}elseif ($_GET['del'] == 'all'){
		if ($_GET['dosubmit']){
			$db->query("Delete from ".table('xinxi'));
			$links[0]['title'] = '返回列表页面';
			$links[0]['href'] = get_link("del|dosubmit");
			$links[1]['title'] = '返回后台首页';
			$links[1]['href'] = $_admin_indexurl;
			admin_log("删除全部信息！", $admin_val['name']);
			showmsg("系统提醒", "删除全部信息成功！", $links);
		}
		$links[0]['title'] = '确定删除';
		$links[0]['href'] = get_link()."&amp;dosubmit=1";
		$links[1]['title'] = '返回列表页面';
		$links[1]['href'] = get_link("del");
		showmsg("系统提醒", "确定删除所有信息并且不可恢复吗？", $links);
	}else{
		$db->query("Delete from ".table('xinxi')." WHERE messageid in ({$_GET['del']})");
		$links[0]['title'] = '返回列表页面';
		$links[0]['href'] = get_link("del|dosubmit");
		$links[1]['title'] = '返回后台首页';
		$links[1]['href'] = $_admin_indexurl;
		admin_log("删除信息 messageid：{$_GET['del']}！", $admin_val['name']);
		showmsg("系统提醒", "删除信息成功！", $links);
	}
}
//删除系统信息
if ($_GET['xtdel']){
	if ($admin_val['id']!='1') showmsg("系统提醒", "只有网站创始人才可以操作！");
	if ($_GET['xtdel'] == 'all'){
		if ($_GET['dosubmit']){
			$db->query("Delete from ".table('xinxi_xitong'));
			$db->query("Delete from ".table('xinxi_data'));
			$links[0]['title'] = '返回列表页面';
			$links[0]['href'] = get_link("xtdel|dosubmit");
			$links[1]['title'] = '返回后台首页';
			$links[1]['href'] = $_admin_indexurl;
			admin_log("删除全部系统信息！", $admin_val['name']);
			showmsg("系统提醒", "删除全部系统信息成功！", $links);
		}
		$links[0]['title'] = '确定删除';
		$links[0]['href'] = get_link()."&amp;dosubmit=1";
		$links[1]['title'] = '返回列表页面';
		$links[1]['href'] = get_link("xtdel");
		showmsg("系统提醒", "确定删除所有系统信息并且不可恢复吗？", $links);
	}else{
		$db->query("Delete from ".table('xinxi_xitong')." WHERE id in ({$_GET['xtdel']})");
		$db->query("Delete from ".table('xinxi_data')." WHERE group_message_id in ({$_GET['xtdel']})");
		$links[0]['title'] = '返回列表页面';
		$links[0]['href'] = get_link("xtdel|dosubmit");
		$links[1]['title'] = '返回后台首页';
		$links[1]['href'] = $_admin_indexurl;
		admin_log("删除系统信息 id：{$_GET['xtdel']}！", $admin_val['name']);
		showmsg("系统提醒", "删除系统信息成功！", $links);
	}
}
?>