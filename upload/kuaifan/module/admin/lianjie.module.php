<?php
/*
 * 友情链接
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

if ($_GET['a'] == 'add') {
	if ($_POST['dosubmit']) {
		if (empty($_POST['title'])) showmsg("系统提示", "请输入名称");
		if (empty($_POST['titlej'])) showmsg("系统提示", "请输入简称");
		$row = $db->getone("SELECT * FROM ".table('lianjie_fenlei')." WHERE `catid`='{$_POST['catid']}' ORDER BY listorder DESC");
		if (empty($row)) showmsg("系统提示", "请选择正确的分类");
		$info = array(
			'title' => $_POST['title'],
			'titlej' => $_POST['titlej'],
			'url' => $_POST['url'],
			'userid' => '0',
			'catid' => $row['catid'],
			'catid_cn' => $row['title'],
			'type' => intval($_POST['type']),
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
			'listorder' => intval($_POST['listorder']),
		);
		if (!inserttable(table('lianjie'), $info)){
			showmsg("系统提示", "添加失败，请稍后再试！");
		}else{
			$links[0]['title'] = '继续添加';
			$links[0]['href'] = get_link();
			$links[1]['title'] = '返回友链列表';
			$links[1]['href'] = get_link("a");
			admin_log("添加友情链接: {$info['title']}。", $admin_val['name']);
			showmsg("系统提示", "添加成功！", $links);
		}
	}
	$row = $db->getall("SELECT * FROM ".table('lianjie_fenlei')." ORDER BY listorder DESC");
	$fenleiarr = array();
	foreach($row as $_v) {
		$fenleiarr[$_v['title']] = $_v['catid'];
	}
	$smarty->assign('fenleiarr', $fenleiarr);
}elseif ($_GET['a'] == 'edit') { //修改
	$row = $db->getone("SELECT * FROM ".table('lianjie')." WHERE `id`='{$_GET['id']}'");
	if (empty($row)) showmsg("系统提示", "友情链接不存在！");
	$row['inputtime'] = $row['inputtime']?date('Y-m-d H:i:s', $row['inputtime']):'';
	$row['readtime'] = $row['readtime']?date('Y-m-d H:i:s', $row['readtime']):'';
	$row['fromtime'] = $row['fromtime']?date('Y-m-d H:i:s', $row['fromtime']):'';
	$smarty->assign('lianjie', $row);
	
	if ($_POST['dosubmit']) {
		if (empty($_POST['title'])) showmsg("系统提示", "请输入名称");
		if (empty($_POST['titlej'])) showmsg("系统提示", "请输入简称");
		$row = $db->getone("SELECT * FROM ".table('lianjie_fenlei')." WHERE `catid`='{$_POST['catid']}' ORDER BY listorder DESC");
		if (empty($row)) showmsg("系统提示", "请选择正确的分类");
		$info = array(
			'title' => $_POST['title'],
			'titlej' => $_POST['titlej'],
			'url' => $_POST['url'],
			'userid' => intval($_POST['userid']),
			'catid' => $row['catid'],
			'catid_cn' => $row['title'],
			'type' => intval($_POST['type']),
			'content' => $_POST['content'],
			'inputtime' => strtotime($_POST['inputtime']),
			'read' => intval($_POST['read']),
			'readip' => $_POST['readip'],
			'readtime' => strtotime($_POST['readtime']),
			'from' => intval($_POST['from']),
			'fromip' => $_POST['fromip'],
			'fromtime' => strtotime($_POST['fromtime']),
			'zhichi' => intval($_POST['zhichi']),
			'buzhichi' => intval($_POST['buzhichi']),
			'listorder' => intval($_POST['listorder']),
		);
		if (!updatetable(table('lianjie'), $info, "`id`='{$_GET['id']}'")){
			showmsg("系统提示", "修改失败，请稍后再试！");
		}else{
			$links[0]['title'] = '继续修改';
			$links[0]['href'] = get_link();
			$links[1]['title'] = '返回友链列表';
			$links[1]['href'] = get_link("a|id");
			admin_log("修改友情链接: {$info['title']}。", $admin_val['name']);
			showmsg("系统提示", "修改成功！", $links);
		}
	}	
	$row = $db->getall("SELECT * FROM ".table('lianjie_fenlei')." ORDER BY listorder DESC");
	$fenleiarr = array();
	foreach($row as $_v) {
		$fenleiarr[$_v['title']] = $_v['catid'];
	}
	$smarty->assign('fenleiarr', $fenleiarr);
		
}elseif ($_GET['a'] == 'del') { //删除友链
	$row = $db->getone("SELECT * FROM ".table('lianjie')." WHERE `id`='{$_GET['id']}'");
	if (empty($row)) showmsg("系统提示", "友情链接不存在！");
	if ($_GET['dosubmit']) {
		$_wheresql = " WHERE catid='{$row['catid']}' AND id='{$row['id']}'";
		$db->query("Delete from ".table('lianjie')." {$_wheresql}");
		$db->query("Delete from ".table('lianjie_data')." {$_wheresql}");
		$links[1]['title'] = '返回友链列表';
		$links[1]['href'] = get_link("a|id|dosubmit");
		admin_log("删除友情链接: {$row['title']}。", $admin_val['name']);
		showmsg("系统提示", "删除成功！", $links);
	}
	$links[0]['title'] = '返回继续';
	$links[0]['href'] = get_link("a").'&amp;a=edit';
	$links[1]['title'] = '返回分类列表';
	$links[1]['href'] = get_link("a|id");
	showmsg("系统提示", "你是否确定删除此友情链接吗？<br/><a href='".get_link()."&amp;dosubmit=1'>确定删除</a>", $links);
}elseif ($_GET['a'] == 'jilu') { //访问/来访纪录
	if ($_GET['del']){
		if ($admin_val['id']!='1') showmsg("系统提醒", "只有网站创始人才可以操作！");
		if ($_GET['del'] == 'all'){
			if ($_GET['dosubmit']){
				$db->query("Delete from ".table('lianjie_data')." WHERE id='{$_GET['id']}'");
				$links[0]['title'] = '返回列表';
				$links[0]['href'] = get_link("del|dosubmit");
				$links[1]['title'] = '返回链接页面';
				$links[1]['href'] = get_link("del|dosubmit|a").'&amp;a=edit';
				admin_log("删除友链ID{$_GET['id']}的访问/来访纪录！", $admin_val['name']);
				showmsg("系统提醒", "删除全部纪录成功！", $links);
			}
			$links[0]['title'] = '确定删除';
			$links[0]['href'] = get_link()."&amp;dosubmit=1";
			$links[1]['title'] = '返回列表页面';
			$links[1]['href'] = get_link("del");
			showmsg("系统提醒", "确定删除所有纪录并且不可恢复吗？", $links);
		}else{
			$db->query("Delete from ".table('lianjie_data')." WHERE id='{$_GET['id']}' AND dataid in ({$_GET['del']})");
			$links[0]['title'] = '返回列表';
			$links[0]['href'] = get_link("del|dosubmit");
			$links[1]['title'] = '返回链接页面';
			$links[1]['href'] = get_link("del|dosubmit|a").'&amp;a=edit';
			admin_log("删除友链ID{$_GET['id']}的访问/来访纪录, dataid：{$_GET['del']}！", $admin_val['name']);
			showmsg("系统提醒", "删除纪录成功！", $links);
		}
	}
}elseif ($_GET['a'] == 'peizhi') { //配置
	$peizhi = getcache(KF_ROOT_PATH. "caches/caches_peizhi_mokuai/cache.lianjie.php");
	//
	if ($_POST['dosubmit']) {
		$peizhi['denglu'] = $_POST['denglu'];
		$peizhi['shuachu'] = $_POST['shuachu'];
		$peizhi['shuajin'] = $_POST['shuajin'];
		$db -> query("update ".table('peizhi_mokuai')." set `setting`='".array2string($peizhi)."' WHERE `module` = 'lianjie'");
		admin_log("修改了友链配置。", $admin_val['name']);
		refresh_peizhi_mokuai();
		$links[0]['title'] = '继续修改';
		$links[0]['href'] = get_link();
		$links[1]['title'] = '返回链接列表';
		$links[1]['href'] = get_link("a").'&amp;a=index';
		showmsg("系统提醒", "保存配置成功！", $links);
	}
	$smarty->assign('peizhi', $peizhi);
}elseif ($_GET['a'] == 'fenlei') { //分类列表
	
}elseif ($_GET['a'] == 'fladd') { //添加分类 、 修改分类
	if ($_GET['catid']){
		$row = $db->getone("SELECT * FROM ".table('lianjie_fenlei')." WHERE `catid`='{$_GET['catid']}'");
		if (empty($row)) showmsg("系统提示", "分类不存在！");
	}else{
		$row = array(
			'listorder'=>'0',
			'type'=>'0',
			'type_num'=>'3',
		);
	}
	if ($row['catid']){
		$smarty->assign('TITLE', "修改分类");
	}else{
		$smarty->assign('TITLE', "添加分类");
	}
	$smarty->assign('fenlei', $row);
	//
	if ($_POST['dosubmit']) {
		if (empty($_POST['title'])) showmsg("系统提示", "请输入名称");
		$info = array(
			'title' => $_POST['title'],
			'listorder' => intval($_POST['listorder']),
			'type' => intval($_POST['type']),
			'type_num' => intval($_POST['type_num']),
			'islink' => intval($_POST['islink']),
		);
		if ($row['catid']){
			$ret = updatetable(table('lianjie_fenlei'), $info, 'catid = '.$row['catid']);
			$db -> query("update ".table('lianjie')." set `catid_cn`='{$info['title']}' WHERE `catid` = {$row['catid']}");
		}else{
			$ret = inserttable(table('lianjie_fenlei'), $info);
		}
		if (!$ret){
			showmsg("系统提示", "操作失败，请稍后再试！");
		}else{
			$links[0]['title'] = '返回继续';
			$links[0]['href'] = get_link();
			$links[1]['title'] = '返回分类列表';
			$links[1]['href'] = get_link("a|catid").'&amp;a=fenlei';
			admin_log("添加或修改友情链接分类: {$info['title']}。", $admin_val['name']);
			showmsg("系统提示", "操作成功！", $links);
		}
	}
}elseif ($_GET['a'] == 'fldel') { //删除分类
	$row = $db->getone("SELECT * FROM ".table('lianjie_fenlei')." WHERE `catid`='{$_GET['catid']}'");
	if (empty($row)) showmsg("系统提示", "分类不存在！");
	if ($_GET['catid']=='1'){
		showmsg("系统提示", "默认分类请保留！");
	}
	if ($_GET['dosubmit']) {
		$_wheresql = "catid='{$_GET['catid']}'";
		$db->query("Delete from ".table('lianjie')." WHERE {$_wheresql}");
		$db->query("Delete from ".table('lianjie_fenlei')." WHERE {$_wheresql}");
		$db->query("Delete from ".table('lianjie_data')." WHERE {$_wheresql}");
		$links[1]['title'] = '返回分类列表';
		$links[1]['href'] = get_link("a|catid|dosubmit").'&amp;a=fenlei';
		admin_log("删除友情链接分类: {$row['title']}。", $admin_val['name']);
		showmsg("系统提示", "删除成功！", $links);
	}
	$links[0]['title'] = '返回继续';
	$links[0]['href'] = get_link("a").'&amp;a=fladd';
	$links[1]['title'] = '返回分类列表';
	$links[1]['href'] = get_link("a|catid").'&amp;a=fenlei';
	showmsg("系统提示", "你是否确定删除此友情链接分类并且删除分类下的所有友情链接？<br/><a href='".get_link()."&amp;dosubmit=1'>确定删除</a>", $links);
}else{
	if ($_POST['key']) $_GET['key'] = $_POST['key'];
	$wheresql = '';
	$wheresql.= isset($_GET['type'])?" AND `type`='{$_GET['type']}'":"";
	$wheresql.= isset($_GET['key'])?" AND (`title` like '%{$_GET['key']}%' OR `catid_cn` = '{$_GET['key']}')":"";
	$wheresql = ltrim(ltrim($wheresql),'AND');
	$ordersql = (isset($_GET['order']))?"`{$_GET['order']}`":"`id`";
	$ordersql.= " DESC";
	$smarty->assign('wheresql', $wheresql);
	$smarty->assign('ordersql', $ordersql);
	//
	$url = get_link('type');
	if ($_GET['type']=='1'){
		$typelink = '状态:<a href="'.$url.'">全部</a>|<a href="'.$url.'&amp;type=0">审核中</a>|正常|<a href="'.$url.'&amp;type=2">黑名单</a>';
	}elseif ($_GET['type']=='2'){
		$typelink = '状态:<a href="'.$url.'">全部</a>|<a href="'.$url.'&amp;type=0">审核中</a>|<a href="'.$url.'&amp;type=1">正常</a>|黑名单';
	}elseif ($_GET['type']=='0'){
		$typelink = '状态:<a href="'.$url.'">全部</a>|审核中|<a href="'.$url.'&amp;type=1">正常</a>|<a href="'.$url.'&amp;type=2">黑名单</a>';
	}else{
		$typelink = '状态:<a href="'.$url.'&amp;type=0">审核中</a>|<a href="'.$url.'&amp;type=1">正常</a>|<a href="'.$url.'&amp;type=2">黑名单</a>';
	}
	$smarty->assign('typelink', $typelink);
	//
	$url = get_link('order');
	if ($_GET['order']=='listorder'){
		$orderlink = '排序:按排序|<a href="'.$url.'&amp;order=inputtime">添加时间</a>|<a href="'.$url.'&amp;order=read">访问最多</a>|<a href="'.$url.'&amp;order=from">来访最多</a>|<a href="'.$url.'&amp;order=fromtime">最后来访</a>';
	}elseif ($_GET['order']=='inputtime'){
		$orderlink = '排序:<a href="'.$url.'&amp;order=listorder">按排序</a>|添加时间|<a href="'.$url.'&amp;order=read">访问最多</a>|<a href="'.$url.'&amp;order=from">来访最多</a>|<a href="'.$url.'&amp;order=fromtime">最后来访</a>';
	}elseif ($_GET['order']=='fromtime'){
		$orderlink = '排序:<a href="'.$url.'&amp;order=listorder">按排序</a>|<a href="'.$url.'&amp;order=inputtime">添加时间</a>|<a href="'.$url.'&amp;order=read">访问最多</a>|<a href="'.$url.'&amp;order=from">来访最多</a>|最后来访';
	}elseif ($_GET['order']=='read'){
		$orderlink = '排序:<a href="'.$url.'&amp;order=listorder">按排序</a>|<a href="'.$url.'&amp;order=inputtime">添加时间</a>|访问最多|<a href="'.$url.'&amp;order=from">来访最多</a>|<a href="'.$url.'&amp;order=fromtime">最后来访</a>';
	}elseif ($_GET['order']=='from'){
		$orderlink = '排序:<a href="'.$url.'&amp;order=listorder">按排序</a>|<a href="'.$url.'&amp;order=inputtime">添加时间</a>|<a href="'.$url.'&amp;order=read">访问最多</a>|来访最多|<a href="'.$url.'&amp;order=fromtime">最后来访</a>';
	}else{
		$orderlink = '排序:<a href="'.$url.'&amp;order=listorder">按排序</a>|<a href="'.$url.'&amp;order=inputtime">添加时间</a>|<a href="'.$url.'&amp;order=read">访问最多</a>|<a href="'.$url.'&amp;order=from">来访最多</a>|<a href="'.$url.'&amp;order=fromtime">最后来访</a>';
	}
	$smarty->assign('orderlink', $orderlink);
}



function fenleixing($str){
	if ($str=='1'){
		return '不需审核';
	}elseif ($str=='2'){
		return '进入审核';
	}else{
		return '需要审核';
	}
}
?>