<?php
/*
 * 会员模块
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');
kf_class::run_sys_func('huiyuan');

$__templatefile = $templatefile;

switch ($_GET['a']){
	case 'shenhe': //审核
		if ($_GET['n']){
			$links[0]['title'] = '审核列表';
			$links[0]['href'] = get_link("n|userid");
			$links[1]['title'] = '返回后台首页';
			$links[1]['href'] = $_admin_indexurl;
			$usersh = $db -> getone("select * from ".table('huiyuan_shenhe')." WHERE userid=".intval($_GET['userid'])."");
			if (empty($usersh)) showmsg("系统提醒", "要审核的会员已不存在！",$links);
			$modellistarr = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_moxing.php');
			$modellist = $modellistarr[$usersh['modelid']];
		}
		if ($_GET['n'] == 'tg' || $_GET['n'] == 'tgm') {
			if ($usersh['username']){
				if ($db -> getone("select * from ".table('huiyuan')." WHERE username = '{$usersh['username']}'")){
					showmsg("系统提醒", "要审核的会员用户名已被注册，无法审核！",$links);}
			}
			if ($usersh['email']){
				if ($db -> getone("select * from ".table('huiyuan')." WHERE email = '{$usersh['email']}'")){
					showmsg("系统提醒", "要审核的会员邮箱已被注册，无法审核！",$links);}
			}
			if ($usersh['mobile']){
				if ($db -> getone("select * from ".table('huiyuan')." WHERE mobile = '{$usersh['mobile']}'")){
					showmsg("系统提醒", "要审核的会员手机号码已被注册，无法审核！",$links);}
			}
			$usersh_ = array();
			$usersh_['password'] = md5s($usersh['password']);
			$usersh_['regdate'] = $usersh_['lastdate'] = $usersh['regdate'];
			$usersh_['username'] = $usersh['username'];
			$usersh_['nickname'] = $usersh['nickname'];
			$usersh_['email'] = $usersh['email'];
			$usersh_['regip'] = $usersh['regip'];
			$usersh_['point'] = $usersh['point'];
			$usersh_['groupid'] = _get_usergroup_bypoint($usersh['point']);
			$usersh_['amount'] = $usersh['amount'];
			$usersh_['encrypt'] = $usersh['encrypt'];
			$usersh_['modelid'] = $usersh['modelid'] ? $usersh['modelid'] : 1;
			$usersh_['mobile'] = $usersh['mobile'];
			$info_id = inserttable(table('huiyuan'),$usersh_,true);
			if($info_id > 0){
				$modelinfo = string2array($usersh['modelinfo']);
				if (!empty($modelinfo)){
					$modelinfo['userid'] = $info_id;
					inserttable(table('huiyuan_diy_'.$modellist['tablename']),$modelinfo,true);
				}
				$db->query("Delete from ".table('huiyuan_shenhe')." WHERE userid=".intval($usersh['userid']));
				if ($_GET['n'] == 'tgm') {
					//审核成功发送邮件
					$_mail = get_mail('reg',$usersh,$_CFG);
					smtp_mail($usersh['email'],$_mail['smtp_title'],$_mail['smtp_body']);
				}
				$link_[0]['title'] = '进入管理该会员';
				$link_[0]['href'] = $_admin_indexurl.'&amp;c=huiyuan&amp;a=xiangqing&amp;userid='.$info_id;
				admin_log("审核会员通过ID：{$info_id}。", $admin_val['name']);
				showmsg("系统提醒", "审核成功！",array_merge($link_,$links));
			}else{
				showmsg("系统提醒", "审核失败，请稍后再试！",$links);
			}
		}elseif ($_GET['n'] == 'btg' || $_GET['n'] == 'btgm') {
			if ($db->query("Delete from ".table('huiyuan_shenhe')." WHERE userid=".intval($usersh['userid']))){
				if ($_GET['n'] == 'btgm') {
					//审核成功发送邮件
					smtp_mail($usersh['email'],'帐号注册失败','你在'.$_CFG['site_name'].'注册的帐号：'.$usersh['username'].'未通过审核！');
				}
				admin_log("审核会员未通过ID：{$usersh['userid']}。", $admin_val['name']);
				showmsg("系统提醒", "操作成功！",$links);
			}else{
				showmsg("系统提醒", "审核失败，请稍后再试！",$links);
			}
		}
		break;
	case 'xiangqing_sh': //审核详情
		//会员组
		$grouplist = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_zu.php');
		foreach($grouplist as $k=>$v) {
			$grouplist[$k] = $v['name'];
		}
		$smarty->assign('grouplist', $grouplist);
		//会员所属模型
		$modellistarr = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_moxing.php');
		foreach($modellistarr as $k=>$v) {
			$modellistarr[$k] = $v['title'];
		}
		$smarty->assign('modellistarr', $modellistarr);
		break;
	case 'peizhi': //配置
		if($_POST['dosubmit']) {
			$info = array();
			foreach ($_POST as $_k => $_v){
				$_v = trim($_v);
				if (substr($_k,0,5)=='info_'){
					$info[substr($_k,5)] = $_v;
				}
			}
			$db->query("UPDATE ".table('peizhi_mokuai')." SET setting='".array2string($info)."' WHERE module='huiyuan'");
			$db->query("UPDATE ".table('peizhi')." SET value='{$_POST['closereg']}' WHERE name='closereg'");
			$db->query("UPDATE ".table('peizhi')." SET value='{$info['amountname']}' WHERE name='amountname'");
			$db->query("UPDATE ".table('peizhi')." SET value='{$_POST['hideusersid']}' WHERE name='hideusersid'");
			refresh_cache('peizhi');
			refresh_peizhi_mokuai('huiyuan');
			$links[0]['title'] = '重新修改';
			$links[0]['href'] = get_link("dosubmit");
			$links[1]['title'] = '返回后台首页';
			$links[1]['href'] = $_admin_indexurl;
			showmsg("系统提醒", "保存成功！",$links);
		}
		$peizhi = $db -> getone("select * from ".table('peizhi_mokuai')." WHERE module='huiyuan'");
		$peizhiarr = string2array($peizhi['setting']);
		$peizhiarr['closereg'] = $_CFG['closereg'];
		$peizhiarr['hideusersid'] = $_CFG['hideusersid'];
		$smarty->assign('peizhi', $peizhiarr);
		break;
	case 'shangchuan': //上传用户组图标
		$fenzu = $db -> getone("select * from ".table('huiyuan_zu')." WHERE groupid=".intval($_GET['edit'])." LIMIT 1");
		if (empty($fenzu)) showmsg("系统提醒", "你要操作的对象已经不存在！");
		if($_GET['delicon']) {
			if (!empty($fenzu['icon'])){
				$db->query("UPDATE ".table('huiyuan_zu')." SET `icon`='' WHERE groupid='{$_GET['edit']}'");
				refresh_cache_all('huiyuan_zu','','groupid');
				admin_log("删除用户组图标,ID{$_GET['edit']}。", $admin_val['name']);
				showmsg("系统提醒", "删除成功！");
			}else{
				showmsg("系统提醒", "删除失败，图标已经删除无须重复操作！");
			}
		}
		if($_POST['dosubmit']) {
			kf_class::run_sys_func('upload');
			//上传格式
			$_file_allowext = "gif|jpg|jpeg|png|bmp";
			$_file_allowext = str_replace('|', '/', $_file_allowext);
			$_file_allowext = str_replace(',', '/', $_file_allowext);
			//上传大小
			$_file_size = intval(ini_get('upload_max_filesize')*1024);
			//上传路径
			$up_dir = "uploadfiles/avatar/icon/";
			make_dir('./'.$up_dir);
			$_file = _asUpFiles('./'.$up_dir, 'icon', $_file_size, $_file_allowext, $_GET['edit']);
			if ($_file){
				$db->query("UPDATE ".table('huiyuan_zu')." SET `icon`='".$_CFG['site_domain'].$_CFG['site_dir'].$up_dir.$_file."' WHERE groupid='{$_GET['edit']}'");
				$links[0]['title'] = '重新上传';
				$links[0]['href'] = get_link();
				$links[1]['title'] = '修改页面';
				$links[1]['href'] = get_link("a").'&amp;a=fenzu';
				$links[2]['title'] = '返回后台首页';
				$links[2]['href'] = $_admin_indexurl;
				refresh_cache_all('huiyuan_zu','','groupid');
				admin_log("上传用户组图标,ID{$_GET['edit']}。", $admin_val['name']);
				showmsg("系统提醒", "用户组图标上传成功！", $links);
			}else{
				showmsg("系统提醒", "用户组图标上传失败！");
			}
		}
		$smarty->assign('fenzu', $fenzu);
		break;
	case 'fenzu': //分组
		if ($_GET['add']) {
			if($_POST['dosubmit']) {
				$info = array();
				if (strlen($_POST['info_name']) < 2) showmsg("系统提醒", "用户组名应该为2-8位之间！");
				if (strlen($_POST['info_point']) < 1 || intval($_POST['info_point'])<1) showmsg("系统提醒", "积分点数应该为1-8位的数字！");
				if (strlen($_POST['info_starnum']) < 1 || intval($_POST['info_starnum'])<1) showmsg("系统提醒", "星星数应该为1-8位的数字！");
				if (strlen($_POST['info_allowpostnum']) < 1) showmsg("系统提醒", "日最大投稿数应该为1-8位的数字！");
				if ($db -> getone("select * from ".table('huiyuan_zu')." WHERE name='{$_POST['info_name']}' LIMIT 1")) {
					showmsg("系统提醒", "会员组名称已经存在！");
				}
				$_POST['info_point'] = intval($_POST['info_point']);
				$_POST['info_starnum'] = intval($_POST['info_starnum']);
				$_POST['info_price_d'] = intval($_POST['info_price_d']);
				$_POST['info_price_m'] = intval($_POST['info_price_m']);
				$_POST['info_price_y'] = intval($_POST['info_price_y']);
				$_POST['info_allowmessage'] = intval($_POST['info_allowmessage']);
				$_POST['info_allowpostnum'] = intval($_POST['info_allowpostnum']);
				foreach ($_POST as $_k => $_v){
					$_v = trim($_v);
					if (substr($_k,0,5)=='info_'){
						$info[substr($_k,5)] = $_v;
					}
				}
				$info['allowpost'] = $info['allowpost'] ? 1 : 0;
				$info['allowupgrade'] = $info['allowupgrade'] ? 1 : 0;
				$info['allowpostverify'] = $info['allowpostverify'] ? 1 : 0;
				$info['allowsendmessage'] = $info['allowsendmessage'] ? 1 : 0;
				$info['allowattachment'] = $info['allowattachment'] ? 1 : 0;
				$info['allowsearch'] = $info['allowsearch'] ? 1 : 0;
				$info['allowvisit'] = $info['allowvisit'] ? 1 : 0;
				$info_id = inserttable(table('huiyuan_zu'),$info,true);
				$links[0]['title'] = '继续添加';
				$links[0]['href'] = get_link("dosubmit");
				$links[1]['title'] = '分类管理';
				$links[1]['href'] = get_link("add|dosubmit");
				$links[2]['title'] = '返回后台首页';
				$links[2]['href'] = $_admin_indexurl;
				if ($info_id > 0){
					refresh_cache_all('huiyuan_zu','','groupid');
					admin_log("添加了会员分组ID：{$info_id} | {$info['name']}。", $admin_val['name']);
					showmsg("添加成功", "添加成功！",$links);
				}else{
					showmsg("添加失败", "添加失败，网络繁忙请稍后再试！");
				}
			}
			$templatefile = $__templatefile."/add";
		}elseif ($_GET['edit']) {
			$fenzu = $db -> getone("select * from ".table('huiyuan_zu')." WHERE groupid=".intval($_GET['edit'])." LIMIT 1");
			if (!$fenzu) showmsg("系统提醒", "你要操作的对象已经不存在！");
			if($_POST['dosubmit']) {
				$info = array();
				if (strlen($_POST['info_name']) < 2) showmsg("系统提醒", "用户组名应该为2-8位之间！");
				//if (strlen($_POST['info_point']) < 1 || intval($_POST['info_point'])<1) showmsg("系统提醒", "积分点数应该为1-8位的数字！");
				//if (strlen($_POST['info_starnum']) < 1 || intval($_POST['info_starnum'])<1) showmsg("系统提醒", "星星数应该为1-8位的数字！");
				//if (strlen($_POST['info_allowpostnum']) < 1) showmsg("系统提醒", "日最大投稿数应该为1-8位的数字！");
				if ($db -> getone("select * from ".table('huiyuan_zu')." WHERE `name`='{$_POST['info_name']}' AND `groupid`!={$fenzu['groupid']} LIMIT 1")) {
					showmsg("系统提醒", "会员组名称已经存在！");
				}
				$_POST['info_point'] = intval($_POST['info_point']);
				$_POST['info_starnum'] = intval($_POST['info_starnum']);
				$_POST['info_price_d'] = intval($_POST['info_price_d']);
				$_POST['info_price_m'] = intval($_POST['info_price_m']);
				$_POST['info_price_y'] = intval($_POST['info_price_y']);
				$_POST['info_allowmessage'] = intval($_POST['info_allowmessage']);
				$_POST['info_allowpostnum'] = intval($_POST['info_allowpostnum']);
				foreach ($_POST as $_k => $_v){
					$_v = trim($_v);
					if (substr($_k,0,5)=='info_'){
						$info[substr($_k,5)] = $_v;
					}
				}
				$info['allowpost'] = $info['allowpost'] ? 1 : 0;
				$info['allowupgrade'] = $info['allowupgrade'] ? 1 : 0;
				$info['allowpostverify'] = $info['allowpostverify'] ? 1 : 0;
				$info['allowsendmessage'] = $info['allowsendmessage'] ? 1 : 0;
				$info['allowattachment'] = $info['allowattachment'] ? 1 : 0;
				$info['allowsearch'] = $info['allowsearch'] ? 1 : 0;
				$info['allowvisit'] = $info['allowvisit'] ? 1 : 0;
				$links[0]['title'] = '继续修改';
				$links[0]['href'] = get_link("dosubmit");
				$links[1]['title'] = '分类管理';
				$links[1]['href'] = get_link("edit|dosubmit");
				$links[2]['title'] = '返回后台首页';
				$links[2]['href'] = $_admin_indexurl;
				if (updatetable(table("huiyuan_zu"), $info, "groupid=".intval($_GET['edit'])."")){
					refresh_cache_all('huiyuan_zu','','groupid');
					admin_log("修改了会员分组ID：{$_GET['edit']} | {$fenzu['name']}。", $admin_val['name']);
					showmsg("修改成功", "修改成功！",$links);
				}else{
					showmsg("修改失败", "修改失败，网络繁忙请稍后再试！");
				}
			}
			$smarty->assign('fenzu', $fenzu);
			$templatefile = $__templatefile."/edit";
		}elseif ($_GET['del']) {
			$fenzu = $db -> getone("select * from ".table('huiyuan_zu')." WHERE groupid=".intval($_GET['del'])." LIMIT 1");
			if (!$fenzu) showmsg("系统提醒", "你要操作的对象已经不存在！");
			$links[0]['title'] = '分类管理';
			$links[0]['href'] = get_link("del|dosubmit");
			$links[1]['title'] = '返回后台首页';
			$links[1]['href'] = $_admin_indexurl;
			if($_GET['dosubmit']) {
				$db->query("Delete from ".table('huiyuan_zu')." WHERE groupid=".intval($_GET['del']));
				refresh_cache_all('huiyuan_zu','','groupid');
				admin_log("删除了会员分组ID：{$_GET['del']} | {$fenzu['name']}。", $admin_val['name']);
				showmsg("系统提醒", "删除成功！",$links);
			}
			$links_[0]['title'] = '确定删除';
			$links_[0]['href'] = get_link()."&amp;dosubmit=1";
			showmsg("系统提醒", "你确定删除此分组并且不可恢复吗？",array_merge($links_,$links));
		}else{
			$templatefile = $__templatefile."/index";
			$wheresql=" WHERE 1=1";
			$sql = "select * from ".table('huiyuan_zu')." {$wheresql} ORDER BY sort asc,groupid asc";
			$result = $db->query($sql);
			$__list = array();
			while($row = $db->fetch_array($result)){
				$__list[] = $row;
			}
			$smarty->assign('fenzu', $__list);
		}
		break;
	case 'moxing': //模型
		if ($_GET['add']) {
			$templatefile = $__templatefile."/add";
			if (!empty($_POST['dosubmit'])){
				if (!$_POST['title']) showmsg("系统提醒", "“模型名称”不能为空！");
				if (!$_POST['tablename']) showmsg("系统提醒", "“模型表键名”不能为空！");
				if (is_english($_POST['tablename'],1,1) != '3') showmsg("系统提醒", "“模型表键名”只能是数字或英文或下划线组合并且是字母开头！");
				if ($db -> getone("select * from ".table('huiyuan_moxing')." WHERE tablename='{$_POST['tablename']}' LIMIT 1")) {
					showmsg("系统提醒", "模型表键名“{$_POST['tablename']}”已经存在,不可重复添加！");	}
					$addmoxing = array();
					$addmoxing['title'] = trim($_POST['title']);
					$addmoxing['body'] = trim($_POST['body']);
					$addmoxing['tablename'] = trim($_POST['tablename']);
					$addmoxing['default_style'] = trim($_POST['default_style'])?trim($_POST['default_style']):'default';
					$addmoxing['category_template'] = trim($_POST['category_template'])?trim($_POST['category_template']):'category';
					$addmoxing['list_template'] = trim($_POST['list_template'])?trim($_POST['list_template']):'list';
					$addmoxing['show_template'] = trim($_POST['show_template'])?trim($_POST['show_template']):'show';
					$addmoxing['addtime'] = $timestamp;
					$links[0]['title'] = '继续添加';
					$links[0]['href'] = get_link();
					$links[1]['title'] = '返回列表';
					$links[1]['href'] = get_link('add');
					$links[2]['title'] = '返回后台首页';
					$links[2]['href'] = $_admin_indexurl;
					$modelid = inserttable(table('huiyuan_moxing'),$addmoxing, true);
					if (empty($modelid)){
						showmsg("系统提醒", "添加失败,请稍后再试！", $links);
					}else{
						$model_sql = file_get_contents(KF_ROOT_PATH.'kuaifan/resources/huiyuan_moxing.sql');
						$model_sql = str_replace('$basic_table', table('huiyuan_diy_'.$_POST['tablename']), $model_sql);
						//$model_sql = str_replace('$table_data', table('huiyuan_diy_'.$_POST['tablename'].'_data'), $model_sql);
						$model_sql = str_replace('$table_model_field', table('huiyuan_moxing_ziduan'), $model_sql);
						$model_sql = str_replace('$modelid',$modelid,$model_sql);
						sql_execute($model_sql);
						cache_field_huiyuan($modelid);
						refresh_cache_all('huiyuan_moxing');
						admin_log("添加了会员模型ID{$modelid}|{$_POST['title']}|{$_POST['tablename']}。", $admin_val['name']);
						showmsg("系统提醒", "添加成功！", $links);
					}
			}
		}elseif ($_GET['edit']) {
			$templatefile = $__templatefile."/edit";
			$__edit = $db -> getone("select * from ".table('huiyuan_moxing')." where id = ".intval($_GET['edit'])." LIMIT 1");
			if (!$__edit){
				showmsg("系统提醒", "要修改的模型ID".intval($_GET['edit'])."不存在！");
			}else{
				$smarty->assign('edit', $__edit);
			}
			if (!empty($_POST['dosubmit'])){
				if (!$_POST['title']) showmsg("系统提醒", "“模型名称”不能为空！");
				if (!$_POST['tablename']) showmsg("系统提醒", "“模型表键名”不能为空！");
				$addmoxing = array();
				$addmoxing['title'] = trim($_POST['title']);
				$addmoxing['body'] = trim($_POST['body']);
				$addmoxing['default_style'] = trim($_POST['default_style'])?trim($_POST['default_style']):'default';
				$addmoxing['category_template'] = trim($_POST['category_template'])?trim($_POST['category_template']):'category';
				$addmoxing['list_template'] = trim($_POST['list_template'])?trim($_POST['list_template']):'list';
				$addmoxing['show_template'] = trim($_POST['show_template'])?trim($_POST['show_template']):'show';
				$links[0]['title'] = '继续修改';
				$links[0]['href'] = get_link();
				$links[1]['title'] = '返回列表';
				$links[1]['href'] = get_link("edit");
				$links[2]['title'] = '返回后台首页';
				$links[2]['href'] = $_admin_indexurl;
				if (!updatetable(table('huiyuan_moxing'), $addmoxing, "id = ".intval($_GET['edit'])."")){
					showmsg("修改失败", "修改失败，网络繁忙请稍后再试！", $links);
				}else{
					cache_field_huiyuan($_GET['edit']);
					refresh_cache_all('huiyuan_moxing');
					admin_log("修改了会员模型ID{$_GET['edit']}|{$_POST['title']}|{$__edit['tablename']}。", $admin_val['name']);
					showmsg("系统提醒", "修改成功！", $links);
				}
			}
		}elseif ($_GET['start']) {
			$addmoxing = array();
			$addmoxing['mode'] = '0';
			$links[0]['title'] = '返回列表';
			$links[0]['href'] = get_link("start");
			$links[1]['title'] = '返回后台首页';
			$links[1]['href'] = $_admin_indexurl;
			if (!updatetable(table('huiyuan_moxing'), $addmoxing, "id = ".intval($_GET['start'])."")){
				showmsg("启用失败", "启用失败，网络繁忙请稍后再试！", $links);
			}else{
				cache_field_huiyuan($_GET['start']);
				admin_log("启用了会员模型ID{$_GET['start']}。", $admin_val['name']);
				showmsg("系统提醒", "启用成功！", $links);
			}
		}elseif ($_GET['end']) {
			$addmoxing = array();
			$addmoxing['mode'] = '1';
			$links[0]['title'] = '返回列表';
			$links[0]['href'] = get_link("end");
			$links[1]['title'] = '返回后台首页';
			$links[1]['href'] = $_admin_indexurl;
			if (!updatetable(table('huiyuan_moxing'), $addmoxing, "id = ".intval($_GET['end'])."")){
				showmsg("禁用失败", "禁用失败，网络繁忙请稍后再试！", $links);
			}else{
				cache_field_huiyuan($_GET['end']);
				admin_log("禁用了会员模型ID{$_GET['end']}。", $admin_val['name']);
				showmsg("系统提醒", "禁用成功！", $links);
			}
		}elseif ($_GET['del']) {
			$__del = $db -> getone("select * from ".table('huiyuan_moxing')." where id = ".intval($_GET['del'])." LIMIT 1");
			if (!$__del){
				showmsg("系统提醒", "要删除的模型ID".intval($_GET['del'])."不存在！");
			}
			$___del = $db -> getone("select * from ".table('huiyuan')." WHERE modelid=".intval($_GET['del'])." LIMIT 1");
			if (!empty($___del)){
				showmsg("系统提醒", "要删除的模型ID".intval($_GET['del'])."还有会员，不可删除！");
			}
			if ($_GET['dosubmit']) {
				$links[0]['title'] = '返回列表';
				$links[0]['href'] = get_link("del|dosubmit");
				$links[1]['title'] = '返回后台首页';
				$links[1]['href'] = $_admin_indexurl;
				if (!$db->query("Delete from ".table('huiyuan_moxing')." where id = ".intval($_GET['del']))){
					showmsg("系统提醒", "删除失败,请稍后再试！", $links);
				}else{
					$db->query("DROP TABLE ".table('huiyuan_diy_'.$__del['tablename']));
					//$db->query("DROP TABLE ".table('huiyuan_diy_'.$__del['tablename'].'_data'));
					//$db->query("Delete from ".table('sousuo')." where modelid = ".intval($_GET['del']));
					$db->query("Delete from ".table('huiyuan_moxing_ziduan')." where modelid = ".intval($_GET['del']));
					cache_field_huiyuan_del(intval($_GET['del']));
					refresh_cache_all('huiyuan_moxing');
					admin_log("删除了会员模型ID{$_GET['del']}|{$__del['title']}|{$__del['tablename']}。", $admin_val['name']);
					showmsg("系统提醒", "删除成功！", $links);
				}
			}
			$links[0]['title'] = '确定删除';
			$links[0]['href'] = get_link("dosubmit")."&amp;dosubmit=1";
			$links[1]['title'] = '返回列表';
			$links[1]['href'] = get_link("del");
			$links[2]['title'] = '返回后台首页';
			$links[2]['href'] = $_admin_indexurl;
			showmsg("系统提醒", "你确定删除模型“{$__del['title']}(ID{$__del['id']})”并且不可恢复吗？", $links);
		}elseif ($_GET['field']) {
			$__templatefile = $__templatefile."/field";
			$wheresql = " WHERE id = ".intval($_GET['field'])."";
			$__field = $db -> getone("select * from ".table('huiyuan_moxing')." {$wheresql} LIMIT 1");
			if (!$__field){
				showmsg("系统提醒", "要操作的模型ID".intval($_GET['field'])."不存在！");
			}
			$smarty->assign('field', $__field);
				
			if ($_GET['fadd']) {
				$templatefile = $__templatefile."/add";
			}elseif ($_GET['faddl']) {
				if (!empty($_POST['dosubmit'])){

					if (!$_POST['field']) showmsg("系统提醒", "“字段名”不能为空！");
					if (!$_POST['name']) showmsg("系统提醒", "“字段别名”不能为空！");
					if (is_english($_POST['field'],1,1) != '3') showmsg("系统提醒", "“字段名”只能是数字或英文或下划线组合并且是字母开头！");
					$ziduandb = "WHERE modelid = ".intval($_GET['field'])." and field='".trim($_POST['field'])."'";
					$ziduandb = $db -> getone("select * from ".table('huiyuan_moxing_ziduan')." {$ziduandb} LIMIT 1");
					if (!empty($ziduandb)) { showmsg("系统提醒", "字段名已经存在，不可重复添加！"); }
						
					$addziduan = array();
					$addziduan['modelid'] = intval($_GET['field']);
					$addziduan['type'] = trim($_GET['faddl']);
					$addziduan['issystem'] = trim($_POST['issystem']);
					$addziduan['field'] = trim($_POST['field']);
					$addziduan['name'] = trim($_POST['name']);
					$addziduan['tips'] = trim($_POST['tips']);
					$addziduan['setting']['defaultvalue'] = trim($_POST['setting_defaultvalue']);
					$addziduan['setting']['ispassword'] = trim($_POST['setting_ispassword']);
					$addziduan['setting']['enablehtml'] = trim($_POST['setting_enablehtml']);
					$addziduan['setting']['options'] = trim($_POST['setting_options']);
					$addziduan['setting']['options'] = str_replace('///', Chr(13).Chr(10), $addziduan['setting']['options']);
					$addziduan['setting']['boxtype'] = trim($_POST['setting_boxtype']);
					$addziduan['setting']['fieldtype'] = trim($_POST['setting_fieldtype']);
					$addziduan['setting']['outputtype'] = trim($_POST['setting_outputtype']);
					$addziduan['setting']['minnumber'] = intval($_POST['setting_minnumber']);
					$addziduan['setting']['maxnumber'] = intval($_POST['setting_maxnumber']);
					$addziduan['setting']['decimaldigits'] = trim($_POST['setting_decimaldigits']);
					$addziduan['setting']['format'] = trim($_POST['setting_format']);
					$addziduan['setting']['upload_allowext'] = trim($_POST['setting_upload_allowext']);
					$addziduan['setting']['upload_number'] = trim($_POST['setting_upload_number']);
					$addziduan['setting']['downloadtype'] = trim($_POST['setting_downloadtype']);
					$addziduan['minlength'] = intval($_POST['minlength']);
					$addziduan['maxlength'] = intval($_POST['maxlength']);
					$addziduan['listorder'] = intval($_POST['listorder']);
						
					$tablename = table('huiyuan_diy_'.$__field['tablename']);
					//if ($addziduan['issystem'] == '0') $tablename = table('huiyuan_diy_'.$__field['tablename'].'_data');
					$field = $addziduan['field'];
					$minlength = $addziduan['minlength'] ? $addziduan['minlength'] : 0;
					$maxlength = $addziduan['maxlength'] ? $addziduan['maxlength'] : 0;
					$field_type = $addziduan['type'];
					if ($addziduan['type'] == 'text') $field_type = 'varchar';
					if ($addziduan['type'] == 'textarea') $field_type = 'mediumtext';
					if ($addziduan['type'] == 'box') $field_type = 'int';
					if ($addziduan['type'] == 'datetime') $field_type = 'int';
					if ($addziduan['type'] == 'images') $field_type = 'text';
					if ($addziduan['type'] == 'downfile') $field_type = 'text';
					if ($addziduan['setting']['fieldtype']) $field_type = $addziduan['setting']['fieldtype'];
					require(KF_ROOT_PATH.'kuaifan/resources/ziduan.sql.php');
					$links[0]['title'] = '继续添加';
					$links[0]['href'] = get_link("fadd|faddl|dosubmit")."&amp;fadd=1";
					$links[1]['title'] = '返回列表';
					$links[1]['href'] = get_link("fadd|faddl|dosubmit");
					$links[2]['title'] = '返回后台首页';
					$links[2]['href'] = $_admin_indexurl;
					$addziduan['setting'] = array2string($addziduan['setting']);
					if (!inserttable(table('huiyuan_moxing_ziduan'),$addziduan)){
						showmsg("系统提醒", "添加失败,请稍后再试！", $links);
					}else{
						cache_field_huiyuan($_GET['field']);
						//更新字段附件为已用
						if ($addziduan['type'] == 'images' || $addziduan['type'] == 'downfile'){
							//$db->query("UPDATE ".table('neirong_fujian')." SET of='1' WHERE modelid='".$addziduan['modelid']."' AND field='".$addziduan['field']."'");
						}
						admin_log("添加会员字段{$addziduan['field']}到{$__field['tablename']}。", $admin_val['name']);
						showmsg("系统提醒", "添加成功！", $links);
					}

				}
				$templatefile = $__templatefile."/addl";
				$tempformat = "<select name=\"setting_format".fenmiao()."\">";
				$tempformat.= "<option value=\"Y-m-d H:i:s\">(24小时制)".date('Y-m-d H:i:s')."</option>";
				$tempformat.= "<option value=\"Y-m-d Ah:i:s\">(12小时制)".date('Y-m-d h:i:s')."</option>";
				$tempformat.= "<option value=\"Y-m-d H:i\">".date('Y-m-d H:i')."</option>";
				$tempformat.= "<option value=\"Y-m-d\">".date('Y-m-d')."</option>";
				$tempformat.= "<option value=\"m-d\">".date('m-d')."</option>";
				$tempformat.= "</select>";
				$smarty->assign('tempdatetime', $tempformat);
			}elseif ($_GET['fstart']) {
				$addmoxing = array();
				$addmoxing['status'] = '0';
				$links[0]['title'] = '返回列表';
				$links[0]['href'] = get_link("fstart");
				$links[1]['title'] = '返回后台首页';
				$links[1]['href'] = $_admin_indexurl;
				if (!updatetable(table('huiyuan_moxing_ziduan'), $addmoxing, "id = ".intval($_GET['fstart'])."")){
					showmsg("启用失败", "启用失败，网络繁忙请稍后再试！", $links);
				}else{
					cache_field_huiyuan($_GET['field']);
					admin_log("启用了会员字段ID{$_GET['fstart']}。", $admin_val['name']);
					showmsg("系统提醒", "启用成功！", $links);
				}
			}elseif ($_GET['fend']) {
				$addmoxing = array();
				$addmoxing['status'] = '1';
				$links[0]['title'] = '返回列表';
				$links[0]['href'] = get_link("fend");
				$links[1]['title'] = '返回后台首页';
				$links[1]['href'] = $_admin_indexurl;
				if (!updatetable(table('huiyuan_moxing_ziduan'), $addmoxing, "id = ".intval($_GET['fend'])."")){
					showmsg("禁用失败", "禁用失败，网络繁忙请稍后再试！", $links);
				}else{
					cache_field_huiyuan($_GET['field']);
					admin_log("禁用了会员字段ID{$_GET['fend']}。", $admin_val['name']);
					showmsg("系统提醒", "禁用成功！", $links);
				}
			}elseif ($_GET['fdel']) {
				$__fdel = $db -> getone("select * from ".table('huiyuan_moxing_ziduan')." where id = ".intval($_GET['fdel'])." LIMIT 1");
				if (!$__fdel){
					showmsg("系统提醒", "要删除的字段ID".intval($_GET['fdel'])."不存在！");
				}
				if ($_GET['dosubmit']) {
					$links[0]['title'] = '返回列表';
					$links[0]['href'] = get_link("fdel|dosubmit");
					$links[1]['title'] = '返回后台首页';
					$links[1]['href'] = $_admin_indexurl;
					if (!$db->query("Delete from ".table('huiyuan_moxing_ziduan')." where id = ".intval($_GET['fdel']))){
						showmsg("系统提醒", "删除失败,请稍后再试！", $links);
					}else{
						$table_name = table('huiyuan_diy_'.$__field['tablename']);
						//if ($__fdel['issystem'] == '0') $table_name = table('huiyuan_diy_'.$__field['tablename'].'_data');
						$db->query("ALTER TABLE `{$table_name}` DROP `{$__fdel['field']}`;");
						cache_field_huiyuan($_GET['field']);
						//更新字段附件为未用
						if ($__fdel['type'] == 'images' || $__fdel['type'] == 'downfile'){
							//$db->query("UPDATE ".table('neirong_fujian')." SET of='0' WHERE modelid='".intval($_GET['field'])."' AND field='".$__fdel['field']."'");
						}
						admin_log("删除了会员字段ID{$_GET['fdel']}|{$__fdel['name']}。", $admin_val['name']);
						showmsg("系统提醒", "删除成功！", $links);
					}
				}
				$links[0]['title'] = '确定删除';
				$links[0]['href'] = get_link("dosubmit")."&amp;dosubmit=1";
				$links[1]['title'] = '返回列表';
				$links[1]['href'] = get_link("fdel");
				$links[2]['title'] = '返回后台首页';
				$links[2]['href'] = $_admin_indexurl;
				showmsg("系统提醒", "你确定删除字段“{$__fdel['name']}(ID{$__fdel['id']})”并且不可恢复吗？", $links);
			}elseif ($_GET['fedit']) {
				$wheresql = " WHERE id = ".intval($_GET['fedit'])."";
				$__ziduan = $db -> getone("select * from ".table('huiyuan_moxing_ziduan')." {$wheresql} LIMIT 1");
				if (!$__ziduan){
					showmsg("系统提醒", "要操作的字段ID".intval($_GET['fedit'])."不存在！");
				}

				if (!empty($_POST['dosubmit'])){
					if (!$_POST['name']) showmsg("系统提醒", "“字段别名”不能为空！");
					$addziduan = array();
					//$addziduan['modelid'] = intval($_GET['field']);
					//$addziduan['type'] = trim($_GET['faddl']);
					//$addziduan['issystem'] = trim($_POST['issystem']);
					//$addziduan['field'] = trim($_POST['field']);
					$addziduan['name'] = trim($_POST['name']);
					$addziduan['tips'] = trim($_POST['tips']);
					$addziduan['setting']['defaultvalue'] = trim($_POST['setting_defaultvalue']);
					$addziduan['setting']['ispassword'] = trim($_POST['setting_ispassword']);
					$addziduan['setting']['enablehtml'] = trim($_POST['setting_enablehtml']);
					$addziduan['setting']['options'] = trim($_POST['setting_options']);
					$addziduan['setting']['options'] = str_replace('///', Chr(13).Chr(10), $addziduan['setting']['options']);
					$addziduan['setting']['boxtype'] = trim($_POST['setting_boxtype']);
					$addziduan['setting']['outputtype'] = trim($_POST['setting_outputtype']);
					$addziduan['setting']['minnumber'] = intval($_POST['setting_minnumber']);
					$addziduan['setting']['maxnumber'] = intval($_POST['setting_maxnumber']);
					$addziduan['setting']['decimaldigits'] = trim($_POST['setting_decimaldigits']);
					$addziduan['setting']['format'] = trim($_POST['setting_format']);
					$addziduan['setting']['upload_allowext'] = trim($_POST['setting_upload_allowext']);
					$addziduan['setting']['upload_number'] = trim($_POST['setting_upload_number']);
					$addziduan['setting']['downloadtype'] = trim($_POST['setting_downloadtype']);
					$addziduan['setting'] = array2string($addziduan['setting']);
					if ($__ziduan['del']) unset($addziduan['setting']);
					$addziduan['minlength'] = intval($_POST['minlength']);
					$addziduan['maxlength'] = intval($_POST['maxlength']);
					$addziduan['listorder'] = intval($_POST['listorder']);
					$links[0]['title'] = '继续修改';
					$links[0]['href'] = get_link("dosubmit");
					$links[1]['title'] = '返回列表';
					$links[1]['href'] = get_link("fedit|dosubmit");
					$links[2]['title'] = '返回后台首页';
					$links[2]['href'] = $_admin_indexurl;
					$wheresql=" id=".intval($_GET['fedit'])."";
					if (!updatetable(table('huiyuan_moxing_ziduan'), $addziduan, $wheresql)){
						showmsg("系统提醒", "修改失败,请稍后再试！", $links);
					}else{
						cache_field_huiyuan($_GET['field']);
						admin_log("修改会员字段{$__ziduan['field']}。", $admin_val['name']);
						showmsg("系统提醒", "修改成功！", $links);
					}
				}
				$templatefile = $__templatefile."/edit";
				$__setting = string2array($__ziduan['setting']);
				$__setting['options_vs1'] = str_replace(Chr(13).Chr(10), '///', $__setting['options']);
				$tempformat = "<select name=\"setting_format".fenmiao()."\" value=\"{$__setting['format']}\">";
				if ($_GET['vs'] == '1') {
					$tempformat = "<select name=\"setting_format".fenmiao()."\" value=\"{$__setting['format']}\">";
				}else{
					$tempformat = "<select name=\"setting_format".fenmiao()."\">";
				}
				$forval = ($__setting['format']=='Y-m-d H:i:s' && $_GET['vs']!='1')?" selected=\"selected\"":"";
				$tempformat.= "<option value=\"Y-m-d H:i:s\"{$forval}>(24小时制)".date('Y-m-d H:i:s')."</option>";
				$forval = ($__setting['format']=='Y-m-d Ah:i:s' && $_GET['vs']!='1')?" selected=\"selected\"":"";
				$tempformat.= "<option value=\"Y-m-d Ah:i:s\"{$forval}>(12小时制)".date('Y-m-d h:i:s')."</option>";
				$forval = ($__setting['format']=='Y-m-d H:i' && $_GET['vs']!='1')?" selected=\"selected\"":"";
				$tempformat.= "<option value=\"Y-m-d H:i\"{$forval}>".date('Y-m-d H:i')."</option>";
				$forval = ($__setting['format']=='Y-m-d' && $_GET['vs']!='1')?" selected=\"selected\"":"";
				$tempformat.= "<option value=\"Y-m-d\"{$forval}>".date('Y-m-d')."</option>";
				$forval = ($__setting['format']=='m-d' && $_GET['vs']!='1')?" selected=\"selected\"":"";
				$tempformat.= "<option value=\"m-d\"{$forval}>".date('m-d')."</option>";
				$tempformat.= "</select>";
				$__setting['format'] = $tempformat;
				$smarty->assign('ziduan', $__ziduan);
				$smarty->assign('setting', $__setting);
			}else{
				$templatefile = $__templatefile."/index";
				$wheresql = " WHERE modelid = ".intval($_GET['field'])." and hide = 0";
				$sql = "select * from ".table('huiyuan_moxing_ziduan')." {$wheresql} ORDER BY listorder asc";
				$result = $db->query($sql);
				$__list = array();
				while($row = $db->fetch_array($result)){
					$__list[] = $row;
				}
				$smarty->assign('ziduan', $__list);
			}
		}else{
			$templatefile = $__templatefile."/index";
			$wheresql=" WHERE 1=1";
			$sql = "select * from ".table('huiyuan_moxing')." {$wheresql} ORDER BY id asc";
			$result = $db->query($sql);
			$__list = array();
			while($row = $db->fetch_array($result)){
				$__list[] = $row;
			}
			$smarty->assign('moxing', $__list);
		}		
		break;
	case 'add': //添加新会员
		if($_POST['dosubmit']) {
			$info = array();
			$detail = array();
			foreach ($_POST as $_k => $_v){
				$_v = trim($_v);
				if ($_k=='info_userid'){
					if (intval($_v) > 0){
						$row = $db -> getone("select * from ".table('huiyuan')." WHERE userid=".intval($_v));
						if (empty($row)) $info[substr($_k,5)] = $_v;
					}
					continue;
				}
				if (substr($_k,0,5)=='info_'){
					$info[substr($_k,5)] = $_v;
					if (substr($_k,5) == 'regdate' || substr($_k,5) == 'lastdate'){
						if ($_v) $info[substr($_k,5)] = strtotime($_v);
					}
				}
				if (substr($_k,0,7)=='detail_'){
					if (substr($_k,7,5) == 'date_'){
						$detail[substr($_k,12)] = strtotime($_v);
					}else{
						$detail[substr($_k,7)] = $_v;
					}
				}
			}
			$links[0]['title'] = '重新添加';
			$links[0]['href'] = get_link();
			$links[1]['title'] = '重选模型';
			$links[1]['href'] = get_link("mo");
			$links[2]['title'] = '会员列表';
			$links[2]['href'] = get_link("a|mo");
			$links[3]['title'] = '返回后台首页';
			$links[3]['href'] = $_admin_indexurl;
			if (!$info['nickname']){
				showmsg("系统提醒", "昵称不能为空！",$links);
			}
			if ($info['username']){
				if ($db -> getone("select * from ".table('huiyuan')." WHERE username = '{$info['username']}'")){
					showmsg("系统提醒", "用户名已存在，无法添加！",$links);}
			}else{
				showmsg("系统提醒", "用户名不能为空！",$links);
			}
			if ($info['email']){
				if ($db -> getone("select * from ".table('huiyuan')." WHERE email = '{$info['email']}'")){
					showmsg("系统提醒", "邮箱已被注册，无法添加！",$links);}
			}
			if ($info['mobile']){
				if ($db -> getone("select * from ".table('huiyuan')." WHERE mobile = '{$info['mobile']}'")){
					showmsg("系统提醒", "手机号码已被注册，无法添加！",$links);}
			}
			$userpass = generate_password(6,1);
			//UCenter会员注册
			$ucuserid = 0;
			kf_class::ucenter();
			if (UC_USE == '1'){
				$ucuid= uc_user_register($info['username'], $userpass, $info['email'], '', '', $online_ip);
				if(is_numeric($ucuid)) {
					switch ($ucuid) {
						case '-1':
							showmsg("系统提示:UCenter", "用户名不合法！", $links);
							break;
						case '-2':
							showmsg("系统提示:UCenter", "包含要允许注册的词语！", $links);
							break;
						case '-3':
							showmsg("系统提示:UCenter", "用户名已经存在！", $links);
							break;
						case '-4':
							showmsg("系统提示:UCenter", "Email 格式有误！", $links);
							break;
						case '-5':
							showmsg("系统提示:UCenter", "Email 不允许注册！", $links);
							break;
						case '-6':
							showmsg("系统提示:UCenter", "该 Email 已经被注册！", $links);
							break;
						default :
							$ucuserid = $ucuid;
							break;
					}
				}else{
					showmsg("系统提示:UCenter", "网络繁忙，请稍后再试。", $links);
				}
			}
			//开始添加
			$info['ucuserid'] = $ucuserid;
			$info['password'] = md5s($userpass);
			$info_id = inserttable(table('huiyuan'), $info, true);
			if($info_id > 0){
				if (!empty($detail)){
					$modellistarr = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_moxing.php');
					$modellist = $modellistarr[$info['modelid']];
					$detail['userid'] = $info_id;
					inserttable(table('huiyuan_diy_'.$modellist['tablename']),$detail,true);
				}
				$links[0]['title'] = '继续添加';
				$link_[0]['title'] = '进入管理该会员';
				$link_[0]['href'] = $_admin_indexurl.'&amp;c=huiyuan&amp;a=xiangqing&amp;userid='.$info_id;
				admin_log("添加会员ID：{$info_id}。", $admin_val['name']);
				showmsg("系统提醒", "添加成功！<br/>会员ID: {$info_id}<br/>密码: {$userpass}",array_merge($link_,$links));
			}else{
				showmsg("系统提醒", "添加失败，请稍后再试！",$links);
			}
		}
		//会员组
		$grouplist = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_zu.php');
		foreach($grouplist as $k=>$v) {
			$grouplist[$k] = $v['name'];
		}
		$smarty->assign('grouplist', $grouplist);
		//会员所属模型
		$modellistarr = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_moxing.php');
		$smarty->assign('modellistarr', $modellistarr);
		//默认积分、金币
		$member_setting = $db -> getone("select * from ".table('peizhi_mokuai')." WHERE module='huiyuan'");
		$member_setting = string2array($member_setting['setting']);
		$point = $member_setting['defualtpoint'] ? $member_setting['defualtpoint'] : 0;
		$amount = $member_setting['defualtamount'] ? $member_setting['defualtamount'] : 0;
		$smarty->assign('point', $point);
		$smarty->assign('amount', $amount);
		$smarty->assign('bypoint', _get_usergroup_bypoint($point));
		//模型字段
		if ($_GET['mo']){
			$field = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'model'.DIRECTORY_SEPARATOR.'model_field_'.$_GET['mo'].'.huiyuan.cache.php');
			$smarty->assign('field', $field);
		}
		break;
	case 'xiangqing': //详情
		//会员组
		$grouplist = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_zu.php');
		foreach($grouplist as $k=>$v) {
			$grouplist[$k] = $v['name'];
		}
		$smarty->assign('grouplist', $grouplist);
		//会员所属模型
		$modellistarr = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_moxing.php');
		foreach($modellistarr as $k=>$v) {
			$modellistarr[$k] = $v['title'];
		}
		$smarty->assign('modellistarr', $modellistarr);
		break;
	case 'shenfen': //通过会员身份进入网站
		$_SESSION['sid'] = $_GET['sf'];
		setcookie("kf_usersid", $_GET['sf'], SYS_TIME+3600);
		tiaozhuan(get_link('vs','&','1')."&m=index&sid=".$_GET['sf']);
	case 'xiugai': //修改
		//会员组
		$grouplist = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_zu.php');
		foreach($grouplist as $k=>$v) {
			$grouplist[$k] = $v['name'];
		}
		$smarty->assign('grouplist', $grouplist);
		//会员所属模型
		$modellistarr = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_moxing.php');
		foreach($modellistarr as $k=>$v) {
			$modellistarr[$k] = $v['title'];
		}
		$smarty->assign('modellistarr', $modellistarr);
		
		if($_POST['dosubmit']) {
			$info = array();
			$detail = array();
			foreach ($_POST as $_k => $_v){
				$_v = trim($_v);
				if (substr($_k,0,5)=='info_'){
					$info[substr($_k,5)] = $_v;
					if (substr($_k,5) == 'regdate' || substr($_k,5) == 'lastdate'){
						if ($_v) $info[substr($_k,5)] = strtotime($_v);
					}
					if (substr($_k,5) == 'overduedate'){ //vip过期时间
						if ($_POST['info_vip']){
							$info['overduedate'] = strtotime($_v);
						}else{
							unset($info['overduedate']);
						}
					}
					if (substr($_k,5) == 'colorname'){ //颜色用户名
						if ($_v){
							if (substr($_v,0,1) == "#"){
								$info['colorname'] = $_v;
							}else{
								$info['colorname'] = "#".$_v;
							}
							$info['colorname'] = substr($info['colorname'],0,7);
						}
					}
				}
				if (substr($_k,0,7)=='detail_'){
					if (substr($_k,7,5) == 'date_'){
						$detail[substr($_k,12)] = strtotime($_v);
					}else{
						$detail[substr($_k,7)] = $_v;
					}
				}
			}
			$links[0]['title'] = '继续修改';
			$links[0]['href'] = get_link("dosubmit");
			$links[1]['title'] = '详情页';
			$links[1]['href'] = get_link("a").'&amp;a=xiangqing';
			$links[2]['title'] = '会员列表';
			$links[2]['href'] = get_link("a|userid");
			$links[3]['title'] = '返回后台首页';
			$links[3]['href'] = $_admin_indexurl;
			$wheresql = "userid = ".intval($_GET['userid']);
			$row = $db -> getone("select * from ".table('huiyuan')." WHERE ".$wheresql);
			if (empty($row)) {
				$links[0]['href'] = '-1';
				showmsg("系统提醒", "你要修改的会员ID:{$_GET['userid']}不存在！");
			}
			
			unset($info['userid']); //不可修改会员ID
			unset($info['username']); //不可修改会员名称
			if ($info['username']){
				if ($db -> getone("select * from ".table('huiyuan')." WHERE userid !=".intval($_GET['userid'])." AND username = '{$info['username']}'")){
					showmsg("系统提醒", "用户名已存在，无法修改！",$links);}
			}
			if ($info['email']){
				if ($db -> getone("select * from ".table('huiyuan')." WHERE userid !=".intval($_GET['userid'])." AND email = '{$info['email']}'")){
				showmsg("系统提醒", "邮箱已被注册，无法修改！",$links);}
			}
			if ($info['mobile']){
				if ($db -> getone("select * from ".table('huiyuan')." WHERE userid !=".intval($_GET['userid'])." AND mobile = '{$info['mobile']}'")){
				showmsg("系统提醒", "手机号码已被注册，无法修改！",$links);}
			}
			//ucenter更新用户资料
			kf_class::ucenter();
			if (UC_USE == '1'){
				if ($row['ucuserid'] > 0){
					$ucresult = uc_user_edit($row['username'], trim($_POST['password']), trim($_POST['password']), $info['email'], 1);
					if($ucresult == -1) {
						showmsg("系统提示:UCenter", "旧密码不正确！", $links);
					} elseif($ucresult == -4) {
						showmsg("系统提示:UCenter", "Email 格式有误！", $links);
					} elseif($ucresult == -5) {
						showmsg("系统提示:UCenter", "Email 不允许注册！", $links);
					} elseif($ucresult == -6) {
						showmsg("系统提示:UCenter", "该 Email 已经被注册！", $links);
					}
				}
			}
			//
			if (trim($_POST['password'])) $info['password'] = md5s(trim($_POST['password']));
			$modellistarr = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_moxing.php');
			if ($info) updatetable(table('huiyuan'), $info, $wheresql);
			if ($detail) updatetable_detai(table('huiyuan_diy_'.$modellistarr[$row['modelid']]['tablename']), $detail, intval($_GET['userid']));
			admin_log("修改会员资料ID{$_GET['userid']}。", $admin_val['name']);
			showmsg("系统提醒", "修改成功！", $links);
		}
		break;
	case 'touxiang': //头像
		$wheresql = "userid = ".intval($_GET['userid']);
		$row = $db -> getone("select * from ".table('huiyuan')." WHERE ".$wheresql);
		if (empty($row)) showmsg("系统提醒", "你要修改的会员ID:{$_GET['userid']}不存在！");
		if($_POST['dosubmit']) {
			kf_class::run_sys_func('upload');
			$avatardir = getavatar($row['userid'],1);
			$pdefault = $row['userid'].'_default.gif';
			$avatar = '0'; $_fileret = false;
			if ($_FILES['shangchuan']['name']){
				$_file_size = intval(ini_get('upload_max_filesize')*1024);
				$_fileret = _asUpFiles($avatardir, 'shangchuan', $_file_size, 'gif/jpg/jpeg/png', $pdefault, true);
			}elseif (get_extension($_POST['wangluo']) == 'jpg' || get_extension($_POST['wangluo']) == 'gif' || get_extension($_POST['wangluo']) == 'jpeg' || get_extension($_POST['wangluo']) == 'png'){
				$_fileret = get_file($_POST['wangluo'],$avatardir,$pdefault);
			}
			if ($_fileret){
				makethumb($avatardir.$pdefault, $avatardir, 180, 180, 0, $row['userid'].'_180.gif');
				makethumb($avatardir.$pdefault, $avatardir, 90, 90, 0, $row['userid'].'_90.gif');
				makethumb($avatardir.$pdefault, $avatardir, 45, 45, 0, $row['userid'].'_45.gif');
				makethumb($avatardir.$pdefault, $avatardir, 30, 30, 0, $row['userid'].'_30.gif');
				$avatar = '1';
			}
			$links[0]['title'] = '继续修改';
			$links[0]['href'] = get_link("dosubmit").'';
			$links[1]['title'] = '详情页';
			$links[1]['href'] = get_link("a").'&amp;a=xiangqing';
			$links[2]['title'] = '会员列表';
			$links[2]['href'] = get_link("a|userid");
			$links[3]['title'] = '返回后台首页';
			$links[3]['href'] = $_admin_indexurl;
			if ($avatar == '1'){
				//ucenter修改用户头像
				kf_class::ucenter();
				if (UC_USE == '1'){
					if ($row['ucuserid'] > 0){
						$_avauculr = UC_API.'/avatar_kuaifan.php?uid='.$row['ucuserid'].'&wangluo='.urlencode(getavatar($row['userid'], 'default'));
						dfopen($_avauculr);
					}
				}
				//
				$db->query("UPDATE ".table('huiyuan')." SET avatar='1' WHERE ".$wheresql);
				admin_log("修改会员头像ID{$_GET['userid']}。", $admin_val['name']);
				showmsg("系统提醒", "保存成功！", $links);
			}else{
				showmsg("系统提醒", "保存失败！", $links);
			}
		}
		if($_GET['dosubmit']) {
			$links[0]['title'] = '继续修改';
			$links[0]['href'] = get_link("dosubmit").'';
			$links[1]['title'] = '详情页';
			$links[1]['href'] = get_link("a").'&amp;a=xiangqing';
			$links[2]['title'] = '会员列表';
			$links[2]['href'] = get_link("a|userid");
			$links[3]['title'] = '返回后台首页';
			$links[3]['href'] = $_admin_indexurl;
			//ucenter删除用户头像
			kf_class::ucenter();
			if (UC_USE == '1'){
				if (!empty($row['ucuserid'])) uc_user_deleteavatar($row['ucuserid']);
			}
			//
			del_touxiang($row['userid']);
			admin_log("删除会员头像ID{$_GET['userid']}。", $admin_val['name']);
			showmsg("系统提醒", "操作成功！", $links);
		}
		
		if ($row['avatar']){
			$avatar = getavatar($row['userid']);
		}else{
			$avatar = array();
		}
		$smarty->assign('avatar', $avatar);
		break;
	case 'suoding': //锁定
		$links[0]['title'] = '详情页';
		$links[0]['href'] = get_link("a").'&amp;a=xiangqing';
		$links[1]['title'] = '会员列表';
		$links[1]['href'] = get_link("a|userid");
		$links[2]['title'] = '返回后台首页';
		$links[2]['href'] = $_admin_indexurl;
		$wheresql = "userid = ".intval($_GET['userid']);
		$db->query("UPDATE ".table('huiyuan')." SET islock='1' WHERE ".$wheresql);
		admin_log("锁定会员ID{$_GET['userid']}。", $admin_val['name']);
		showmsg("系统提醒", "操作成功！", $links);
		break;
	case 'jiesuo': //解锁
		$links[0]['title'] = '详情页';
		$links[0]['href'] = get_link("a").'&amp;a=xiangqing';
		$links[1]['title'] = '会员列表';
		$links[1]['href'] = get_link("a|userid");
		$links[2]['title'] = '返回后台首页';
		$links[2]['href'] = $_admin_indexurl;
		$wheresql = "userid = ".intval($_GET['userid']);
		$db->query("UPDATE ".table('huiyuan')." SET islock='0' WHERE ".$wheresql);
		admin_log("解锁会员ID{$_GET['userid']}。", $admin_val['name']);
		showmsg("系统提醒", "操作成功！", $links);
		break;
	case 'shanchu': //删除
		$wheresql = "userid = ".intval($_GET['userid']);
		$row = $db -> getone("select * from ".table('huiyuan')." WHERE ".$wheresql);
		if (empty($row)) showmsg("系统提醒", "你要操作的会员ID:{$_GET['userid']}不存在！");
		if($_GET['dosubmit']) {
			//ucenter删除用户
			kf_class::ucenter();
			if (UC_USE == '1'){
				if (!empty($row['ucuserid'])) uc_user_delete($row['ucuserid']);
			}
			//删除主数据
			$modellistarr = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_moxing.php');
			$db->query("Delete from ".table('huiyuan')." WHERE ".$wheresql);
			$db->query("Delete from ".table('huiyuan_diy_'.$modellistarr[$row['modelid']]['tablename'])." WHERE ".$wheresql);
			$db->query("Delete from ".table('huiyuan_vip')." WHERE ".$wheresql);
			$links[0]['title'] = '会员列表';
			$links[0]['href'] = get_link("a|userid|dosubmit");
			$links[1]['title'] = '返回后台首页';
			$links[1]['href'] = $_admin_indexurl;
			admin_log("删除会员ID{$_GET['userid']}。", $admin_val['name']);
			showmsg("系统提醒", "删除成功！", $links);
		}
		$links[0]['title'] = '详情页';
		$links[0]['href'] = get_link("a").'&amp;a=xiangqing';
		$links[1]['title'] = '会员列表';
		$links[1]['href'] = get_link("a|userid");
		$links[2]['title'] = '返回后台首页';
		$links[2]['href'] = $_admin_indexurl;
		showmsg("系统提醒", "确定删除此会员相关的所有信息！<br/><a href='".get_link("dosubmit")."&amp;dosubmit=1'>确定删除</a>", $links);
		break;
	case 'xunzhang': //勋章
		if ($_GET['z']) {
			$templatefile = $__templatefile."/".$_GET['z'];
		}else{
			$templatefile = $__templatefile."/index";
		}
		//添加、修改分类
		if ($_GET['z'] == 'addfl') {
			if ($_GET['id'] > 0){
				$row = $db -> getone("select * from ".table('huiyuan_xunzhang')." WHERE `type`='fenlei' AND `id`=".intval($_GET['id']));
				if (empty($row)) showmsg("系统提醒", "分类不存在！");
				$row['setting'] = string2array($row['setting']);
				$smarty->assign('row', $row);
				$_title = '修改';
				$smarty->assign('title', $_title.'分类');
			}else{
				$_title = '添加';
				$smarty->assign('title', $_title.'分类');
			}
			if($_POST['dosubmit']) {
				if (strlen($_POST['title']) < 2 || empty($_POST['title'])) showmsg("系统提醒", "请输入名称！");
				$_arr = array();
				$_arr['type'] = 'fenlei';
				$_arr['title'] = htmlspecialchars($_POST['title']);
				$_arr['setting']['body'] = htmlspecialchars($_POST['body']);
				$_arr['setting'] = array2string($_arr['setting']);
				if ($_GET['id'] > 0){
					$_row = updatetable(table('huiyuan_xunzhang'), $_arr, '`id`='.intval($_GET['id']));
				}else{
					$_arr['intime'] = SYS_TIME;
					$_row = inserttable(table('huiyuan_xunzhang'), $_arr);
				}
				if (!$_row){
					showmsg("系统提醒", $_title."失败，请稍后再试！");
				}else{
					$links[0]['title'] = '继续'.$_title;
					$links[0]['href'] = get_link("dosubmit");
					$links[1]['title'] = '返回分类列表';
					$links[1]['href'] = get_link("dosubmit|z").'&amp;z=fenlei';
					admin_log($_title."勋章分类{$_arr['title']}。", $admin_val['name']);
					showmsg("系统提醒", $_title."成功！", $links);
				}
			}
		}
		//添加、修改勋章
		if ($_GET['z'] == 'add') {
			if ($_GET['id'] > 0){
				$row = $db -> getone("select * from ".table('huiyuan_xunzhang')." WHERE `type`='xunzhang' AND `id`=".intval($_GET['id']));
				if (empty($row)) showmsg("系统提醒", "勋章不存在！");
				$row['setting'] = string2array($row['setting']);
				if (file_exists(KF_ROOT_PATH.'uploadfiles/avatar/xunzhang/'.$row['id'].'.gif')) {
					$row['img'] = $_CFG['site_dir'].'uploadfiles/avatar/xunzhang/'.$row['id'].'.gif';
				}else{
					$row['img'] = '';
				}
				$smarty->assign('row', $row);
				$_title = '修改';
				$smarty->assign('title', $_title.'勋章');
			}else{
				$_title = '添加';
				$smarty->assign('title', $_title.'勋章');
			}
			if($_POST['dosubmit']) {
				$row = $db -> getone("select * from ".table('huiyuan_xunzhang')." WHERE `type`='fenlei' AND `id`=".intval($_POST['catid']));
				if (empty($row)) showmsg("系统提醒", "分类不存在！");
				if (strlen($_POST['title']) < 2 || empty($_POST['title'])) showmsg("系统提醒", "请输入名称！");
				$_arr = array();
				$_arr['type'] = 'xunzhang';
				$_arr['catid'] = intval($_POST['catid']);
				$_arr['title'] = htmlspecialchars($_POST['title']);
				$_arr['setting']['body'] = htmlspecialchars($_POST['body']);
				$_arr['setting']['catid_cn'] = $row['title'];
				$_arr['setting'] = array2string($_arr['setting']);
				if ($_GET['id'] > 0){
					$_row = updatetable(table('huiyuan_xunzhang'), $_arr, '`id`='.intval($_GET['id']));
					$_id = $_GET['id'];
				}else{
					$_arr['intime'] = SYS_TIME;
					$_row = inserttable(table('huiyuan_xunzhang'), $_arr, true);
					$_id = $_row;
				}
				if (!$_row){
					showmsg("系统提醒", $_title."失败，请稍后再试！");
				}else{
					if ($_FILES['xunzhang']['name']){
						kf_class::run_sys_func('upload');
						//上传格式
						$_file_allowext = "gif|jpg|jpeg|png|bmp";
						$_file_allowext = str_replace('|', '/', $_file_allowext);
						$_file_allowext = str_replace(',', '/', $_file_allowext);
						//上传大小
						$_file_size = intval(ini_get('upload_max_filesize')*1024);
						//上传路径
						$up_dir = "uploadfiles/avatar/xunzhang/";
						make_dir('./'.$up_dir);
						_asUpFiles('./'.$up_dir, 'xunzhang', $_file_size, $_file_allowext, $_id.'.gif', true);
					}
					$links[0]['title'] = '继续'.$_title;
					$links[0]['href'] = get_link("dosubmit");
					$links[1]['title'] = '返回勋章列表';
					$links[1]['href'] = get_link("dosubmit|z").'&amp;z=index';
					admin_log($_title."勋章{$_arr['title']}。", $admin_val['name']);
					showmsg("系统提醒", $_title."成功！", $links);
				}
			}
		}
		//添加、修改颁发
		if ($_GET['z'] == 'addhy') {
			if ($_GET['id'] > 0){
				$row = $db -> getone("select * from ".table('huiyuan_xunzhang')." WHERE `type`='huiyuan' AND `id`=".intval($_GET['id']));
				if (empty($row)) showmsg("系统提醒", "记录不存在！");
				$row['setting'] = string2array($row['setting']);
				if (file_exists(KF_ROOT_PATH.'uploadfiles/avatar/xunzhang/'.$row['catid'].'.gif')) {
					$row['img'] = $_CFG['site_dir'].'uploadfiles/avatar/xunzhang/'.$row['catid'].'.gif';
				}else{
					$row['img'] = '';
				}
				$smarty->assign('row', $row);
				$_title = '修改';
				$smarty->assign('title', $_title.'记录');
			}else{
				$_title = '颁发';
				$smarty->assign('title', $_title.'勋章');
			}
			if($_POST['dosubmit']) {
				$row = $db -> getone("select * from ".table('huiyuan_xunzhang')." WHERE `type`='xunzhang' AND `id`=".intval($_POST['catid']));
				if (empty($row)) showmsg("系统提醒", "选择的勋章不存在！");
				if ($_POST['dataid'] < 1) showmsg("系统提醒", "请输入会员ID！");
				$huiy = $db -> getone("select * from ".table('huiyuan')." WHERE `userid`=".intval($_POST['dataid']));
				if (empty($huiy)) showmsg("系统提醒", "你输入的会员ID错误！");
				$_arr = array();
				$_arr['type'] = 'huiyuan';
				$_arr['catid'] = intval($_POST['catid']);
				$_arr['title'] = $row['title'];
				$_arr['setting']['body'] = htmlspecialchars($_POST['body']);
				$_arr['setting']['userid'] = $huiy['userid'];
				$_arr['setting']['username'] = $huiy['username'];
				$_arr['setting']['nickname'] = $huiy['nickname']?$huiy['nickname']:$huiy['username'];
				$_arr['setting'] = array2string($_arr['setting']);
				if ($_GET['id'] > 0){
					$_row = updatetable(table('huiyuan_xunzhang'), $_arr, '`id`='.intval($_GET['id']));
				}else{
					$row = $db -> getone("select * from ".table('huiyuan_xunzhang')." WHERE `type`='huiyuan' AND `dataid`=".intval($_POST['dataid'])." AND `catid`=".intval($_POST['catid']));
					if (!empty($row)) showmsg("系统提醒", "该会员已经有这枚勋章！");
					$_arr['dataid'] = intval($_POST['dataid']);
					$_arr['intime'] = SYS_TIME;
					$_row = inserttable(table('huiyuan_xunzhang'), $_arr);
				}
				set_xunzhang(intval($_POST['dataid']));
				if (!$_row){
					showmsg("系统提醒", $_title."失败，请稍后再试！");
				}else{
					$links[0]['title'] = '继续'.$_title;
					$links[0]['href'] = get_link("dosubmit");
					$links[1]['title'] = '返回颁发列表';
					$links[1]['href'] = get_link("dosubmit|z").'&amp;z=list';
					admin_log($_title."会员勋章{$_arr['title']}。", $admin_val['name']);
					showmsg("系统提醒", $_title."成功！", $links);
				}
			}
		}
		//删除勋章图片
		if ($_GET['z'] == 'delfile') {
			@unlink(KF_ROOT_PATH.'uploadfiles/avatar/xunzhang/'.intval($_GET['id']).'.gif');
			showmsg("系统提醒", "操作完成！");
		}
		//删除勋章
		if ($_GET['z'] == 'del') {
			$row = $db -> getone("select * from ".table('huiyuan_xunzhang')." WHERE `type`='xunzhang' AND `id`=".intval($_GET['id']));
			if (empty($row)) showmsg("系统提醒", "勋章不存在！");
			if($_GET['dosubmit']) {
				@unlink(KF_ROOT_PATH.'uploadfiles/avatar/xunzhang/'.$row['id'].'.gif');
				// 删除会员勋章
				$rowhy = $db -> getall("select * from ".table('huiyuan_xunzhang')." WHERE `type`='huiyuan' AND `catid`=".intval($row['id']));
				foreach($rowhy as $_vv) {
					$db->query("Delete from ".table('huiyuan_xunzhang')." WHERE `id`=".$_vv['id']);
					set_xunzhang($_vv['dataid']);
				}
				$db->query("Delete from ".table('huiyuan_xunzhang')." WHERE `id`=".$row['id']);
				$links[0]['title'] = '返回列表';
				$links[0]['href'] = get_link('z|id')."&amp;z=index";
				admin_log("删除勋章{$row['title']}。", $admin_val['name']);
				showmsg("系统提醒", "删除成功！", $links);
			}
			$links[0]['title'] = '确定删除';
			$links[0]['href'] = get_link()."&amp;dosubmit=1";
			$links[1]['title'] = '返回修改页面';
			$links[1]['href'] = get_link("z").'&amp;z=add';
			showmsg("系统提醒", "确定删除并且不可恢复？", $links);
		}
		//删除勋章分类
		if ($_GET['z'] == 'delfl') {
			$row = $db -> getone("select * from ".table('huiyuan_xunzhang')." WHERE `type`='fenlei' AND `id`=".intval($_GET['id']));
			if (empty($row)) showmsg("系统提醒", "勋章分类不存在！");
			if($_GET['dosubmit']) {
				//删除勋章
				$rowfl = $db -> getall("select * from ".table('huiyuan_xunzhang')." WHERE `type`='xunzhang' AND `catid`=".intval($row['id']));
				foreach($rowfl as $_v) {
					@unlink(KF_ROOT_PATH.'uploadfiles/avatar/xunzhang/'.$_v['id'].'.gif');
					// 删除会员勋章
					$rowhy = $db -> getall("select * from ".table('huiyuan_xunzhang')." WHERE `type`='huiyuan' AND `catid`=".intval($_v['id']));
					foreach($rowhy as $_vv) {
						$db->query("Delete from ".table('huiyuan_xunzhang')." WHERE `id`=".$_vv['id']);
						set_xunzhang($_vv['dataid']);
					}
				}
				$db->query("Delete from ".table('huiyuan_xunzhang')." WHERE `id`=".$row['id']);
				$db->query("Delete from ".table('huiyuan_xunzhang')." WHERE `catid`=".$row['id']);
				$links[0]['title'] = '返回列表';
				$links[0]['href'] = get_link('z|id')."&amp;z=fenlei";
				admin_log("删除勋章分类{$row['title']}。", $admin_val['name']);
				showmsg("系统提醒", "删除成功！", $links);
			}
			$links[0]['title'] = '确定删除';
			$links[0]['href'] = get_link()."&amp;dosubmit=1";
			$links[1]['title'] = '返回修改页面';
			$links[1]['href'] = get_link("z").'&amp;z=addfl';
			showmsg("系统提醒", "确定删除分类及分类下的勋章并且不可恢复？", $links);
		}
		//删除颁发记录
		if ($_GET['z'] == 'delhy') {
			$row = $db -> getone("select * from ".table('huiyuan_xunzhang')." WHERE `type`='huiyuan' AND `id`=".intval($_GET['id']));
			if (empty($row)) showmsg("系统提醒", "记录不存在！");
			if($_GET['dosubmit']) {
				// 删除会员勋章
				$db->query("Delete from ".table('huiyuan_xunzhang')." WHERE `id`=".$row['id']);
				set_xunzhang($row['dataid']);
				$links[0]['title'] = '返回列表';
				$links[0]['href'] = get_link('z|id')."&amp;z=list";
				admin_log("删除会员(ID:{$row['dataid']})颁发记录{$row['title']}。", $admin_val['name']);
				showmsg("系统提醒", "删除成功！", $links);
			}
			$links[0]['title'] = '确定删除';
			$links[0]['href'] = get_link()."&amp;dosubmit=1";
			$links[1]['title'] = '返回修改页面';
			$links[1]['href'] = get_link("z").'&amp;z=addhy';
			showmsg("系统提醒", "确定删除并且不可恢复？", $links);
		}
		$smarty->assign('URL', $_admin_indexurl.'&amp;c=huiyuan&amp;a=xunzhang');
		break;
	case 'updatesid': //一键更新会员sid
		if($_GET['dosubmit']) {
			if($_GET['goid']) {
				$row = $db -> getall("select * from ".table('huiyuan')." WHERE `userid` >=".intval($_GET['goid'])." ORDER BY userid asc LIMIT 0, 100010");
			}else{
				$row = $db -> getall("select * from ".table('huiyuan')." ORDER BY userid asc LIMIT 0, 100010");
			}
			$_i = 0;
			foreach($row as $_v) {
				$db->query("UPDATE ".table('huiyuan')." SET `usersid`='".generate_password(24)."' WHERE `userid`=".$_v['userid']);
				$_i++;
				if ($_i > 100000){
					$links[0]['title'] = '更新一下部分';
					$links[0]['href'] = get_link("goid")."&amp;goid=".$_v['userid'];
					showmsg("系统提醒", "会员数多,自动更新中转...", $links, $links[0]['href'], 1);
				}
			}
			$links[0]['title'] = '返回会员列表';
			$links[0]['href'] = get_link("a|goid").'&amp;a=index';
			$links[1]['title'] = '返回后台首页';
			$links[1]['href'] = $_admin_indexurl;
			admin_log("一键更新会员sid。", $admin_val['name']);
			showmsg("系统提醒", "一键更新会员sid完成！", $links);
		}
		$links[0]['title'] = '确定更新';
		$links[0]['href'] = get_link()."&amp;dosubmit=1";
		$links[1]['title'] = '返回会员列表';
		$links[1]['href'] = get_link("a").'&amp;a=index';
		$links[2]['title'] = '返回后台首页';
		$links[2]['href'] = $_admin_indexurl;
		showmsg("系统提醒", "确定一键更新所有会员的sid身份标识？", $links);
		break;
	default: //会员列表
		//会员组
		$grouplist = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_zu.php');
		foreach($grouplist as $k=>$v) {
			$grouplist[$k] = $v['name'];
		}
		$smarty->assign('grouplist', $grouplist);
		//会员所属模型
		$modellistarr = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_moxing.php');
		foreach($modellistarr as $k=>$v) {
			$modellistarr[$k] = $v['title'];
		}
		$smarty->assign('modellistarr', $modellistarr);

}
?>