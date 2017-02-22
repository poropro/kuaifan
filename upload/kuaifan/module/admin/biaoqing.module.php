<?php
/*
 * 回复表情
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');
kf_class::run_sys_func('neirong');
$up_dir_0 = "uploadfiles/content/em/";

if ($_GET['a'] == 'add') {
	if ($_POST['dosubmit']) {
		$_POST['em'] = trim($_POST['em']);
		if (!$_POST['em']) showmsg("系统提示", "请输入代号");
		$row = $db->getone("SELECT * FROM ".table('biaoqing')." WHERE `em`='{$_POST['em']}'");
		if (!empty($row)) showmsg("系统提示", "代号已存在,请重新填写");
		$row = $db->getone("SELECT * FROM ".table('biaoqing_fenlei')." WHERE `catid`='{$_POST['catid']}'");
		if (empty($row)) showmsg("系统提示", "请选择正确的分类");
		$info = array(
			'em' => $_POST['em'],
			'is' => $_POST['is'],
			'catid' => $row['catid'],
			'catid_cn' => $row['title'],
			'inputtime' => SYS_TIME,
			'listorder' => intval($_POST['listorder']),
		);
		if (!inserttable(table('biaoqing'), $info)){
			showmsg("系统提示", "添加失败，请稍后再试！");
		}else{
			kf_class::run_sys_func('upload');
			if ($_FILES['file']['name']){
				$_file_allowext = "gif|jpg|jpeg|png|bmp";
				$_file_allowext = str_replace('|', '/', $_file_allowext);
				$_file_allowext = str_replace(',', '/', $_file_allowext);
				//上传大小
				$_file_size = intval(ini_get('upload_max_filesize')*1024);
				//目录构造
				make_dir('./'.$up_dir_0);
				$_file = _asUpFiles('./'.$up_dir_0, 'file', $_file_size, $_file_allowext, 'e-time.tmp', true);
				if ($_file){
					if (!rename($up_dir_0.'e-time.tmp', $up_dir_0.md5($_POST['em']).'.gif')){
						//重命名失败
					}
				}
			}
			$links[0]['title'] = '继续添加';
			$links[0]['href'] = get_link();
			$links[1]['title'] = '返回表情列表';
			$links[1]['href'] = get_link("a");
			admin_log("添加表情: {$info['em']}。", $admin_val['name']);
			showmsg("系统提示", "添加成功！", $links);
		}
	}
	$row = $db->getall("SELECT * FROM ".table('biaoqing_fenlei')." ORDER BY listorder DESC");
	$fenleiarr = array();
	foreach($row as $_v) {
		$fenleiarr[$_v['title']] = $_v['catid'];
	}
	$smarty->assign('fenleiarr', $fenleiarr);
}elseif ($_GET['a'] == 'edit') { //修改
	$rowy = $db->getone("SELECT * FROM ".table('biaoqing')." WHERE `id`='{$_GET['id']}'");
	if (empty($rowy)) showmsg("系统提示", "表情不存在！");
	$rowy['inputtime'] = $rowy['inputtime']?date('Y-m-d H:i:s', $rowy['inputtime']):'';
	$smarty->assign('biaoqing', $rowy);
	
	if ($_POST['dosubmit']) {
		$_POST['em'] = trim($_POST['em']);
		if (!$_POST['em']) showmsg("系统提示", "请输入代号");
		$row = $db->getone("SELECT * FROM ".table('biaoqing')." WHERE `id`!='{$_GET['id']}' AND `em`='{$_POST['em']}'");
		if (!empty($row)) showmsg("系统提示", "代号已存在,请重新填写");
		$row = $db->getone("SELECT * FROM ".table('biaoqing_fenlei')." WHERE `catid`='{$_POST['catid']}'");
		if (empty($row)) showmsg("系统提示", "请选择正确的分类");
		$info = array(
			'em' => $_POST['em'],
			'is' => $_POST['is'],
			'catid' => $row['catid'],
			'catid_cn' => $row['title'],
			'inputtime' => SYS_TIME,
			'listorder' => intval($_POST['listorder']),
		);
		if (!updatetable(table('biaoqing'), $info, "`id`='{$_GET['id']}'")){
			showmsg("系统提示", "修改失败，请稍后再试！");
		}else{
			if ($_POST['em'] != $rowy['em']){
				rename($up_dir_0.md5($rowy['em']).'.gif', $up_dir_0.md5($_POST['em']).'.gif');
			}
			kf_class::run_sys_func('upload');
			if ($_FILES['file']['name']){
				$_file_allowext = "gif|jpg|jpeg|png|bmp";
				$_file_allowext = str_replace('|', '/', $_file_allowext);
				$_file_allowext = str_replace(',', '/', $_file_allowext);
				//上传大小
				$_file_size = intval(ini_get('upload_max_filesize')*1024);
				//目录构造
				make_dir('./'.$up_dir_0);
				$_file = _asUpFiles('./'.$up_dir_0, 'file', $_file_size, $_file_allowext, 'e-time.tmp', true);
				if ($_file){
					if (!rename($up_dir_0.'e-time.tmp', $up_dir_0.md5($_POST['em']).'.gif')){
						//重命名失败
					}
				}
			}
			$links[0]['title'] = '继续修改';
			$links[0]['href'] = get_link();
			$links[1]['title'] = '返回表情列表';
			$links[1]['href'] = get_link("a|id");
			admin_log("修改表情: {$info['em']}。", $admin_val['name']);
			showmsg("系统提示", "修改成功！", $links);
		}
	}	
	$row = $db->getall("SELECT * FROM ".table('biaoqing_fenlei')." ORDER BY listorder DESC");
	$fenleiarr = array();
	foreach($row as $_v) {
		$fenleiarr[$_v['title']] = $_v['catid'];
	}
	$smarty->assign('fenleiarr', $fenleiarr);
		
}elseif ($_GET['a'] == 'del') { //删除表情
	$row = $db->getone("SELECT * FROM ".table('biaoqing')." WHERE `id`='{$_GET['id']}'");
	if (empty($row)) showmsg("系统提示", "表情不存在！");
	if ($_GET['dosubmit']) {
		@unlink($up_dir_0.md5($row['em']).'.gif');
		$_wheresql = " WHERE catid='{$row['catid']}' AND id='{$row['id']}'";
		$db->query("Delete from ".table('biaoqing')." {$_wheresql}");
		$links[1]['title'] = '返回表情列表';
		$links[1]['href'] = get_link("a|id|dosubmit");
		admin_log("删除表情: {$row['em']}。", $admin_val['name']);
		showmsg("系统提示", "删除成功！", $links);
	}
	$links[0]['title'] = '返回继续';
	$links[0]['href'] = get_link("a").'&amp;a=edit';
	$links[1]['title'] = '返回分类列表';
	$links[1]['href'] = get_link("a|id");
	showmsg("系统提示", "你是否确定删除此表情吗？<br/><a href='".get_link()."&amp;dosubmit=1'>确定删除</a>", $links);
}elseif ($_GET['a'] == 'fenlei') { //分类列表
	
}elseif ($_GET['a'] == 'fladd') { //添加分类 、 修改分类
	if ($_GET['catid']){
		$row = $db->getone("SELECT * FROM ".table('biaoqing_fenlei')." WHERE `catid`='{$_GET['catid']}'");
		if (empty($row)) showmsg("系统提示", "分类不存在！");
	}else{
		$row = array(
			'listorder'=>'0',
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
		);
		if ($row['catid']){
			$ret = updatetable(table('biaoqing_fenlei'), $info, 'catid = '.$row['catid']);
			$db -> query("update ".table('biaoqing')." set `catid_cn`='{$info['title']}' WHERE `catid` = {$row['catid']}");
		}else{
			$ret = inserttable(table('biaoqing_fenlei'), $info);
		}
		if (!$ret){
			showmsg("系统提示", "操作失败，请稍后再试！");
		}else{
			$links[0]['title'] = '返回继续';
			$links[0]['href'] = get_link();
			$links[1]['title'] = '返回分类列表';
			$links[1]['href'] = get_link("a|catid").'&amp;a=fenlei';
			admin_log("添加或修改表情分类: {$info['title']}。", $admin_val['name']);
			showmsg("系统提示", "操作成功！", $links);
		}
	}
}elseif ($_GET['a'] == 'fldel') { //删除分类
	$row = $db->getone("SELECT * FROM ".table('biaoqing_fenlei')." WHERE `catid`='{$_GET['catid']}'");
	if (empty($row)) showmsg("系统提示", "分类不存在！");
	if ($_GET['catid']=='1'){
		showmsg("系统提示", "默认分类请保留！");
	}
	if ($_GET['dosubmit']) {
		$_wheresql = "catid='{$_GET['catid']}'";
		$drow = $db->getall("SELECT * FROM ".table('biaoqing')." WHERE {$_wheresql} ORDER BY listorder DESC");
		foreach($drow as $_v) {
			@unlink($up_dir_0.md5($_v['em']).'.gif');
		}
		$db->query("Delete from ".table('biaoqing')." WHERE {$_wheresql}");
		$db->query("Delete from ".table('biaoqing_fenlei')." WHERE {$_wheresql}");
		$links[1]['title'] = '返回分类列表';
		$links[1]['href'] = get_link("a|catid|dosubmit").'&amp;a=fenlei';
		admin_log("删除表情分类: {$row['title']}。", $admin_val['name']);
		showmsg("系统提示", "删除成功！", $links);
	}
	$links[0]['title'] = '返回继续';
	$links[0]['href'] = get_link("a").'&amp;a=fladd';
	$links[1]['title'] = '返回分类列表';
	$links[1]['href'] = get_link("a|catid").'&amp;a=fenlei';
	showmsg("系统提示", "你是否确定删除此表情分类并且删除分类下的所有表情？<br/><a href='".get_link()."&amp;dosubmit=1'>确定删除</a>", $links);
}else{
	if ($_GET['hide'] > 0){
		$row = $db->getone("SELECT * FROM ".table('biaoqing')." WHERE `id`='{$_GET['hide']}'");
		if ($row){
			$_is = ($row['is']==1)?0:1;
			$db -> query("update ".table('biaoqing')." set `is`='{$_is}' WHERE `id`='{$_GET['hide']}'");
		}
		tiaozhuan(get_link("hide"));
	}
	if ($_POST['key']) $_GET['key'] = $_POST['key'];
	$wheresql = '';
	$wheresql.= isset($_GET['type'])?" AND `type`='{$_GET['type']}'":"";
	$wheresql.= isset($_GET['key'])?" AND (`em` like '%{$_GET['key']}%' OR `catid_cn` = '{$_GET['key']}')":"";
	$wheresql = ltrim(ltrim($wheresql),'AND');
	$ordersql = "`listorder` DESC\,`id`";
	$smarty->assign('wheresql', $wheresql);
	$smarty->assign('ordersql', $ordersql);
}
?>