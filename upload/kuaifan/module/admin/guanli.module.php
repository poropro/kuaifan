<?php
/*
 * 管理帐号
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');


$wheresql=" WHERE 1=1";
$sql = "select * from ".table('guanliyuan')." {$wheresql} ORDER BY id asc";
$result = $db->query($sql);
$__list = array();
while($row = $db->fetch_array($result)){
	$__list[] = $row;
}

$__glid['title'] = '0';
if ($_GET['id']){
	$wheresql=" where id=".intval($_GET['id'])."";
	$__glid = $db -> getone("select * from ".table('guanliyuan').$wheresql." LIMIT 1");
	if ($__glid) {
		$__glid['title'] = '1';
	}
}
if ($__glid['title'] == '1'){
	if ($_GET['del']=='1'){
		if ($__glid['id']=='1') showmsg("系统提醒", "系统原有帐号不可删除！");
		if ($__glid['name']==$admin_val['name']) showmsg("系统提醒", "不能删除自己的帐号！");
		$links[0]['title'] = '确定删除';
		$links[0]['href'] = get_link('del').'&amp;del=2';
		$links[1]['title'] = '返回修改';
		$links[1]['href'] = get_link('del');
		$links[2]['title'] = '返回后台首页';
		$links[2]['href'] = $_admin_indexurl;
		showmsg("系统提醒", "你确定删除管理员“{$__glid['name']}”的帐号信息并且不可恢复吗？", $links);
	}
	if ($_GET['del']=='2'){
		if ($__glid['id']=='1') showmsg("系统提醒", "系统原有帐号不可删除！");
		if ($__glid['name']==$admin_val['name']) showmsg("系统提醒", "不能删除自己的帐号！");
		$links[0]['title'] = '返回列表';
		$links[0]['href'] = get_link('del|id');
		$links[1]['title'] = '返回后台首页';
		$links[1]['href'] = $_admin_indexurl;
		if (!$db->query("Delete from ".table('guanliyuan').$wheresql)){
			showmsg("系统提醒", "删除失败,请稍后再试！", $links);
		}else{
			admin_log("修改管理员{$__glid['name']}。", $admin_val['name']);
			showmsg("系统提醒", "删除成功！", $links);
		}
	}
}

if (!empty($_POST['dosubmit'])){
	$links[0]['title'] = '返回后台首页';
	$links[0]['href'] = $_admin_indexurl;
	if (!$_POST['name']) showmsg("系统提醒", "帐号不能为空！");
	if (!$_POST['rank']) showmsg("系统提醒", "头衔不能为空！");
	if ($__glid['title'] == '1'){
		if ($admin_val['id']!='1' && $admin_val['id']!=$__glid['id']) showmsg("系统提醒", "权限不足,你只能修改自己的帐号！");
		$links[1]['title'] = '返回列表';
		$links[1]['href'] = get_link('id');
		$links[2]['title'] = '继续修改';
		$links[2]['href'] = get_link();
		$links = array_reverse($links);
		$setsql = "name='{$_POST['name']}', rank='{$_POST['rank']}'";
		if ($_POST['pass']) {$setsql.= ",pass='".md5s($_POST['pass'])."'";}
		if (!$db -> query("update ".table('guanliyuan')." set ".$setsql." WHERE id='{$__glid['id']}'")){
			showmsg("系统提醒", "修改失败,请稍后再试！", $links);
		}else{
			admin_log("修改管理员{$_POST['name']}。", $admin_val['name']);
			showmsg("系统提醒", "修改成功！", $links);
		}
	}else{
		if ($admin_val['id']!='1') showmsg("系统提醒", "权限不足！");
		if (!$_POST['pass']) showmsg("系统提醒", "密码不能为空！");
		$ihave = $db -> getone("select * from ".table('guanliyuan')." where `name` = '{$_POST['name']}' LIMIT 1");
		if ($ihave) showmsg("系统提醒", "添加失败,帐号“{$_POST['name']}”已存在！");
		$links[1]['title'] = '继续添加';
		$links[1]['href'] = get_link();
		$links = array_reverse($links);
		$setsql = array();
		$setsql['name'] = $_POST['name'];
		$setsql['pass'] = $_POST['pass'];
		$setsql['rank'] = $_POST['rank'];
		if (!inserttable(table('guanliyuan'),$setsql)){
			showmsg("系统提醒", "添加失败,请稍后再试！", $links);
		}else{
			admin_log("添加管理员{$_POST['name']}。", $admin_val['name']);
			showmsg("系统提醒", "添加成功！", $links);
		}
	}
}
$smarty->assign('guanliid',$__glid);
$smarty->assign('guanliyuan', $__list);

?>