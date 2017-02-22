<?php
/*
 * 广告系统
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
		$info = array(
			'type' => $_POST['type'],
			'title' => $_POST['title'],
			'description' => $_POST['description'],
		);
		if (!inserttable(table('guanggao_fenlei'), $info)){
			showmsg("系统提示", "添加失败，请稍后再试！");
		}else{
			$links[0]['title'] = '继续添加';
			$links[0]['href'] = get_link();
			$links[1]['title'] = '返回广告位列表';
			$links[1]['href'] = get_link("a");
			admin_log("添加广告位: {$info['title']}。", $admin_val['name']);
			showmsg("系统提示", "添加成功！", $links);
		}
	}
	$fenleiarr = array(
		'图片链接'=>'images',
		'文字链接'=>'link',
		'UBB代码'=>'ubb',
		'WML代码'=>'wml',
	);
	$smarty->assign('fenleiarr', $fenleiarr);
}elseif ($_GET['a'] == 'edit') { //修改
	$row = $db->getone("SELECT * FROM ".table('guanggao_fenlei')." WHERE `catid`='{$_GET['catid']}'");
	if (empty($row)) showmsg("系统提示", "广告位不存在！");
	$smarty->assign('guanggaowei', $row);
	
	if ($_POST['dosubmit']) {
		if (empty($_POST['title'])) showmsg("系统提示", "请输入名称");
		$info = array(
			'title' => $_POST['title'],
			'description' => $_POST['description'],
		);
		if (!updatetable(table('guanggao_fenlei'), $info, "`catid`='{$_GET['catid']}'")){
			showmsg("系统提示", "修改失败，请稍后再试！");
		}else{
			$links[0]['title'] = '继续修改';
			$links[0]['href'] = get_link();
			$links[1]['title'] = '返回广告列表';
			$links[1]['href'] = get_link("a")."&amp;a=guanggaowei";
			$links[2]['title'] = '返回广告位列表';
			$links[2]['href'] = get_link("a|catid");
			admin_log("修改广告位: {$info['title']}。", $admin_val['name']);
			showmsg("系统提示", "修改成功！", $links);
		}
	}			
}elseif ($_GET['a'] == 'del') { //删除广告位
	$row = $db->getone("SELECT * FROM ".table('guanggao_fenlei')." WHERE `catid`='{$_GET['catid']}'");
	if (empty($row)) showmsg("系统提示", "广告位不存在！");
	if ($_GET['dosubmit']) {
		$_wheresql = "catid='{$row['catid']}'";
		$db->query("Delete from ".table('guanggao')." WHERE {$_wheresql}");
		$db->query("Delete from ".table('guanggao_data')." WHERE {$_wheresql}");
		$db->query("Delete from ".table('guanggao_fenlei')." WHERE {$_wheresql}");
		$links[0]['title'] = '返回广告位列表';
		$links[0]['href'] = get_link("a|catid|dosubmit");
		admin_log("删除广告位: {$row['title']}。", $admin_val['name']);
		showmsg("系统提示", "删除成功！", $links);
	}
	$links[0]['title'] = '返回继续';
	$links[0]['href'] = get_link("a").'&amp;a=guanggaowei';
	$links[1]['title'] = '返回广告位列表';
	$links[1]['href'] = get_link("a|catid");
	showmsg("系统提示", "你是否确定删除此广告位吗？<br/><a href='".get_link()."&amp;dosubmit=1'>确定删除</a>", $links);	
}elseif ($_GET['a'] == 'guanggaowei') { //广告列表
	$row = $db->getone("SELECT * FROM ".table('guanggao_fenlei')." WHERE `catid`='{$_GET['catid']}'");
	if (empty($row)) showmsg("系统提示", "广告位不存在！");
	$smarty->assign('guanggaowei', $row);
	
	if ($_POST['key']) $_GET['key'] = $_POST['key'];
	$wheresql = '';
	$wheresql.= isset($_GET['catid'])?" AND `catid`='{$_GET['catid']}'":"";
	$wheresql.= isset($_GET['key'])?" AND `title` like '%{$_GET['key']}%'":"";
	$wheresql = ltrim(ltrim($wheresql),'AND');
	$smarty->assign('wheresql', $wheresql);
}elseif ($_GET['a'] == 'ggadd') { //添加广告
	$row = $db->getone("SELECT * FROM ".table('guanggao_fenlei')." WHERE `catid`='{$_GET['catid']}'");
	if (empty($row)) showmsg("系统提示", "广告位不存在！");
	$smarty->assign('guanggaowei', $row);
	if ($_POST['dosubmit']) {
		if (empty($_POST['title'])) showmsg("系统提示", "请输入广告名称");
		$info = array(
			'catid' => $row['catid'],
			'cattype' => $row['type'],
			'catid_cn' => $row['title'],
			'title' => $_POST['title'],
			'startdate' => strtotime($_POST['startdate']),
			'enddate' => strtotime($_POST['enddate']),
			'addtime' => SYS_TIME,
			'hits' => 0,
			'clicks' => 0,
			'listorder' => 0,
			'disabled' => $_POST['disabled'],
		);
		$setting = array(
			'images' => $_POST['setting_images'],
			'link' => $_POST['setting_link'],
			'ubb' => $_POST['setting_ubb'],
			'wml' => $_POST['setting_wml'],
			'setmoney' => intval($_POST['setting_setmoney']),
			'setmoney_type' => $_POST['setting_setmoney_type'],
		);
		if ($_FILES['setting_upload_images']['name']){
			kf_class::run_sys_func('upload');
			make_dir('./uploadfiles/guanggao/'.date("Y/m/d/"));
			$_file = _asUpFiles('./uploadfiles/guanggao/'.date("Y/m/d/"), 'setting_upload_images', '1024', 'gif/jpg/jpeg/png/bmp', true);
			if ($_file){
				$setting['images'] = $_CFG['site_domain'].$_CFG['site_dir'].'uploadfiles/guanggao/'.date("Y/m/d/").$_file;
			}
		}
		$info['setting'] = array2string($setting);
		if (!inserttable(table("guanggao"),$info, true)){
			showmsg("系统提示", "添加失败，请稍后再试！");
		}else{
			//统计
			$total_sql="SELECT COUNT(*) AS num FROM ".table("guanggao")." WHERE catid = ".$row['catid']."";
			$total_count=$db->get_total($total_sql);
			updatetable(table("guanggao_fenlei"), array('items'=>$total_count,), "catid = ".$row['catid']."");
				
			$links[0]['title'] = '继续添加';
			$links[0]['href'] = get_link();
			$links[1]['title'] = '返回广告列表';
			$links[1]['href'] = get_link("a").'&amp;a=guanggaowei';
			admin_log("添加广告: {$info['title']}。", $admin_val['name']);
			showmsg("系统提示", "添加成功！", $links);
		}
	}
}elseif ($_GET['a'] == 'ggedit') { //修改广告
	$row = $db->getone("SELECT * FROM ".table('guanggao')." WHERE `id`='{$_GET['id']}'");
	if (empty($row)) showmsg("系统提示", "广告不存在！");
	$row['setting'] = string2array($row['setting']);
	$smarty->assign('guanggao', $row);
	
	if ($_POST['dosubmit']) {
		if (empty($_POST['title'])) showmsg("系统提示", "请输入广告名称");
		$info = array(
			'title' => $_POST['title'],
			'startdate' => strtotime($_POST['startdate']),
			'enddate' => strtotime($_POST['enddate']),
			'clicks' => $_POST['clicks'],
			'disabled' => $_POST['disabled'],
		);
		$setting = array(
			'images' => $_POST['setting_images'],
			'link' => $_POST['setting_link'],
			'ubb' => $_POST['setting_ubb'],
			'wml' => $_POST['setting_wml'],
			'setmoney' => intval($_POST['setting_setmoney']),
			'setmoney_type' => $_POST['setting_setmoney_type'],
		);
		if ($_FILES['setting_upload_images']['name']){
			kf_class::run_sys_func('upload');
			make_dir('./uploadfiles/guanggao/'.date("Y/m/d/"));
			$_file = _asUpFiles('./uploadfiles/guanggao/'.date("Y/m/d/"), 'setting_upload_images', '1024', 'gif/jpg/jpeg/png/bmp', true);
			if ($_file){
				$setting['images'] = $_CFG['site_domain'].$_CFG['site_dir'].'uploadfiles/guanggao/'.date("Y/m/d/").$_file;
			}
		}
		$info['setting'] = array2string($setting);
		if (!updatetable(table("guanggao"),$info, "`id`='{$_GET['id']}'")){
			showmsg("系统提示", "修改失败，请稍后再试！");
		}else{
			$links[0]['title'] = '继续修改';
			$links[0]['href'] = get_link();
			$links[1]['title'] = '返回广告列表';
			$links[1]['href'] = get_link("a|id").'&amp;a=guanggaowei';
			admin_log("修改广告: {$info['title']}。", $admin_val['name']);
			showmsg("系统提示", "修改成功！", $links);
		}
	}
}elseif ($_GET['a'] == 'ggdel') { //删除广告
	$row = $db->getone("SELECT * FROM ".table('guanggao')." WHERE `id`='{$_GET['id']}'");
	if (empty($row)) showmsg("系统提示", "广告不存在！");
	if ($_GET['dosubmit']) {
		$_wheresql = "id='{$row['id']}'";
		$db->query("Delete from ".table('guanggao')." WHERE {$_wheresql}");
		$db->query("Delete from ".table('guanggao_data')." WHERE {$_wheresql}");
		//统计
		$total_sql="SELECT COUNT(*) AS num FROM ".table("guanggao")." WHERE catid = ".$row['catid']."";
		$total_count=$db->get_total($total_sql);
		updatetable(table("guanggao_fenlei"), array('items'=>$total_count,), "catid = ".$row['catid']."");
		
		$links[0]['title'] = '返回广告列表';
		$links[0]['href'] = get_link("a|id|dosubmit").'&amp;a=guanggaowei';
		admin_log("删除广告: {$row['title']}。", $admin_val['name']);
		showmsg("系统提示", "删除成功！", $links);
	}
	$links[0]['title'] = '返回广告列表';
	$links[0]['href'] = get_link("a|id").'&amp;a=guanggaowei';
	showmsg("系统提示", "你是否确定删除此广告吗？<br/><a href='".get_link()."&amp;dosubmit=1'>确定删除</a>", $links);	
}elseif ($_GET['a'] == 'jilu') { //访问/来访纪录
	if ($_GET['del']){
		if ($admin_val['id']!='1') showmsg("系统提醒", "只有网站创始人才可以操作！");
		if ($_GET['del'] == 'all'){
			if ($_GET['dosubmit']){
				$db->query("Delete from ".table('guanggao_data')." WHERE id='{$_GET['id']}'");
				$links[0]['title'] = '返回列表';
				$links[0]['href'] = get_link("del|dosubmit");
				$links[1]['title'] = '返回广告页面';
				$links[1]['href'] = get_link("del|dosubmit|a").'&amp;a=ggedit';
				admin_log("删除广告ID{$_GET['id']}的点击纪录！", $admin_val['name']);
				showmsg("系统提醒", "删除全部纪录成功！", $links);
			}
			$links[0]['title'] = '确定删除';
			$links[0]['href'] = get_link()."&amp;dosubmit=1";
			$links[1]['title'] = '返回列表页面';
			$links[1]['href'] = get_link("del");
			showmsg("系统提醒", "确定删除所有纪录并且不可恢复吗？", $links);
		}else{
			$db->query("Delete from ".table('guanggao_data')." WHERE id='{$_GET['id']}' AND dataid in ({$_GET['del']})");
			$links[0]['title'] = '返回列表';
			$links[0]['href'] = get_link("del|dosubmit");
			$links[1]['title'] = '返回链接页面';
			$links[1]['href'] = get_link("del|dosubmit|a").'&amp;a=ggedit';
			admin_log("删除广告ID{$_GET['id']}的点击纪录！, dataid：{$_GET['del']}！", $admin_val['name']);
			showmsg("系统提醒", "删除纪录成功！", $links);
		}
	}
}else{
	if ($_POST['key']) $_GET['key'] = $_POST['key'];
	$wheresql = '';
	$wheresql.= isset($_GET['type'])?" AND `type`='{$_GET['type']}'":"";
	$wheresql.= isset($_GET['key'])?" AND `title` like '%{$_GET['key']}%'":"";
	$wheresql = ltrim(ltrim($wheresql),'AND');
	$smarty->assign('wheresql', $wheresql);
	//		
	$url = get_link('type');
	if ($_GET['type']=='images'){
		$typelink = '状态:<a href="'.$url.'">全部</a>|图链|<a href="'.$url.'&amp;type=link">文链</a>|<a href="'.$url.'&amp;type=ubb">UBB</a>|<a href="'.$url.'&amp;type=wml">WML</a>';
	}elseif ($_GET['type']=='link'){
		$typelink = '状态:<a href="'.$url.'">全部</a>|<a href="'.$url.'&amp;type=images">图链</a>|文链|<a href="'.$url.'&amp;type=ubb">UBB</a>|<a href="'.$url.'&amp;type=wml">WML</a>';
	}elseif ($_GET['type']=='ubb'){
		$typelink = '状态:<a href="'.$url.'">全部</a>|<a href="'.$url.'&amp;type=images">图链</a>|<a href="'.$url.'&amp;type=link">文链</a>|UBB|<a href="'.$url.'&amp;type=wml">WML</a>';
	}elseif ($_GET['type']=='wml'){
		$typelink = '状态:<a href="'.$url.'">全部</a>|<a href="'.$url.'&amp;type=images">图链</a>|<a href="'.$url.'&amp;type=link">文链</a>|<a href="'.$url.'&amp;type=ubb">UBB</a>|WML';
	}else{
		$typelink = '状态:<a href="'.$url.'&amp;type=images">图链</a>|<a href="'.$url.'&amp;type=link">文链</a>|<a href="'.$url.'&amp;type=ubb">UBB</a>|<a href="'.$url.'&amp;type=wml">WML</a>';
	}
	$smarty->assign('typelink', $typelink);
}



function fenleixing($str){
	if ($str=='images'){
		return '图片链接';
	}elseif ($str=='link'){
		return '文字链接';
	}elseif ($str=='ubb'){
		return 'UBB代码';
	}elseif ($str=='wml'){
		return 'WML代码';
	}else{
		return '未知';
	}
}
?>