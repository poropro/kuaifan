<?php
/*
 * 内容管理
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

$__templatefile = $templatefile;

if ($_GET['a'] == 'moxing') {
	if ($_GET['add']) {
		$templatefile = $__templatefile."/add";
		if (!empty($_POST['dosubmit'])){
			if (!$_POST['type']) showmsg("系统提醒", "请选择正确的“模型类型”！");
			if (!$_POST['title']) showmsg("系统提醒", "“模型名称”不能为空！");
			if (!$_POST['tablename']) showmsg("系统提醒", "“模型表键名”不能为空！");
			if (is_english($_POST['tablename'],1,1) != '3') showmsg("系统提醒", "“模型表键名”只能是数字或英文或下划线组合并且是字母开头！");
			if ($db -> getone("select * from ".table('neirong_moxing')." WHERE tablename='{$_POST['tablename']}' LIMIT 1")) {
				showmsg("系统提醒", "模型表键名“{$_POST['tablename']}”已经存在,不可重复添加！");	}
				$addmoxing = array();
				$addmoxing['type'] = trim($_POST['type']);
				$addmoxing['type_cn'] = moxing_type($addmoxing['type']);
				$addmoxing['title'] = trim($_POST['title']);
				$addmoxing['body'] = trim($_POST['body']);
				$addmoxing['tablename'] = trim($_POST['tablename']);
				$addmoxing['default_style'] = trim($_POST['default_style'])?trim($_POST['default_style']):rtrim($_CFG['template_dir'],'/');
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
				$modelid = inserttable(table('neirong_moxing'),$addmoxing, true);
				if (empty($modelid)){
					showmsg("系统提醒", "添加失败,请稍后再试！", $links);
				}else{
					//添加字段
					$model_sql = file_get_contents(KF_ROOT_PATH.'kuaifan/resources/moxing.sql');
					$model_sql = str_replace('$basic_table', table('diy_'.$_POST['tablename']), $model_sql);
					$model_sql = str_replace('$table_data', table('diy_'.$_POST['tablename'].'_data'), $model_sql);
					$model_sql = str_replace('$table_model_field', table('neirong_moxing_ziduan'), $model_sql);
					$model_sql = str_replace('$modelid',$modelid,$model_sql); sql_execute($model_sql);
					//论坛模型
					if ($addmoxing['type'] == 'bbs'){
						$db->query("UPDATE ".table('neirong_moxing_ziduan')." SET `hide`='0' WHERE `field`='subtitle' AND `modelid`='{$modelid}'");
						$db->query("UPDATE ".table('neirong_moxing_ziduan')." SET `hide`='0' WHERE `field`='jinghua' AND `modelid`='{$modelid}'");
						$db->query("UPDATE ".table('neirong_moxing_ziduan')." SET `hide`='0' WHERE `field`='dingzhi' AND `modelid`='{$modelid}'");
						$db->query("UPDATE ".table('neirong_moxing_ziduan')." SET `isadd`='1' WHERE `field`='tongzhiwo' AND `modelid`='{$modelid}'");
					}
					//更新缓存
					cache_field($modelid);
					refresh_cache_all('neirong_moxing');
					admin_log("添加了模型ID{$modelid}|{$_POST['title']}|{$_POST['tablename']}。", $admin_val['name']);
					showmsg("系统提醒", "添加成功！", $links);
				}
		}
		$_path = DIR_PATH.'neirong/';
		$_path = KF_ROOT_PATH.str_replace('/', DIRECTORY_SEPARATOR, $_path);
		$categoryarr = $listarr = $showarr = array(); 
		$_tplrarr = array('.html.tpl', '.html5.tpl', '.pad.tpl', '.web.tpl', '.tpl');
		$_patharr = glob($_path . '{category_*.tpl,category.tpl}', GLOB_BRACE);
		foreach ($_patharr as $_val) {
			$_val = basename($_val);
			$_val = str_replace($_tplrarr, '', $_val);
			$categoryarr[$_val] = $_val;
		}
		$_patharr = glob($_path . '{list_*.tpl,list.tpl}', GLOB_BRACE);
		foreach ($_patharr as $_val) {
			$_val = basename($_val);
			$_val = str_replace($_tplrarr, '', $_val);
			$listarr[$_val] = $_val;
		}
		$_patharr = glob($_path . '{show_*.tpl,show.tpl}', GLOB_BRACE);
		foreach ($_patharr as $_val) {
			$_val = basename($_val);
			$_val = str_replace($_tplrarr, '', $_val);
			$showarr[$_val] = $_val;
		}
		$smarty->assign('categoryarr', $categoryarr);
		$smarty->assign('listarr', $listarr);
		$smarty->assign('showarr', $showarr);
	}elseif ($_GET['edit']) {
		$templatefile = $__templatefile."/edit";
		$__edit = $db -> getone("select * from ".table('neirong_moxing')." where id = ".intval($_GET['edit'])." LIMIT 1");
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
			$addmoxing['default_style'] = trim($_POST['default_style'])?trim($_POST['default_style']):rtrim($_CFG['template_dir'],'/');
			$addmoxing['category_template'] = trim($_POST['category_template'])?trim($_POST['category_template']):'category';
			$addmoxing['list_template'] = trim($_POST['list_template'])?trim($_POST['list_template']):'list';
			$addmoxing['show_template'] = trim($_POST['show_template'])?trim($_POST['show_template']):'show';
			$links[0]['title'] = '继续修改';
			$links[0]['href'] = get_link();
			$links[1]['title'] = '返回列表';
			$links[1]['href'] = get_link("edit");
			$links[2]['title'] = '返回后台首页';
			$links[2]['href'] = $_admin_indexurl;
			if (!updatetable(table('neirong_moxing'), $addmoxing, "id = ".intval($_GET['edit'])."")){
				showmsg("修改失败", "修改失败，网络繁忙请稍后再试！", $links);
			}else{
				cache_field($_GET['edit']);
				refresh_cache_all('neirong_moxing');
				admin_log("修改了模型ID{$_GET['edit']}|{$_POST['title']}|{$__edit['tablename']}。", $admin_val['name']);
				showmsg("系统提醒", "修改成功！", $links);
			}
		}
		$_path = DIR_PATH.'neirong/';
		$_path = KF_ROOT_PATH.str_replace('/', DIRECTORY_SEPARATOR, $_path);
		$categoryarr = $listarr = $showarr = array(); 
		$_tplrarr = array('.html.tpl', '.html5.tpl', '.pad.tpl', '.web.tpl', '.tpl');
		$_patharr = glob($_path . '{category_*.tpl,category.tpl}', GLOB_BRACE);
		foreach ($_patharr as $_val) {
			$_val = basename($_val);
			$_val = str_replace($_tplrarr, '', $_val);
			$categoryarr[$_val] = $_val;
		}
		$_patharr = glob($_path . '{list_*.tpl,list.tpl}', GLOB_BRACE);
		foreach ($_patharr as $_val) {
			$_val = basename($_val);
			$_val = str_replace($_tplrarr, '', $_val);
			$listarr[$_val] = $_val;
		}
		$_patharr = glob($_path . '{show_*.tpl,show.tpl}', GLOB_BRACE);
		foreach ($_patharr as $_val) {
			$_val = basename($_val);
			$_val = str_replace($_tplrarr, '', $_val);
			$showarr[$_val] = $_val;
		}
		$smarty->assign('categoryarr', $categoryarr);
		$smarty->assign('listarr', $listarr);
		$smarty->assign('showarr', $showarr);
	}elseif ($_GET['start']) {
		$addmoxing = array();
		$addmoxing['mode'] = '0';
		$links[0]['title'] = '返回列表';
		$links[0]['href'] = get_link("start");
		$links[1]['title'] = '返回后台首页';
		$links[1]['href'] = $_admin_indexurl;
		if (!updatetable(table('neirong_moxing'), $addmoxing, "id = ".intval($_GET['start'])."")){
			showmsg("启用失败", "启用失败，网络繁忙请稍后再试！", $links);
		}else{
			cache_field($_GET['start']);
			admin_log("启用了模型ID{$_GET['start']}。", $admin_val['name']);
			showmsg("系统提醒", "启用成功！", $links);
		}
	}elseif ($_GET['end']) {
		$addmoxing = array();
		$addmoxing['mode'] = '1';
		$links[0]['title'] = '返回列表';
		$links[0]['href'] = get_link("end");
		$links[1]['title'] = '返回后台首页';
		$links[1]['href'] = $_admin_indexurl;
		if (!updatetable(table('neirong_moxing'), $addmoxing, "id = ".intval($_GET['end'])."")){
			showmsg("禁用失败", "禁用失败，网络繁忙请稍后再试！", $links);
		}else{
			cache_field($_GET['end']);
			admin_log("禁用了模型ID{$_GET['end']}。", $admin_val['name']);
			showmsg("系统提醒", "禁用成功！", $links);
		}
	}elseif ($_GET['del']) {
		$__del = $db -> getone("select * from ".table('neirong_moxing')." where id = ".intval($_GET['del'])." LIMIT 1");
		if (!$__del){
			showmsg("系统提醒", "要删除的模型ID".intval($_GET['del'])."不存在！");
		}
		$___del = $db -> getone("select * from ".table('diy_'.$__del['tablename'])." LIMIT 1");
		if (!empty($___del)){
			showmsg("系统提醒", "要删除的模型ID".intval($_GET['del'])."还有内容，不可删除！");
		}
		$___del = $db -> getone("select * from ".table('neirong_lanmu')." WHERE modelid=".intval($_GET['del'])." LIMIT 1");
		if (!empty($___del)){
			showmsg("系统提醒", "要删除的模型ID".intval($_GET['del'])."还有栏目，不可删除！");
		}
		if ($_GET['dosubmit']) {
			$links[0]['title'] = '返回列表';
			$links[0]['href'] = get_link("del|dosubmit");
			$links[1]['title'] = '返回后台首页';
			$links[1]['href'] = $_admin_indexurl;
			if (!$db->query("Delete from ".table('neirong_moxing')." where id = ".intval($_GET['del']))){
				showmsg("系统提醒", "删除失败,请稍后再试！", $links);
			}else{
				$db->query("DROP TABLE ".table('diy_'.$__del['tablename']));
				$db->query("DROP TABLE ".table('diy_'.$__del['tablename'].'_data'));
				$db->query("Delete from ".table('sousuo')." where modelid = ".intval($_GET['del']));
				$db->query("Delete from ".table('neirong_moxing_ziduan')." where modelid = ".intval($_GET['del']));
				cache_field_del(intval($_GET['del']));
				refresh_cache_all('neirong_moxing');
				admin_log("删除了模型ID{$_GET['del']}|{$__del['title']}|{$__del['tablename']}。", $admin_val['name']);
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
		$__field = $db -> getone("select * from ".table('neirong_moxing')." {$wheresql} LIMIT 1");
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
				$ziduandb = $db -> getone("select * from ".table('neirong_moxing_ziduan')." {$ziduandb} LIMIT 1");
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
				$addziduan['setting']['pathlist'] = trim($_POST['setting_pathlist']);
				$addziduan['setting']['uponethumb'] = trim($_POST['setting_uponethumb']);
				$addziduan['setting']['newline2br'] = trim($_POST['setting_newline2br']);
				$addziduan['setting']['input_kuan'] = $_POST['setting_input_kuan'];
				$addziduan['setting']['input_gao'] = $_POST['setting_input_gao'];
				$addziduan['setting']['formtext'] = $_POST['setting_formtext'];
				$addziduan['minlength'] = intval($_POST['minlength']);
				$addziduan['maxlength'] = intval($_POST['maxlength']);
				$addziduan['listorder'] = intval($_POST['listorder']);
				$addziduan['isadd'] = intval($_POST['isadd']);
					
				$tablename = table('diy_'.$__field['tablename']);
				if ($addziduan['issystem'] == '0') $tablename = table('diy_'.$__field['tablename'].'_data');
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
				$ziduanid = inserttable(table('neirong_moxing_ziduan'),$addziduan, true);
				if ($ziduanid > 0){
					//更新缓存
					cache_field($_GET['field']);
					//更新字段附件为已用
					if ($addziduan['type'] == 'images' || $addziduan['type'] == 'downfile'){
						$db->query("UPDATE ".table('neirong_fujian')." SET of='1' WHERE modelid='".$addziduan['modelid']."' AND field='".$addziduan['field']."'");
					}
					admin_log("添加字段{$addziduan['field']}到{$__field['tablename']}。", $admin_val['name']);
					showmsg("系统提醒", "添加成功！", $links);
				}else{
					showmsg("系统提醒", "添加失败,请稍后再试！", $links);
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
			if (!updatetable(table('neirong_moxing_ziduan'), $addmoxing, "id = ".intval($_GET['fstart'])."")){
				showmsg("启用失败", "启用失败，网络繁忙请稍后再试！", $links);
			}else{
				cache_field($_GET['field']);
				admin_log("启用了字段ID{$_GET['fstart']}。", $admin_val['name']);
				showmsg("系统提醒", "启用成功！", $links);
			}
		}elseif ($_GET['fend']) {
			$addmoxing = array();
			$addmoxing['status'] = '1';
			$links[0]['title'] = '返回列表';
			$links[0]['href'] = get_link("fend");
			$links[1]['title'] = '返回后台首页';
			$links[1]['href'] = $_admin_indexurl;
			if (!updatetable(table('neirong_moxing_ziduan'), $addmoxing, "id = ".intval($_GET['fend'])."")){
				showmsg("禁用失败", "禁用失败，网络繁忙请稍后再试！", $links);
			}else{
				cache_field($_GET['field']);
				admin_log("禁用了字段ID{$_GET['fend']}。", $admin_val['name']);
				showmsg("系统提醒", "禁用成功！", $links);
			}
		}elseif ($_GET['fdel']) {
			$__fdel = $db -> getone("select * from ".table('neirong_moxing_ziduan')." where id = ".intval($_GET['fdel'])." LIMIT 1");
			if (!$__fdel){
				showmsg("系统提醒", "要删除的字段ID".intval($_GET['fdel'])."不存在！");
			}
			if ($_GET['dosubmit']) {
				$links[0]['title'] = '返回列表';
				$links[0]['href'] = get_link("fdel|dosubmit");
				$links[1]['title'] = '返回后台首页';
				$links[1]['href'] = $_admin_indexurl;
				if (!$db->query("Delete from ".table('neirong_moxing_ziduan')." where id = ".intval($_GET['fdel']))){
					showmsg("系统提醒", "删除失败,请稍后再试！", $links);
				}else{
					$table_name = table('diy_'.$__field['tablename']);
					if ($__fdel['issystem'] == '0') $table_name = table('diy_'.$__field['tablename'].'_data');
					$db->query("ALTER TABLE `{$table_name}` DROP `{$__fdel['field']}`;");
					cache_field($_GET['field']);
					//更新字段附件为未用
					if ($__fdel['type'] == 'images' || $__fdel['type'] == 'downfile'){
						$db->query("UPDATE ".table('neirong_fujian')." SET of='0' WHERE modelid='".intval($_GET['field'])."' AND field='".$__fdel['field']."'");
					}
					admin_log("删除了字段ID{$_GET['fdel']}|{$__fdel['name']}。", $admin_val['name']);
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
			$__ziduan = $db -> getone("select * from ".table('neirong_moxing_ziduan')." {$wheresql} LIMIT 1");
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
				$addziduan['setting']['fieldtype'] = trim($_POST['setting_fieldtype']);
				$addziduan['setting']['outputtype'] = trim($_POST['setting_outputtype']);
				$addziduan['setting']['minnumber'] = intval($_POST['setting_minnumber']);
				$addziduan['setting']['maxnumber'] = intval($_POST['setting_maxnumber']);
				$addziduan['setting']['decimaldigits'] = trim($_POST['setting_decimaldigits']);
				$addziduan['setting']['format'] = trim($_POST['setting_format']);
				$addziduan['setting']['upload_allowext'] = trim($_POST['setting_upload_allowext']);
				$addziduan['setting']['upload_number'] = trim($_POST['setting_upload_number']);
				$addziduan['setting']['downloadtype'] = trim($_POST['setting_downloadtype']);
				$addziduan['setting']['pathlist'] = trim($_POST['setting_pathlist']);
				$addziduan['setting']['uponethumb'] = trim($_POST['setting_uponethumb']);
				$addziduan['setting']['newline2br'] = trim($_POST['setting_newline2br']);
				$addziduan['setting']['input_kuan'] = $_POST['setting_input_kuan'];
				$addziduan['setting']['input_gao'] = $_POST['setting_input_gao'];
				$addziduan['setting']['formtext'] = $_POST['setting_formtext'];
				$addziduan['setting'] = array2string($addziduan['setting']);
				if ($__ziduan['del'] && $__ziduan['type']!="downfile") unset($addziduan['setting']);
				if (empty($addziduan['setting']) && $__ziduan['field']=='content'){
					$addziduan['setting']['nrbiaoqian'] = trim($_POST['setting_nrbiaoqian']);
					$addziduan['setting']['newline2br'] = trim($_POST['setting_newline2br']);
					$addziduan['setting']['input_kuan'] = $_POST['setting_input_kuan'];
					$addziduan['setting']['input_gao'] = $_POST['setting_input_gao'];
					$addziduan['setting']['tu_kuan'] = intval($_POST['setting_tu_kuan']);
					$addziduan['setting']['tu_gao'] = intval($_POST['setting_tu_gao']);
					$addziduan['setting'] = array2string($addziduan['setting']);
				}
				if (empty($addziduan['setting']) && $__ziduan['type']=='textarea'){
					$addziduan['setting']['newline2br'] = trim($_POST['setting_newline2br']);
					$addziduan['setting']['input_kuan'] = $_POST['setting_input_kuan'];
					$addziduan['setting']['input_gao'] = $_POST['setting_input_gao'];
					$addziduan['setting'] = array2string($addziduan['setting']);
				}
				if (empty($addziduan['setting']) && $__ziduan['type']=='pages'){
					$addziduan['setting']['defaultvalue'] = $_POST['setting_defaultvalue'];
					$addziduan['setting'] = array2string($addziduan['setting']);
				}
				$addziduan['minlength'] = intval($_POST['minlength']);
				$addziduan['maxlength'] = intval($_POST['maxlength']);
				$addziduan['listorder'] = intval($_POST['listorder']);
				$addziduan['isadd'] = intval($_POST['isadd']);
				$links[0]['title'] = '继续修改';
				$links[0]['href'] = get_link("dosubmit");
				$links[1]['title'] = '返回列表';
				$links[1]['href'] = get_link("fedit|dosubmit");
				$links[2]['title'] = '返回后台首页';
				$links[2]['href'] = $_admin_indexurl;
				$wheresql=" id=".intval($_GET['fedit'])."";
				if (!updatetable(table('neirong_moxing_ziduan'), $addziduan, $wheresql)){
					showmsg("系统提醒", "修改失败,请稍后再试！", $links);
				}else{
					//更新缓存
					cache_field($_GET['field']);
					admin_log("修改字段{$__ziduan['field']}。", $admin_val['name']);
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
			$forval = $__setting['format']=='Y-m-d H:i:s'?" selected=\"selected\"":"";
			$tempformat.= "<option value=\"Y-m-d H:i:s\"{$forval}>(24小时制)".date('Y-m-d H:i:s')."</option>";
			$forval = $__setting['format']=='Y-m-d Ah:i:s'?" selected=\"selected\"":"";
			$tempformat.= "<option value=\"Y-m-d Ah:i:s\"{$forval}>(12小时制)".date('Y-m-d h:i:s')."</option>";
			$forval = $__setting['format']=='Y-m-d H:i'?" selected=\"selected\"":"";
			$tempformat.= "<option value=\"Y-m-d H:i\"{$forval}>".date('Y-m-d H:i')."</option>";
			$forval = $__setting['format']=='Y-m-d'?" selected=\"selected\"":"";
			$tempformat.= "<option value=\"Y-m-d\"{$forval}>".date('Y-m-d')."</option>";
			$forval = $__setting['format']=='m-d'?" selected=\"selected\"":"";
			$tempformat.= "<option value=\"m-d\"{$forval}>".date('m-d')."</option>";
			$tempformat.= "</select>";
			$__setting['format'] = $tempformat;
			$smarty->assign('ziduan', $__ziduan);
			$smarty->assign('setting', $__setting);
		}elseif ($_GET['paixu']){
			$templatefile = $__templatefile."/paixu";
			$wheresql = " WHERE modelid = ".intval($_GET['field'])." and hide = 0";
			$sql = "select * from ".table('neirong_moxing_ziduan')." {$wheresql} ORDER BY listorder asc";
			$result = $db->query($sql);
			$__list = array();
			while($row = $db->fetch_array($result)){
				$__list[] = $row;
			}
			$smarty->assign('ziduan', $__list);
			if (!empty($_POST['dosubmit'])){
				foreach ($_POST as $_k => $_v){
					if (substr($_k, 0, 4) == 'pai_'){
						$db -> query("update ".table('neirong_moxing_ziduan')." set `listorder`=".intval($_v)." {$wheresql} AND id='".substr($_k, 4)."'");
					}
				}
				cache_field($_GET['field']);
				$links[0]['title'] = '继续修改';
				$links[0]['href'] = get_link("dosubmit");
				$links[1]['title'] = '返回列表';
				$links[1]['href'] = get_link("paixu|dosubmit");
				admin_log("修改模型字段排序，模型ID{$_GET['field']}。", $admin_val['name']);
				showmsg("系统提醒", "修改成功！", $links);
			}
		}else{
			$templatefile = $__templatefile."/index";
			$wheresql = " WHERE modelid = ".intval($_GET['field'])." and hide = 0";
			$sql = "select * from ".table('neirong_moxing_ziduan')." {$wheresql} ORDER BY listorder asc";
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
		$sql = "select * from ".table('neirong_moxing')." {$wheresql} ORDER BY id asc";
		$result = $db->query($sql);
		$__list = array();
		while($row = $db->fetch_array($result)){
			$__list[] = $row;
		}
		$smarty->assign('moxing', $__list);
	}
}elseif ($_GET['a'] == 'lanmu') {
	if ($_GET['add']) {
		if (!empty($_POST['dosubmit'])){
            $_titlearr = explode("///",$_POST['title']);
            $_letterarr = explode("///",$_POST['letter']);
            foreach ($_titlearr as $_kk => $_vv){
                if (!empty($_vv)){
                    $_POST['title'] = $_vv;
                    $_POST['letter'] = $_letterarr[$_kk];
                    if (empty($_POST['letter'])){
                        $_POST['letter'] = preg_replace("/[^a-z]*/", '', pinyinlanmu($_POST['title']));
                        $lanmudb = $db -> getone("select * from ".table('neirong_lanmu')." WHERE letter='".trim($_POST['letter'])."'");
                         if (!empty($lanmudb)) $_POST['letter'] = pinyinlanmu(0);
                    }
                    if (!$_POST['title']) showmsg("系统提醒", "“栏目名称”不能为空！");
                    if (!$_POST['letter']) showmsg("系统提醒", "“英文目录”不能为空！");
                    if (is_english($_POST['letter'],1,1) != '3') showmsg("系统提醒", "“英文目录”只能是数字或英文或下划线组合并且是字母开头！");
                    $lanmudb = "WHERE letter='".trim($_POST['letter'])."'";
                    $lanmudb = $db -> getone("select * from ".table('neirong_lanmu')." {$lanmudb} LIMIT 1");
                    if (!empty($lanmudb)) { showmsg("系统提醒", "“英文目录”已经存在，不可重复添加！"); }

                    $moxingdb = "WHERE id=".intval($_POST['modelid'])."";
                    $moxingdb = $db -> getone("select * from ".table('neirong_moxing')." {$moxingdb} LIMIT 1");
                    if (empty($moxingdb)) { showmsg("系统提醒", "请选择模型！"); }

                    $addlanmu = array();
                    $addlanmu['type'] = 1;
                    $addlanmu['title'] = trim($_POST['title']);
                    $addlanmu['module'] = $moxingdb['tablename'];
                    $addlanmu['module_cn'] = $moxingdb['title'];
                    $addlanmu['modelid'] = trim($_POST['modelid']);
                    $addlanmu['parentid'] = intval($_POST['parentid']);
                    $addlanmu['arrparentid'] = nr_shangjilanmu($_POST['parentid']);
                    $addlanmu['arrchildid'] = 0;
                    $addlanmu['url'] = '';
                    $addlanmu['body'] = trim($_POST['body']);
                    $addlanmu['items'] = 0;
                    $addlanmu['setting'] = array2string(array());
                    $addlanmu['listorder'] = intval($_POST['listorder']);
                    $addlanmu['letter'] = trim($_POST['letter']);
                    foreach ($_POST['setting'] as $_k => $_v){
                        if (!$_v) unset($_POST['setting'][$_k]);
                    }
					//属于栏目模型的内容审核通过后默认允许修改和删除
					if ($moxingdb['type'] == 'bbs'){
						$_POST['setting']['shenhehou'] = 1;
						$_POST['setting']['shenheshan'] = 1;
					}
					$addlanmu['setting'] = array2string($_POST['setting']);
                    $links[0]['title'] = '继续添加';
                    $links[0]['href'] = get_link("dosubmit");
                    $links[1]['title'] = '返回列表';
                    $links[1]['href'] = get_link("add|dosubmit");
                    $links[2]['title'] = '返回后台首页';
                    $links[2]['href'] = $_admin_indexurl;
                    $lanmuid = inserttable(table('neirong_lanmu'),$addlanmu, true);
                    if (empty($lanmuid)){
                        showmsg("系统提醒", "添加失败,请稍后再试！", $links);
                    }else{
                        nr_zijilanmu($_POST['parentid']);
                        refresh_cache_column($_CFG['site']);
                        admin_log("添加栏目{$addlanmu['title']}。", $admin_val['name']);
                    }
                }
			}
			showmsg("系统提醒", "添加成功！", $links);
		}
		$templatefile = $__templatefile."/add";
		$data = getcache(KF_ROOT_PATH.'caches/cache_neirong_moxing.php');
		$model_datas = array();
		foreach($data as $_k=>$_v) {
			$model_datas[$_v['id']] = $_v['title'];
		}
		kf_class::run_sys_class('form','',0);
		$__moxing = form::select($model_datas,'0','name="modelid'.fenmiao().'"',"请选择模型");
		$__lanmu = form::select_category('column/cache.'.$_CFG['site'], $_GET['add'], 'name="parentid'.fenmiao().'"', '≡ 作为一级栏目 ≡' , 0, -1);
		$smarty->assign('moxingsel', $__moxing);
		$smarty->assign('lanmusel', $__lanmu);
		//
		$_path = DIR_PATH.'neirong/';
		$_path = KF_ROOT_PATH.str_replace('/', DIRECTORY_SEPARATOR, $_path);
		$categoryarr = $listarr = $showarr = array('默认模型风格'=>''); 
		$_tplrarr = array('.html.tpl', '.html5.tpl', '.pad.tpl', '.web.tpl', '.tpl');
		$_patharr = glob($_path . '{category_*.tpl,category.tpl}', GLOB_BRACE);
		foreach ($_patharr as $_val) {
			$_val = basename($_val);
			$_val = str_replace($_tplrarr, '', $_val);
			$categoryarr[$_val] = $_val;
		}
		$_patharr = glob($_path . '{list_*.tpl,list.tpl}', GLOB_BRACE);
		foreach ($_patharr as $_val) {
			$_val = basename($_val);
			$_val = str_replace($_tplrarr, '', $_val);
			$listarr[$_val] = $_val;
		}
		$_patharr = glob($_path . '{show_*.tpl,show.tpl}', GLOB_BRACE);
		foreach ($_patharr as $_val) {
			$_val = basename($_val);
			$_val = str_replace($_tplrarr, '', $_val);
			$showarr[$_val] = $_val;
		}
		$smarty->assign('categoryarr', $categoryarr);
		$smarty->assign('listarr', $listarr);
		$smarty->assign('showarr', $showarr);
	}elseif ($_GET['edit']) {
		$wheresql = "id=".intval($_GET['edit'])."";
		$lanmudb = $db -> getone("select * from ".table('neirong_lanmu')." WHERE {$wheresql} LIMIT 1");
		if (!$lanmudb) { showmsg("系统提醒", "要修改的栏目ID".intval($_GET['edit'])."不存在！"); }
		if (!empty($_POST['dosubmit'])){
			if (!$_POST['title']) showmsg("系统提醒", "“栏目名称”不能为空！");
			if (intval($_POST['parentid']) == intval($_GET['edit'])) showmsg("系统提醒", "不能选择自己为上级栏目！");
			$addlanmu = array();
			$addlanmu['title'] = trim($_POST['title']);
			$addlanmu['parentid'] = intval($_POST['parentid']);
			$addlanmu['arrparentid'] = nr_shangjilanmu($_POST['parentid']);
			$addlanmu['body'] = trim($_POST['body']);
			$addlanmu['listorder'] = intval($_POST['listorder']);
			foreach ($_POST['setting'] as $_k => $_v){
				if (!$_v) unset($_POST['setting'][$_k]);
			}$addlanmu['setting'] = array2string($_POST['setting']);
			$links[0]['title'] = '继续修改';
			$links[0]['href'] = get_link("dosubmit");
			$links[1]['title'] = '返回列表';
			$links[1]['href'] = get_link("edit|dosubmit");
			$links[2]['title'] = '返回后台首页';
			$links[2]['href'] = $_admin_indexurl;
			if (!updatetable(table('neirong_lanmu'), $addlanmu, $wheresql)){
				showmsg("系统提醒", "修改失败,请稍后再试！", $links);
			}else{
				nr_zijilanmu($_POST['parentid']);
				if ($_POST['parentid'] != $lanmudb['parentid']) nr_zijilanmu($lanmudb['parentid']);
				refresh_cache_column($_CFG['site']);
				admin_log("修改栏目ID{$_GET['edit']}|{$addlanmu['title']}。", $admin_val['name']);
				showmsg("系统提醒", "修改成功！", $links);
			}
		}
		$templatefile = $__templatefile."/edit";
		$data = getcache(KF_ROOT_PATH.'caches/cache_neirong_moxing.php');
		$model_datas = array();
		foreach($data as $_k=>$_v) {
			if ($_v['id'] == intval($lanmudb['modelid'])) $model_datas[$_v['id']] = $_v['title'];
		}
		kf_class::run_sys_class('form','',0);
		$__moxing = form::select($model_datas,'0','name="modelid'.fenmiao().'"');
		$__lanmu = form::select_category('column/cache.'.$_CFG['site'], $lanmudb['parentid'], 'name="parentid'.fenmiao().'"', '≡ 作为一级栏目 ≡' , 0, -1);
		$smarty->assign('moxingsel', $__moxing);
		$smarty->assign('lanmusel', $__lanmu);
		$smarty->assign('lanmudbsetting', string2array($lanmudb['setting']));
		$smarty->assign('lanmudb', $lanmudb);
		//
		$_path = DIR_PATH.'neirong/';
		$_path = KF_ROOT_PATH.str_replace('/', DIRECTORY_SEPARATOR, $_path);
		$categoryarr = $listarr = $showarr = array('默认模型风格'=>''); 
		$_tplrarr = array('.html.tpl', '.html5.tpl', '.pad.tpl', '.web.tpl', '.tpl');
		$_patharr = glob($_path . '{category_*.tpl,category.tpl}', GLOB_BRACE);
		foreach ($_patharr as $_val) {
			$_val = basename($_val);
			$_val = str_replace($_tplrarr, '', $_val);
			$categoryarr[$_val] = $_val;
		}
		$_patharr = glob($_path . '{list_*.tpl,list.tpl}', GLOB_BRACE);
		foreach ($_patharr as $_val) {
			$_val = basename($_val);
			$_val = str_replace($_tplrarr, '', $_val);
			$listarr[$_val] = $_val;
		}
		$_patharr = glob($_path . '{show_*.tpl,show.tpl}', GLOB_BRACE);
		foreach ($_patharr as $_val) {
			$_val = basename($_val);
			$_val = str_replace($_tplrarr, '', $_val);
			$showarr[$_val] = $_val;
		}
		$smarty->assign('categoryarr', $categoryarr);
		$smarty->assign('listarr', $listarr);
		$smarty->assign('showarr', $showarr);
	}elseif ($_GET['del']) {
		$wheresql = "id=".intval($_GET['del'])."";
		$lanmudb = $db -> getone("select * from ".table('neirong_lanmu')." WHERE {$wheresql} LIMIT 1");
		if (!$lanmudb) { showmsg("系统提醒", "要删除的栏目ID".intval($_GET['del'])."不存在！"); }
		/*
		$_wheresql = "catid=".$lanmudb['id']."";
		$_lanmudb = $db -> getone("select * from ".table('diy_'.$lanmudb['module'])." WHERE {$_wheresql} LIMIT 1");
		if (!empty($_lanmudb)){
			showmsg("系统提醒", "要删除的栏目ID".$lanmudb['id']."还有内容，不可删除！");
		}
		*/
		$_wheresql = "parentid=".$lanmudb['id']."";
		$_lanmudb = $db -> getone("select * from ".table('neirong_lanmu')." WHERE {$_wheresql} LIMIT 1");
		if (!empty($_lanmudb)){
			showmsg("系统提醒", "要删除的栏目ID".$lanmudb['id']."|{$lanmudb['title']}还有子栏目，不可删除！");
		}
		if ($_GET['dosubmit']) {
			$links[0]['title'] = '返回列表';
			$links[0]['href'] = get_link("del|dosubmit");
			$links[1]['title'] = '返回后台首页';
			$links[1]['href'] = $_admin_indexurl;
			if (!$db->query("Delete from ".table('neirong_lanmu')." where id = ".$lanmudb['id'])){
				showmsg("系统提醒", "删除失败,请稍后再试！", $links);
			}else{
				//删除内容
				$_wheresql = "catid=".$lanmudb['id']."";
				$db->query("Delete from ".table('diy_'.$lanmudb['module'].'_data')." WHERE id in (SELECT id from ".table('diy_'.$lanmudb['module'])." WHERE {$_wheresql})");
				$db->query("Delete from ".table('diy_'.$lanmudb['module'])." WHERE {$_wheresql}");
				$db->query("Delete from ".table("neirong_fabu")." WHERE {$_wheresql}");
				//删除附件
				$_wheresql = "commentid REGEXP '^".$lanmudb['module']."_".$lanmudb['modelid']."_".$lanmudb['id']."_'";
				$sql = "SELECT * FROM ".table('neirong_fujian')." WHERE {$_wheresql} ORDER BY id asc";
				$arr = $db->getall($sql);
				foreach($arr as $_value) { fujian_del($_value['url']); }
				$db->query("Delete from ".table("neirong_fujian")." WHERE {$_wheresql}");
				//删除评论
				$_wheresql = "commentid REGEXP '^neirong_".$lanmudb['id']."_'";
				$db->query("Delete from ".table('pinglun')." WHERE {$_wheresql}");
				$db->query("Delete from ".table('pinglun_data_'.$lanmudb['site'])." WHERE {$_wheresql}");
				//删除搜索和权限设置
				$db->query("Delete from ".table('sousuo')." where catid = ".$lanmudb['id']);
				$db->query("Delete from ".table('neirong_lanmu_quanxian')." where catid = ".$lanmudb['id']);
				nr_zijilanmu($lanmudb['parentid']);
				refresh_cache_column($_CFG['site']);
				admin_log("删除了栏目ID{$lanmudb['id']}|{$lanmudb['title']}。", $admin_val['name']);
				showmsg("系统提醒", "删除成功！", $links);
			}
		}
		$links[0]['title'] = '确定删除';
		$links[0]['href'] = get_link("dosubmit")."&amp;dosubmit=1";
		$links[1]['title'] = '返回列表';
		$links[1]['href'] = get_link("del");
		$links[2]['title'] = '返回后台首页';
		$links[2]['href'] = $_admin_indexurl;
		showmsg("系统提醒", "你确定删除栏目及栏目下的所有内容“{$lanmudb['title']}(ID{$lanmudb['id']})”并且不可恢复吗？", $links);
	}elseif ($_GET['set']) {
		$templatefile = $__templatefile."/set";
		$wheresql = "id=".intval($_GET['set'])."";
		$lanmudb = $db -> getone("select * from ".table('neirong_lanmu')." WHERE {$wheresql} LIMIT 1");
		if (!$lanmudb) { showmsg("系统提醒", "要设置的栏目ID".intval($_GET['set'])."不存在！"); }
		$moxingdb = getcache(KF_ROOT_PATH.'caches/cache_neirong_moxing.php');
		$moxingdb = $moxingdb[$lanmudb['modelid']];
		if ($_POST['dosubmit']) {
			$_setting = $_add = $_visit = array();
			$_setting = string2array($lanmudb['setting']);
			$arrchildidarr = explode(',',$lanmudb['arrchildid']);
			foreach($_POST as $_k=>$_v) {
				$_arr = array();
				if (substr($_k, 0, 9) == 'priv_add_'){
					$_arr['action'] = 'add';
					$_arr['catid'] = $_GET['set'];
					$_arr['site'] = $_CFG['site'];
					$_arr['roleid'] = substr($_k, 9);
					$_arr['is'] = $_v;
					$row = $db -> getone("select * from ".table('neirong_lanmu_quanxian')." WHERE action='{$_arr['action']}' AND catid='{$_GET['set']}' AND site='{$_CFG['site']}' AND roleid='{$_arr['roleid']}' LIMIT 1");
					if (empty($row)){
						inserttable(table('neirong_lanmu_quanxian'), $_arr);
					}else{
						updatetable(table('neirong_lanmu_quanxian'), $_arr, "action='{$_arr['action']}' AND catid='{$_GET['set']}' AND site='{$_CFG['site']}' AND roleid='{$_arr['roleid']}'");
					}
					//发布权限应用到子栏目
					if ($_POST['add_zi'] == '1'){
                        foreach($arrchildidarr as $_vv) {
                            if ($_vv > 0){
                                $_arr['catid'] = $_vv;
                                $row = $db -> getone("select * from ".table('neirong_lanmu_quanxian')." WHERE action='{$_arr['action']}' AND catid='{$_vv}' AND site='{$_CFG['site']}' AND roleid='{$_arr['roleid']}' LIMIT 1");
                                if (empty($row)){
                                    inserttable(table('neirong_lanmu_quanxian'), $_arr);
                                }else{
                                    updatetable(table('neirong_lanmu_quanxian'), $_arr, "action='{$_arr['action']}' AND catid='{$_vv}' AND site='{$_CFG['site']}' AND roleid='{$_arr['roleid']}'");
                                }
                            }
                        }
					}
				}elseif (substr($_k, 0, 11) == 'priv_visit_'){
					$_arr['action'] = 'visit';
					$_arr['catid'] = $_GET['set'];
					$_arr['site'] = $_CFG['site'];
					$_arr['roleid'] = substr($_k, 11);
					$_arr['is'] = $_v;
					$row = $db -> getone("select * from ".table('neirong_lanmu_quanxian')." WHERE action='{$_arr['action']}' AND catid='{$_GET['set']}' AND site='{$_CFG['site']}' AND roleid='{$_arr['roleid']}' LIMIT 1");
					if (empty($row)){
						inserttable(table('neirong_lanmu_quanxian'), $_arr);
					}else{
						updatetable(table('neirong_lanmu_quanxian'), $_arr, "action='{$_arr['action']}' AND catid='{$_GET['set']}' AND site='{$_CFG['site']}' AND roleid='{$_arr['roleid']}'");
					}
					//浏览权限应用到子栏目
					if ($_POST['visit_zi'] == '1'){
                        foreach($arrchildidarr as $_vv) {
                            if ($_vv > 0){
                                $_arr['catid'] = $_vv;
                                $row = $db -> getone("select * from ".table('neirong_lanmu_quanxian')." WHERE action='{$_arr['action']}' AND catid='{$_vv}' AND site='{$_CFG['site']}' AND roleid='{$_arr['roleid']}' LIMIT 1");
                                if (empty($row)){
                                    inserttable(table('neirong_lanmu_quanxian'), $_arr);
                                }else{
                                    updatetable(table('neirong_lanmu_quanxian'), $_arr, "action='{$_arr['action']}' AND catid='{$_vv}' AND site='{$_CFG['site']}' AND roleid='{$_arr['roleid']}'");
                                }
                            }
                        }
					}
				}elseif (substr($_k, 0, 10) == 'priv_shen_'){
					$_arr['action'] = 'shen';
					$_arr['catid'] = $_GET['set'];
					$_arr['site'] = $_CFG['site'];
					$_arr['roleid'] = substr($_k, 10);
					$_arr['is'] = $_v;
					$row = $db -> getone("select * from ".table('neirong_lanmu_quanxian')." WHERE action='{$_arr['action']}' AND catid='{$_GET['set']}' AND site='{$_CFG['site']}' AND roleid='{$_arr['roleid']}' LIMIT 1");
					if (empty($row)){
						inserttable(table('neirong_lanmu_quanxian'), $_arr);
					}else{
						updatetable(table('neirong_lanmu_quanxian'), $_arr, "action='{$_arr['action']}' AND catid='{$_GET['set']}' AND site='{$_CFG['site']}' AND roleid='{$_arr['roleid']}'");
					}
					//免审权限应用到子栏目
					if ($_POST['shen_zi'] == '1'){
                        foreach($arrchildidarr as $_vv) {
                            if ($_vv > 0){
                                $_arr['catid'] = $_vv;
                                $row = $db -> getone("select * from ".table('neirong_lanmu_quanxian')." WHERE action='{$_arr['action']}' AND catid='{$_vv}' AND site='{$_CFG['site']}' AND roleid='{$_arr['roleid']}' LIMIT 1");
                                if (empty($row)){
                                    inserttable(table('neirong_lanmu_quanxian'), $_arr);
                                }else{
                                    updatetable(table('neirong_lanmu_quanxian'), $_arr, "action='{$_arr['action']}' AND catid='{$_vv}' AND site='{$_CFG['site']}' AND roleid='{$_arr['roleid']}'");
                                }
                            }
                        }
					}
				}elseif (substr($_k, 0, 8) == 'setting_'){
					$_setting[substr($_k, 8)] = $_v;
				}
			}
			//
			$_setting['presentpoint'] = intval($_setting['presentpoint']);
			$_setting['defaultchargepoint'] = intval($_setting['defaultchargepoint']);
			$_setting['repeatchargedays'] = intval($_setting['repeatchargedays']);
			//
			$_setting['xianshi_tg'] = intval($_setting['xianshi_tg']);
			$_setting['xianshi_hf'] = intval($_setting['xianshi_hf']);
			$_setting['xianshi_zcsj'] = intval($_setting['xianshi_zcsj']);
			$_setting['xianshi_zctg'] = intval($_setting['xianshi_zctg']);
			$_setting['xianshi_zcpl'] = intval($_setting['xianshi_zcpl']);
			//
			if ($moxingdb['type'] == 'bbs'){
				$_setting['bbs_jinghua'] = intval($_setting['bbs_jinghua']);
				$_setting['bbs_dingzhi'] = intval($_setting['bbs_dingzhi']);
			}
			//
			if (!empty($_setting['bbs_banzhu'])){
				$bbs_banzhu = array();
				$bbs_banzhuarr = explode('|',$_setting['bbs_banzhu']);
				foreach ($bbs_banzhuarr as $val) {
					if ($val < 1) continue; 
					$row = $db -> getone("select * from ".table('huiyuan')." WHERE `userid`=".intval($val));
					if (empty($row)) continue;
					$bbs_banzhu[] = $row['userid'];
				}
				if (!empty($bbs_banzhu)) $_setting['bbs_banzhu'] = implode('|',$bbs_banzhu);
			}
			//		
			$row = $db -> getall("select * from ".table('neirong_lanmu_quanxian')." WHERE action='visit' AND catid='{$_GET['set']}' AND site='{$_CFG['site']}'");
			$rowarr = array();
			foreach ($row as $val) {
				$rowarr[$val['roleid']] = $val['is'];
			}
			if ($_setting || $rowarr){
				$setsqlarr = array();
				$setsqlarr['setting'] = array2string($_setting);
				$setsqlarr['visit'] = array2string($rowarr);
				updatetable(table('neirong_lanmu'), $setsqlarr, $wheresql);
			}
            //设置应用到子栏目
			if ($_POST['set_zi'] == '1'){
				foreach($arrchildidarr as $_vv) {
					if ($_vv > 0){
						$row = $db -> getall("select * from ".table('neirong_lanmu_quanxian')." WHERE action='visit' AND catid='{$_vv}' AND site='{$_CFG['site']}'");
						$rowarr = array();
						foreach ($row as $val) {
							$rowarr[$val['roleid']] = $val['is'];
						}
						if ($_setting || $rowarr){
							$setsqlarr = array();
							$setsqlarr['setting'] = array2string($_setting);
							$setsqlarr['visit'] = array2string($rowarr);
							$wheresql = "id=".intval($_vv)."";
							updatetable(table('neirong_lanmu'), $setsqlarr, $wheresql);
						}
					}
				}
			}
			$links[0]['title'] = '继续修改';
			$links[0]['href'] = get_link();
			$links[1]['title'] = '返回修改栏目';
			$links[1]['href'] = get_link("set").'&amp;edit='.$_GET['set'];
			$links[2]['title'] = '返回后台首页';
			$links[2]['href'] = $_admin_indexurl;
			refresh_cache_column($_CFG['site']);
			admin_log("修改权限栏目ID{$_GET['set']}。", $admin_val['name']);
			showmsg("系统提醒", "保存成功！", $links);
		}
		$lanmudbsetting = string2array($lanmudb['setting']);
		$lanmudbsetting['presentpoint'] = $lanmudbsetting['presentpoint']?$lanmudbsetting['presentpoint']:'1';
		$lanmudbsetting['defaultchargepoint'] = $lanmudbsetting['defaultchargepoint']?$lanmudbsetting['defaultchargepoint']:'0';
		$lanmudbsetting['paytype'] = $lanmudbsetting['paytype']?$lanmudbsetting['paytype']:'0';
		$lanmudbsetting['repeatchargedays'] = $lanmudbsetting['repeatchargedays']?$lanmudbsetting['repeatchargedays']:'1';
		$smarty->assign('lanmudb', $lanmudb);
		$smarty->assign('lanmudbset', $lanmudbsetting);
		$smarty->assign('moxingdb', $moxingdb);
		$zudata = getcache(KF_ROOT_PATH.'caches/cache_huiyuan_zu.php');
		$smarty->assign('zudata', $zudata);
		$priv = array();
		$arr = $db->getall("SELECT * FROM ".table('neirong_lanmu_quanxian')." WHERE catid='{$_GET['set']}'");
		foreach($arr as $_value) {
			if ($_value['action'] == 'add'){
				$priv['add'][$_value['roleid']] = $_value['is'];
			}elseif ($_value['action'] == 'visit'){
				$priv['visit'][$_value['roleid']] = $_value['is'];
			}elseif ($_value['action'] == 'shen'){
				$priv['shen'][$_value['roleid']] = $_value['is'];
			}
		}
		$smarty->assign('priv', $priv);
	}else{
		$templatefile = $__templatefile."/index";
		kf_class::run_sys_class('tree','',0);
		$tree= new tree();
		$tree->icon = array('　│ ','　├─ ','　└─ ');
		$tree->nbsp = '　';
		$categorys = array();
		$data = getcache(KF_ROOT_PATH.'caches/column/cache.'.$_CFG['site'].'.php');
		foreach($data as $r) {
			$categorys[$r['id']] = $r;
		}
		$str  = "\$id.\$spacer<a href='".get_link()."&amp;edit=\$id'>\$title</a>|\$module_cn<br/>";
		$tree->init($categorys);
		$categorys = $tree->get_tree(0, $str);
		$smarty->assign('categorys', $categorys);
	}

}elseif ($_GET['a'] == 'fujian') {
	if ($_GET['replace']){
		$links[0]['title'] = '返回附件列表';
		$links[0]['href'] = get_link("replace|dosubmit|n");
		$links[1]['title'] = '返回后台首页';
		$links[1]['href'] = $_admin_indexurl;
		if ($_REQUEST['dosubmit']) {
			$cache_file_path =KF_ROOT_PATH. "caches/cache_time_replace.php";
			if ($_POST['dosubmit']){
				$config_arr = array();
				$config_arr['new_attachment_path'] = $_POST['new_attachment_path'];
				$config_arr['old_attachment_path'] = $_POST['old_attachment_path'];
				write_static_cache($cache_file_path, $config_arr);
			} require($cache_file_path); $_replacedata = $data; unset($data);
			$_n = 1;
			$_timefield = "update ".table('neirong_fujian')." set allurl = replace(allurl, '{$_replacedata['old_attachment_path']}', '{$_replacedata['new_attachment_path']}')";
			if ($_GET['n']<=$_n){
				$db->query($_timefield);
				showmsg("附件地址替换", "附件地址正在替换中....({$_n})！", $links, get_link("dosubmit|n")."&amp;dosubmit=1&amp;n=".($_n+1),1);
			}$_n++;
			$data = getcache(KF_ROOT_PATH.'caches/cache_neirong_moxing.php');
			foreach($data as $_value) {
				$_timefield = "update ".table('diy_'.$_value['tablename'])." set thumb = replace(thumb, '{$_replacedata['old_attachment_path']}', '{$_replacedata['new_attachment_path']}')";
				if ($_GET['n']<=$_n){
					$db->query($_timefield);
					showmsg("附件地址替换", "附件地址正在替换中....({$_n})！", $links, get_link("dosubmit|n")."&amp;dosubmit=1&amp;n=".($_n+1),1);
				}$_n++;
					
				$data = getcache(KF_ROOT_PATH.'caches/model/model_field_'.$_value['id'].'.cache.php');
				foreach($data as $_value2) {
					if ($_value2['type']=='downfile' || $_value2['type']=='images'){
						$_timefield = "update ".table('diy_'.$_value['tablename'].'_data')." set {$_value2['field']} = replace({$_value2['field']}, '{$_replacedata['old_attachment_path']}', '{$_replacedata['new_attachment_path']}')";
						if ($_GET['n']<=$_n){
							$db->query($_timefield);
							showmsg("附件地址替换", "附件地址正在替换中....({$_n})！", $links, get_link("dosubmit|n")."&amp;dosubmit=1&amp;n=".($_n+1),1);
						}$_n++;
					}
				}
			}
			fujian_del($cache_file_path);
			$_links[0]['title'] = '继续修改';
			$_links[0]['href'] = get_link("dosubmit|n");
			showmsg("附件地址替换", "全部替换完成！", array_merge($_links, $links));
		}
		$_valtime= "1. 当您的附件访问地址，发生修改的时候，可以使用本功能对内容中附件地址的批量修改。本功能不要滥用，只在有需要的时候使用，否则会有数据混乱的风险。<br/>";
		$_valtime.= "2. 请在使用本功能之前做好数据备份，否则使用后无法恢复。<br/>----------<br/>";
		$_valtime.= format_form(array('set'=>'头','notvs'=>'1'));
		$_valtime.= "原附件访问地址:(原来在配置文件中所配置的站点访问域名)<br/>".format_form("输入框|名称:'old_attachment_path".fenmiao()."'")."<br/>";
		$_valtime.= "新访问地址:<br/>".format_form("输入框|名称:'new_attachment_path".fenmiao()."',值:'".htmlspecialchars(str_replace(':','\:',$_CFG['site_domain'].$_CFG['site_dir'].'uploadfiles/'))."'")."<br/>";
		if ($_GET['vs']==1) $_valtime.= '
								<anchor title="提交">提交修改
								<go href="'.get_link().'" method="post" accept-charset="utf-8">
								<postfield name="old_attachment_path" value="$(old_attachment_path'.fenmiao().')"/>
								<postfield name="new_attachment_path" value="$(new_attachment_path'.fenmiao().')"/>
								<postfield name="dosubmit" value="1"/>
								</go></anchor>									
							';
		$_valtime.= format_form(array('set'=>'按钮|名称:dosubmit,值:提交修改','notvs'=>'1'));
		$_valtime.= format_form(array('set'=>'尾','notvs'=>'1'));
		$_valtime.= "<br/>*例如:原来的网址是http://aaa.com现在的网址是http://bbb.com，那么在原附件访问地址写http://aaa.com/uploadfiles/，在新访问地址写http://bbb.com/uploadfiles/。";
		showmsg("附件地址替换", $_valtime, $links);
	}
	kf_class::run_sys_func('upload');
}elseif ($_GET['a'] == 'fujianpeizhi') {
	$system_info = array();
	$system_info['version'] = KUAIFAN_VERSION;
	$system_info['release'] = KUAIFAN_RELEASE;
	$system_info['os'] = PHP_OS;
	$system_info['web_server'] = $_SERVER['SERVER_SOFTWARE'];
	$system_info['php_ver'] = PHP_VERSION;
	$system_info['mysql_ver'] = $db->dbversion();
	$system_info['max_filesize'] = ini_get('upload_max_filesize');
	if(!function_exists('imagepng') && !function_exists('imagejpeg') && !function_exists('imagegif')) {
		$system_info['gd'] = "不支持";
	} else {
		$system_info['gd'] = "支持";
	}
	$system_info['watermark_pos'] = array(
		'顶部居左'=>1,
		'顶部居中'=>2,
		'顶部居右'=>3,
		'中部居中'=>4,
		'中部居左'=>5,
		'中部居右'=>6,
		'底部居左'=>7,
		'底部居中'=>8,
		'底部居右'=>9,
		'随机位置'=>10,
	);
	$smarty->assign('system_info', $system_info);
	//
	$peizhi = $db -> getone("select * from ".table('peizhi_mokuai')." WHERE `module`='fujian' LIMIT 1");
	$setting = string2array($peizhi['setting']);
	$smarty->assign('peizhi', $setting);
	if (!empty($_POST['dosubmit'])){
		$setting = array_merge($setting, $_POST);
		if ($_FILES['watermark_img']['name']){
			kf_class::run_sys_func('upload');
			$up_dir_0 = "uploadfiles/content/water/";
			make_dir('./'.$up_dir_0);
			$_file_size = intval(ini_get('upload_max_filesize')*1024);
			$_file_allowext = "gif/jpg/jpeg/png/bmp";
			$_file_url = _asUpFiles('./'.$up_dir_0, 'watermark_img', $_file_size, $_file_allowext, $_FILES['watermark_img']['name'], true, false);
			$setting['watermark_img'] = $up_dir_0.$_file_url;
		}
		$db -> query("update ".table('peizhi_mokuai')." set `setting`='".array2string($setting)."' WHERE `module`='fujian'");
		refresh_peizhi_mokuai();
		$links[0]['title'] = '重新配置';
		$links[0]['href'] = get_link("dosubmit");
		$links[1]['title'] = '返回后台首页';
		$links[1]['href'] = $_admin_indexurl;
		showmsg("系统提醒", "提交配置成功！", $links);
	}
}elseif ($_GET['a'] == 'pinglun') {
	if ($_GET['id']){
		$pinglundb1 = $db -> getone("select * from ".table('pinglun_data_'.$_CFG['site'])." WHERE id=".intval($_GET['id'])." LIMIT 1");
		$pinglundb2 = $db -> getone("select * from ".table('pinglun')." WHERE commentid='".$pinglundb1['commentid']."' LIMIT 1");
		$_timetext = '
				<a href="'.get_link('id').'">返回列表</a><br/>
				<a href="'.get_link('id').'&amp;del='.$_GET['id'].'">删除此评论</a><br/>
				发表人:'.$pinglundb1['username'].'<br/>
				I P:'.$pinglundb1['ip'].'<br/>
				发表时间:'.date("Y-m-d H:i:s",$pinglundb1['creat_at']).'<br/>
				原文标题:'.$pinglundb2['title'].'<br/>
				评论详情:'.htmlspecialchars($pinglundb1['content']).'			
			
			';
		$links[0]['title'] = '返回后台首页';
		$links[0]['href'] = $_admin_indexurl;
		showmsg("评论详情", $_timetext, $links);
	}elseif ($_GET['del']){
		$row = $db -> getone("select * from ".table('pinglun_data_'.$_CFG['site'])." WHERE id=".intval($_GET['del'])." LIMIT 1");
		if (empty($row)) showmsg("系统提醒", "要删除的评论不存在！");
		$links[0]['title'] = '返回列表';
		$links[0]['href'] = get_link("del");
		$links[1]['title'] = '返回后台首页';
		$links[1]['href'] = $_admin_indexurl;
		if (!$db->query("Delete from ".table('pinglun_data_'.$_CFG['site'])." where id=".intval($_GET['del'])."")){
			showmsg("系统提醒", "删除失败,请稍后再试！", $links);
		}else{
			delplfile($row['content']); //删除评论附件
			admin_log("删除了评论“ID{$_GET['del']}", $admin_val['name']);
			if ($row['status'] == 1){
				//统计内容评论数
				$parr = explode('_', $row['commentid']);
				$ldata = getcache('caches/column/cache.'.$_CFG['site'].'.php');
				$ldata = $ldata[$parr[1]];
				$ldataset = string2array($ldata['setting']);
				$db -> query("update ".table("diy_".$ldata['module'])." set reply=reply-1 WHERE id='{$parr[2]}' AND catid='{$parr[1]}'");
				//统计评论数
				$pwhe = "total=total-1";
				if ($row['direction'] == '1'){
					$pwhe.= ",square=square-1";
				}elseif ($row['direction'] == '2'){
					$pwhe.= ",anti=anti-1";
				}elseif ($row['direction'] == '3'){
					$pwhe.= ",neutral=neutral-1";
				}
				$db -> query("update ".table('pinglun')." set {$pwhe} WHERE commentid='{$row['commentid']}'");
				//删除评论扣分
				$pl_arr = getcache('caches/caches_peizhi_mokuai/cache.pinglun.php');
				$pinglun_del_point = $pl_arr['pinglun_del_point'];
				if ($ldataset['pinglun_del_point'] == '0') $pinglun_del_point = 0; 
				if ($ldataset['pinglun_del_point'] > 0) $pinglun_del_point = intval($ldataset['pinglun_del_point']); 
				if ($pinglun_del_point > 0 && $row['userid'] > 0){
					kf_class::run_sys_func('huiyuan');
					set_jiangfa($row['userid'], $pinglun_del_point*-1, 0, '评论被系统管理员删除-'.$title);
				}
			}
			showmsg("系统提醒", "删除成功！", $links);
		}
	}elseif ($_GET['to']){
		$row = $db -> getone("select * from ".table('pinglun_data_'.$_CFG['site'])." WHERE id=".intval($_GET['to'])." LIMIT 1");
		if (empty($row)) showmsg("系统提醒", "要操作的评论不存在！");
		if (!empty($row['status'])) showmsg("系统提醒", "请勿重复操作！");
		$db -> query("update ".table('pinglun_data_'.$_CFG['site'])." set status=1 WHERE id='{$row['id']}'");
		//统计内容评论数
		$parr = explode('_', $row['commentid']);
		$ldata = getcache('caches/column/cache.'.$_CFG['site'].'.php');
		$ldata = $ldata[$parr[1]];
		$ldataset = string2array($ldata['setting']);
		$db -> query("update ".table("diy_".$ldata['module'])." set reply=reply+1 WHERE id='{$parr[2]}' AND catid='{$parr[1]}'");
		//通知原评论
		if ($row['is_huifu'] > 0){
			$rowpl = $db -> getone("select * from ".table('pinglun')." WHERE commentid='".$row['commentid']."' LIMIT 1");
			$rowhf = $db -> getone("select * from ".table('pinglun_data_'.$_CFG['site'])." WHERE id=".$row['is_huifu']." LIMIT 1");
			$rowhy = $db -> getone("select * from ".table('huiyuan')." WHERE userid=".$row['userid']." LIMIT 1");
			if ($rowhf['userid'] > 0 && $rowhf['userid'] != $rowhy['userid']){
				if ($rowhy['userid'] > 0){
					$_zuozhe = '<a href="index.php?m=huiyuan&amp;c=ziliao&amp;userid='.$rowhy['userid'].'&amp;sid={sid}">'.$rowhy['nickname'].'</a>';
				}else{
					$_zuozhe = '游客';
				}
				kf_class::run_sys_func('xinxi');
				add_message($rowhf['username'], 0, '回复您的评论', $_zuozhe.'在<a href="index.php?m=neirong&amp;c=show&amp;catid='.$parr[1].'&amp;id='.$parr[2].'&amp;sid={sid}">'.$rowpl['title'].'</a>回复了您的评论。');
			}
		}
		//通知作者
		$_rowcon = $db -> getone("select * from ".table('diy_'.$ldata['module'])." WHERE `id`='{$parr[2]}' AND `catid`='{$parr[1]}' LIMIT 1");	
		if ($_rowcon['tongzhiwo']>0 && $_rowcon['sysadd']==0){
			if (empty($rowhy)) $rowhy = $db -> getone("select * from ".table('huiyuan')." WHERE userid=".$row['userid']." LIMIT 1");
			if ($rowhy['userid'] > 0){
				$_zuozhe = '<a href="index.php?m=huiyuan&amp;c=ziliao&amp;userid='.$rowhy['userid'].'&amp;sid={sid}">'.$rowhy['nickname'].'</a>';
			}else{
				$_zuozhe = '游客';
			}
			if ($_rowcon['username'] != $rowhy['username']){
				kf_class::run_sys_func('xinxi');
				add_message($_rowcon['username'], 0, '回复您的内容-'.$_rowcon['title'], $_zuozhe.'回复了您发布的内容<a href="index.php?m=neirong&amp;c=show&amp;catid='.$parr[1].'&amp;id='.$parr[2].'&amp;sid={sid}">'.$_rowcon['title'].'</a>。');
			}
		}
		//统计评论数
		$pwhe = "total=total+1";
		if ($row['direction'] == '1'){
			$pwhe.= ",square=square+1";
		}elseif ($row['direction'] == '2'){
			$pwhe.= ",anti=anti+1";
		}elseif ($row['direction'] == '3'){
			$pwhe.= ",neutral=neutral+1";
		}
		$db -> query("update ".table('pinglun')." set {$pwhe} WHERE commentid='{$row['commentid']}'");
		//评论成功奖励
		$pl_arr = getcache('caches/caches_peizhi_mokuai/cache.pinglun.php');
		$pinglun_add_point = $pl_arr['pinglun_add_point'];
		if ($ldataset['pinglun_add_point'] == '0') $pinglun_add_point = 0; 
		if ($ldataset['pinglun_add_point'] > 0) $pinglun_add_point = intval($ldataset['pinglun_add_point']); 
		if ($pinglun_add_point > 0 && $row['userid'] > 0){
			kf_class::run_sys_func('huiyuan');
			set_jiangfa($row['userid'], $pinglun_add_point, 0, '评论-'.$title);
		}
		$links[0]['title'] = '返回列表';
		$links[0]['href'] = get_link("to");
		$links[1]['title'] = '返回后台首页';
		$links[1]['href'] = $_admin_indexurl;
		admin_log("审核了评论“ID{$_GET['to']}", $admin_val['name']);
		showmsg("系统提醒", "操作成功！", $links);
	}elseif ($_GET['config']){
		$_row = $db -> getone("select * from ".table('peizhi_mokuai')." WHERE `module`='pinglun'");	
		$_setting = string2array($_row['setting']);
		if (!empty($_POST['dosubmit'])){
			$links[0]['title'] = '返回后台首页';
			$links[0]['href'] = $_admin_indexurl;
			foreach($_POST as $k => $v){
				$v = $k=='pinglun_format'?$v:intval($v);
				$_setting[$k] = $v;
			}
			!$db->query("UPDATE ".table('peizhi_mokuai')." SET setting='".array2string($_setting)."' WHERE `module`='pinglun'")?showmsg("系统提醒", "更新设置失败！", $links):"";
			refresh_peizhi_mokuai('pinglun');
			admin_log("修改了评论配置。", $admin_val['name']);
			$links[1]['title'] = '返回评论列表';
			$links[1]['href'] = get_link('config');
			$links[2]['title'] = '继续配置';
			$links[2]['href'] = get_link();
			$links = array_reverse($links);
			showmsg("系统提醒", "保存成功！", $links);
		}
		$smarty->assign('setting',$_setting);
		$templatefile = $__templatefile."/peizhi";
	}else{
		$templatefile = $__templatefile."/index";
	}
}elseif ($_GET['a'] == 'qingli') {
	if (!empty($_REQUEST['dosubmit'])){
		if ($admin_val['id']!='1') showmsg("系统提醒", "只有网站创始人才可以操作！");
		if ($_GET['do']){
			$links[0]['title'] = '返回后台首页';
			$links[0]['href'] = $_admin_indexurl;
			$_timearr = explode(',',$_GET['do']);
			$_wheresql= "site=".$_CFG['site'];
			$_n = ($_GET['n'] > 0)?intval($_GET['n']):0;
			//删除评论
			if(in_array('pinglun' , $_timearr) && $_n <= 0)	{
				$db->query("Delete from ".table('pinglun_data_'.$_CFG['site'])." WHERE {$_wheresql}");
				$db->query("Delete from ".table('pinglun')." WHERE {$_wheresql}");
				admin_log("清理了评论！", $admin_val['name']);
				showmsg("系统提醒", "评论清理完成,正在进入下一项....(1)", $links, get_link("n")."&amp;n=1",1);
			}
			//删除附件
			if(in_array('fujian' , $_timearr) && $_n <= 1)	{
				$arr = $db->getall("SELECT * FROM ".table('neirong_fujian')." WHERE {$_wheresql} ORDER BY id asc");
				$_nn = 0;
				foreach($arr as $_value) {
					fujian_del($_value['url']);
					$db->query("Delete from ".table('neirong_fujian')." WHERE id=".$_value['id']);
					$_nn++;
					if ($_nn > 100) showmsg("系统提醒", "正在清理附件,正在进入下一项....(2+)", $links, get_link("n")."&amp;n=1",1);
				}
				admin_log("清理了附件！", $admin_val['name']);
				showmsg("系统提醒", "附件清理完成,正在进入下一项....(2)", $links, get_link("n")."&amp;n=2",1);
			}
			//删除内容
			if(in_array('neirong' , $_timearr) && $_n <= 2)	{
				$arr = $db->getall("SELECT * FROM ".table('neirong_lanmu')." WHERE {$_wheresql} GROUP BY module");
				$_nn = 0;
				foreach($arr as $_key => $_value) {
          if ($_key > $_GET['k']){
            $db->query("Delete from ".table("diy_".$_value['module']));
            $db->query("Delete from ".table("diy_".$_value['module']."_data"));
            $_nn++;
            if ($_nn > 0) showmsg("系统提醒", "正在清理内容,正在进入下一项....(3+)", $links, get_link("n|k")."&amp;n=2&amp;k=".$_key,1);
					}
				}
        $db->query("Delete from ".table("neirong_fabu"));
        $db->query("Delete from ".table('sousuo'));
				admin_log("清理了内容！", $admin_val['name']);
				showmsg("系统提醒", "内容清理完成,正在进入下一项....(3)", $links, get_link("n|k")."&amp;n=3",1);
			}
			//删除栏目
			if(in_array('lanmu' , $_timearr) && $_n <= 3)	{
				$db->query("Delete from ".table('neirong_lanmu'));
				$db->query("Delete from ".table('neirong_lanmu_quanxian'));
				admin_log("清理了栏目！", $admin_val['name']);
				showmsg("系统提醒", "栏目清理完成,正在进入下一项....(4)", $links, get_link("n")."&amp;n=4",1);
			}
			//删除模型
			if(in_array('moxing' , $_timearr) && $_n <= 4)	{
				$arr = $db->getall("SELECT * FROM ".table('neirong_moxing')." WHERE {$_wheresql} ORDER BY id asc");
				$_nn = 0;
				foreach($arr as $_value) {
					$db->query("DROP TABLE ".table('diy_'.$_value['tablename']));
					$db->query("DROP TABLE ".table('diy_'.$_value['tablename'].'_data'));
					$db->query("Delete from ".table('neirong_moxing_ziduan')." WHERE modelid={$_value['id']}");
					$db->query("Delete from ".table('neirong_moxing')." WHERE id=".$_value['id']);
					$_nn++;
					if ($_nn > 0) showmsg("系统提醒", "正在清理模型,正在进入下一项....(5+)", $links, get_link("n")."&amp;n=4",1);
				}
				admin_log("清理了模型！", $admin_val['name']);
				showmsg("系统提醒", "模型清理完成,正在进入下一项....(5)", $links, get_link("n")."&amp;n=5",1);
			}
			showmsg("系统提醒", "清理任务完成，建议您<a href='".$_admin_indexurl."&amp;c=huancun'>更新一下缓存</a>！", $links);
		}
		$_timeval = '';
		if ($_REQUEST['ql_pinglun']=='1' ) $_timeval.= "pinglun";
		if ($_REQUEST['ql_fujian']=='1') $_timeval.= "fujian";
		if ($_REQUEST['ql_neirong']=='1') $_timeval.= "neirong,fujian,pinglun";
		if ($_REQUEST['ql_lanmu']=='1') $_timeval.= "lanmu,neirong,fujian,pinglun";
		if ($_REQUEST['ql_moxing']=='1') $_timeval.= "moxing,lanmu,neirong,fujian,pinglun";
		$_timearr = explode(',',$_timeval);
		$_timeval = ''; $_timedo = '';
		if(in_array('moxing' , $_timearr)) 	{
			$_timeval.= '所有模型 ';
			$_timedo.= 'moxing ';
		}
		if(in_array('lanmu' , $_timearr)) {
			$_timeval.= '所有栏目 ';
			$_timedo.= 'lanmu ';
		}
		if(in_array('neirong' , $_timearr))	{
			$_timeval.= '所有内容 ';
			$_timedo.= 'neirong ';
		}
		if(in_array('fujian' , $_timearr)) {
			$_timeval.= '所有附件 ';
			$_timedo.= 'fujian ';
		}
		if(in_array('pinglun' , $_timearr))	{
			$_timeval.= '所有评论 ';
			$_timedo.= 'pinglun ';
		}
		$_timeval = str_replace(' ', ',', trim($_timeval));
		$_timedo = str_replace(' ', ',', trim($_timedo));
		$links[0]['title'] = '返回重选';
		$links[0]['href'] = get_link("dosubmit|do");
		$links[1]['title'] = '返回后台首页';
		$links[1]['href'] = $_admin_indexurl;
		if ($_timeval){
			showmsg("系统提醒", "确定清理的项目有：<br/><b><u>{$_timeval}</u></b>。<br/>
				<a href=\"".get_link('dosubmit|do')."&amp;dosubmit=1&amp;do={$_timedo}\">确定清理</a>", $links);
		}else{
			showmsg("系统提醒", "你没有选择任何要清理的项目！");
		}
	}
}elseif ($_GET['a']=='shenhe'){
	if (isset($_GET['mode']) && $_GET['checkid']){
		$row = $db -> getone("select * from ".table("neirong_fabu")." WHERE checkid='{$_GET['checkid']}' LIMIT 1");
		if (empty($row)) showmsg("系统提醒", "你要审核的内容不存在！");
		$arr_checkid = explode('-',$row['checkid']);
		$row['id'] = $arr_checkid[1];
		$row['modelid'] = $arr_checkid[2];
		$data = getcache(KF_ROOT_PATH.'caches/column/cache.'.$row['site'].'.php');
		$lanmudb = $data[$row['catid']];
		$wheresql = "id=".intval($row['id'])."";
		$neirongdb1 = $db -> getone("select * from ".table("diy_".$lanmudb['module'])." WHERE {$wheresql} LIMIT 1");
		if (!$neirongdb1) { showmsg("系统提醒", "要修改的内容ID".intval($row['id'])."不存在！"); }
		if ($neirongdb1['status']==98 && !$_GET['dosubmit']){
			$links[0]['title'] = '确定修改';
			$links[0]['href'] = get_link("dosubmit").'&amp;dosubmit=1';
			$links[1]['title'] = '返回审核列表';
			$links[1]['href'] = get_link("mode|checkid");
			showmsg("系统提醒", "此内容当前状态为草稿，如果修改状态可能会影响会员的操作，是否确定修改？", $links);
		}
		set_shenhe("diy_".$lanmudb['module'], $_GET['mode'], $neirongdb1['id'], $admin_val['name']);
		$links[0]['title'] = '返回继续修改';
		$links[0]['href'] = get_link("mode|checkid|dosubmit");
		showmsg("系统提醒", "修改审核状态成功。", $links, $links[0]['href']);
	}
}elseif ($_GET['a']=='guanggao'){
	//添加（修改）内容
	if ($_GET['add']){
		if ($_GET['add'] > 0){
			$row = $db -> getone("select * from ".table('neirong_guanggao')." WHERE `id`=".intval($_GET['add'])."");
			$smarty->assign('add', $row);
		}
		if ($_POST['dosubmit']) {
			if (empty($_POST['title'])) showmsg("系统提醒", "请输入关键词！");
			$_row = array();
			$_row['title'] = htmlspecialchars($_POST['title']);
			$_row['url'] = htmlspecialchars($_POST['url']);
			$_row['type'] = in_array($_POST['type'],array('通用','内容','评论'))?$_POST['type']:"通用";
			$_row['stype'] = $_POST['stype'];
			$_row['order'] = intval($_POST['order']);
			$_row['islogin'] = intval($_POST['islogin']);
			$_row['wap'] = intval($_POST['wap']);
			$_row['site'] = $_CFG['site'];
			if ($row){
				if (!updatetable(table("neirong_guanggao"), $_row, "id={$row['id']}")){
					showmsg("系统提醒", "修改失败，请稍后再试。");
				}else{
					refresh_neirong_guanggao();
					admin_log("修改内嵌广告ID：{$row['id']}", $admin_val['name']);
					$links[0]['title'] = '重新修改';
					$links[0]['href'] = get_link("dosubmit");
					$links[1]['title'] = '返回列表';
					$links[1]['href'] = get_link("add|dosubmit");
					$links[2]['title'] = '返回后台首页';
					$links[2]['href'] = $_admin_indexurl;
					showmsg("系统提醒", "修改成功。", $links);
				}
			}else{
				$trueid = inserttable(table("neirong_guanggao"), $_row, true);
				if (!$trueid){
					showmsg("系统提醒", "添加失败，请稍后再试。");
				}else{
					refresh_neirong_guanggao();
					admin_log("添加内嵌广告ID：{$trueid}", $admin_val['name']);
					$links[0]['title'] = '继续添加';
					$links[0]['href'] = get_link("dosubmit");
					$links[1]['title'] = '返回列表';
					$links[1]['href'] = get_link("add|dosubmit");
					$links[2]['title'] = '返回后台首页';
					$links[2]['href'] = $_admin_indexurl;
					showmsg("系统提醒", "添加成功。", $links);
				}
			}
		}
		$templatefile = $__templatefile."/add";
	}else{
		$templatefile = $__templatefile."/index";
	}
}else{
	if (intval($_GET['catid']) > 0){
		$templatefile = $__templatefile."/list";
		$wheresql = "id=".intval($_GET['catid'])."";
		$lanmudb = $db -> getone("select * from ".table('neirong_lanmu')." WHERE {$wheresql} LIMIT 1");
		if (!$lanmudb) { showmsg("系统提醒", "要管理的栏目ID".intval($_GET['catid'])."不存在！"); }
		$smarty->assign('lanmudb', $lanmudb);
			
		//添加内容
		if ($_GET['add']){
			//模型
			$moxingdata = getcache(KF_ROOT_PATH.'caches/cache_neirong_moxing.php');
			$moxingdata = $moxingdata[$lanmudb['modelid']];
			if ($moxingdata['type'] == 'bbs'){
				$_urlarr = array(
					'm'=>'neirong',
					'c'=>'list',
					'catid'=>$lanmudb['id'],
					'sid'=>$_GET['sid'],
					'vs'=>$_GET['vs'],
				);
				$_valurl = url_rewrite('KF_neironglist', $_urlarr);
				showmsg("系统提醒", "论坛模型不可在后台发布内容，请至<a href=\"{$_valurl}\">前台发布</a>。");
			}
			$templatefile = $__templatefile."/add";
			kf_class::run_sys_func('form');
			$data = getcache(KF_ROOT_PATH.'caches/model/model_field_'.$lanmudb['modelid'].'.cache.php');
			if ($_POST['dosubmit']) {
				$__post = array();
				$__post_data = array();
				$__post['catid'] = intval($_GET['catid']);
				$__post['username'] = $admin_val['name'];
				$__post['site'] = $_CFG['site'];
				$__post['sysadd'] = 1;
				foreach($_POST as $_k=>$_v) {
					if (substr($_k, 0, 6) == '__img_'){
						continue;
					}
					$_poarr = $data[$_k];
					$_poarrset = string2array($_poarr['setting']);
					//最小长度
					if (strlen($_v) < intval($_poarr['minlength']) && intval($_poarr['minlength']) > 0){
						showmsg("系统提醒", "“{$_poarr['name']}”最小输入长度为".intval($_poarr['minlength'])."。");
					}
					//最大长度
					if (strlen($_v) > intval($_poarr['maxlength']) && intval($_poarr['maxlength']) > 0){
						if ($_poarr['field'] == 'description'){
							$_v = substr($_v, 0, intval($_poarr['maxlength']));
						}else{
							showmsg("系统提醒", "“{$_poarr['name']}”最大输入长度为".intval($_poarr['maxlength'])."。");
						}
					}
					//数字字段
					if ($_poarr['type'] == 'number'){
						//小数位数
						if ($_poarrset['decimaldigits'] > 0){
							$_v = round($_v, $_poarrset['decimaldigits']);
						}else{
							$_v = intval($_v);
						}
						//取值范围
						if ($_v < $_poarrset['minnumber'] && $_poarrset['minnumber']){
							showmsg("系统提醒", "“{$_poarr['name']}”取值范围最小值为{$_poarrset['minnumber']}。");
						}
						if ($_v > $_poarrset['maxnumber'] && $_poarrset['maxnumber']){
							showmsg("系统提醒", "“{$_poarr['name']}”取值范围最大值为{$_poarrset['maxnumber']}。");
						}
					}
					//时间字段
					if ($_poarr['type'] == 'datetime'){
						$_v = strtotime($_v);
					}
					//收费字段
					if($_k == 'paytype'){
						$__post_data['paytype'] = $_v;
					}
					//字段主副
					if ($_poarr['issystem'] == '1') $__post[$_k] = $_v;
					if ($_poarr['issystem'] == '0') {
						//内容字段判断替换br
						if ($_poarrset['newline2br']){
							$__post_data[$_k] = $_v;
						}else{
							$__post_data[$_k] = nl2br($_v);
						}
					}
				}
				$__post['replytime'] = $__post['inputtime'];
				$__post['subtitle'] = to_subtitle($__post_data['content'], $__post['subtitle'], 2);
				kf_class::run_sys_func('upload');
				$_urlA = $_urlB = $_urlC = array();
				foreach($_FILES as $_k=>$_v) {
					$_poarr = $data[$_k];
					//字段是否存在
					if (empty($_poarr)){
						if (strrchr($_k, '_')) {
							$_kk = substr($_k, 0, strlen(strrchr($_k,'_'))*-1);
							$_poarr = $data[$_kk];
						}
						if (empty($_poarr)){
							showmsg("系统提醒", "参数错误！");
						}
					}
					$_poarrset = string2array($_poarr['setting']);
					//最小长度
					if (intval($_poarr['minlength']) > 0){
						!$_FILES[$_k]['name']?showmsg("系统提醒", "请选择要上传的“{$_poarr['name']}”"):"";
					}
					//跳过
					if (!$_FILES[$_k]['name'] && intval($_poarr['minlength']) == 0){
						continue;
					}
					//跳过数量
					if ($_urlC[$_poarr['field']] > intval($_poarrset['upload_number']) && $_poarrset['upload_number']) {
						continue; 
					}
					//上传格式
					$_file_allowext = $_poarrset['upload_allowext'];
					if ($_poarr['type'] == 'image') $_file_allowext = "gif|jpg|jpeg|png|bmp";
					if ($_poarr['type'] == 'downfile' && empty($_file_allowext)) $_file_allowext = "rar|zip|jar|apk|7z";
					if (!$_file_allowext) showmsg("系统提醒", "“{$_poarr['name']}”未设置允许上传的文件类型，请先设置！");
					$_file_allowext = str_replace('|', '/', $_file_allowext);
					$_file_allowext = str_replace(',', '/', $_file_allowext);
					//上传大小
					$_file_size = intval(ini_get('upload_max_filesize')*1024);
					//目录构造
					$up_dir_0 = "uploadfiles/content/default/";
					make_dir('./'.$up_dir_0.date("Y/m/d/"));
					if ($_k == 'thumb') { //*缩列图
						$up_dir_100 = "uploadfiles/content/100/";
						$up_dir_48 = "uploadfiles/content/48/";
						make_dir('./'.$up_dir_100.date("Y/m/d/"));
						make_dir('./'.$up_dir_48.date("Y/m/d/"));
					}
					//开始上传
					$_file[$_k] = _asUpFiles('./'.$up_dir_0.date("Y/m/d/"), $_k, $_file_size, $_file_allowext, true);
					if ($_file[$_k]){
						if ($_k == 'thumb') { //*缩列图
							makethumb('./'.$up_dir_0.date("Y/m/d/").$_file[$_k], './'.$up_dir_100.date("Y/m/d/"), 100, 100);
							makethumb('./'.$up_dir_0.date("Y/m/d/").$_file[$_k], './'.$up_dir_48.date("Y/m/d/"), 48, 48);
							$_timepost[$_k]['0'] = 		$_CFG['site_domain'].$_CFG['site_dir'].$up_dir_0.date("Y/m/d/").$_file[$_k];
							$_timepost[$_k]['100'] = 	$_CFG['site_domain'].$_CFG['site_dir'].$up_dir_100.date("Y/m/d/").$_file[$_k];
							$_timepost[$_k]['48'] = 	$_CFG['site_domain'].$_CFG['site_dir'].$up_dir_48.date("Y/m/d/").$_file[$_k];
							if ($_poarr['issystem'] == '1') $__post[$_k] = array2string($_timepost[$_k]);
							if ($_poarr['issystem'] == '0') $__post_data[$_k] = array2string($_timepost[$_k]);
							//处理附件表
							$_urlA = array(
									'title' => $__post['title'],
									'name' => $_FILES[$_k]['name'],
									'url' => './'.$up_dir_0.date("Y/m/d/").$_file[$_k],
									'allurl' => $_timepost[$_k]['0'],
									'field' => $_k,
							);
							$__post['subtitle'] = to_subtitle("图", $__post['subtitle']);
						}else{
							$_timepost[$_k] = $_CFG['site_domain'].$_CFG['site_dir'].$up_dir_0.date("Y/m/d/").$_file[$_k];
							//处理附件表
							$_urlB[] = array(
										'title' => $__post['title'],
										'name' => $_FILES[$_k]['name'],
										'body' => $_POST[$_k],
										'url' => './'.$up_dir_0.date("Y/m/d/").$_file[$_k],
										'allurl' => $_timepost[$_k],
										'field' => $_poarr['field'],
							);
							//第一张作为缩略图
							if (!empty($_poarrset['uponethumb']) && empty($__post['thumb'])){
								$get_ext = strtolower(get_extension($_timepost[$_k]));
								if (in_array($get_ext, array('jpg','jpeg','png','gif','bmp'))){
									$up_dir_100 = "uploadfiles/content/100/";
									$up_dir_48 = "uploadfiles/content/48/";
									make_dir('./'.$up_dir_100.date("Y/m/d/"));
									make_dir('./'.$up_dir_48.date("Y/m/d/"));
									makethumb('./'.$up_dir_0.date("Y/m/d/").$_file[$_k], './'.$up_dir_100.date("Y/m/d/"), 100, 100);
									makethumb('./'.$up_dir_0.date("Y/m/d/").$_file[$_k], './'.$up_dir_48.date("Y/m/d/"), 48, 48);
									$_timethumb = array();
									$_timethumb['0'] = 		$_timepost[$_k];
									$_timethumb['100'] = 	$_CFG['site_domain'].$_CFG['site_dir'].$up_dir_100.date("Y/m/d/").$_file[$_k];
									$_timethumb['48'] = 	$_CFG['site_domain'].$_CFG['site_dir'].$up_dir_48.date("Y/m/d/").$_file[$_k];
									$__post['thumb'] = array2string($_timethumb);
								}
							}
							//
							$__post['subtitle'] = to_subtitle($_file[$_k], $__post['subtitle'], 1);
							$_urlC[$_poarr['field']]++;
						}
					}else{
						showmsg("系统提醒", "“{$_poarr['name']}”上传失败！");
					}
				}
				$__post['description'] = $__post['description']?$__post['description']:substr(strip_tags($__post_data['content']),0,255);
				$trueid = inserttable(table("diy_".$lanmudb['module']),$__post, true);
				if (empty($trueid)){
					showmsg("添加失败", "发布失败，网络繁忙请稍后再试！");
				}
				//更新到附件数据表
				if ($_urlA) _db_fujian($lanmudb['module']."_".$lanmudb['modelid']."_".$__post['catid']."_".$trueid, $lanmudb['modelid'], $_urlA, 1);
				if ($_urlB) {
					foreach($_urlB as $_k=>$_v) {
						if ($_v) _db_fujian($lanmudb['module']."_".$lanmudb['modelid']."_".$__post['catid']."_".$trueid, $lanmudb['modelid'], $_v, 1);
					}
					//更新字段
					$_wheresqlB = "commentid='".$lanmudb['module']."_".$lanmudb['modelid']."_".$__post['catid']."_".$trueid."'";
					$__val = array();
					$arr = $db->getall("SELECT * FROM ".table('neirong_fujian')." WHERE {$_wheresqlB} ORDER BY id asc");
					foreach($arr as $_value){
						$__val[$_value['field']][$_value['id']]= $_value['allurl'];
					}
					foreach($__val as $_k2=>$_v2) {
						$_poarr2 = $data[$_k2];
						if ($_poarr2['issystem']=='1'){
							$db -> query("update ".table("diy_".$lanmudb['module'])." set `{$_k2}`='".array2string($_v2)."' WHERE id=".$trueid."");
						}else{
							$__post_data[$_k2] = array2string($_v2);
							//$db -> query("update ".table("diy_".$lanmudb['module']."_data")." set `{$_k2}`='".array2string($_v2)."' WHERE id=".$trueid."");
						}
					}
				}
				//通过采集上传附件
				foreach($_POST as $_k=>$_v) {
					if (substr($_k, 0, 6) == '__img_'){
						$pattern = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.jpeg|\.png]))[\'|\"].*?[\/]?>/";
						preg_match_all($pattern, stripslashes($_v), $_vlists);
						$_vlist = $_vlists[1];
						if (!empty($_vlist)){
							kf_class::run_sys_func('upload');
							$up_dir_0 = "uploadfiles/content/default/";
							make_dir('./'.$up_dir_0.date("Y/m/d/"));
							$__val = array();
							foreach ($_vlist as $_vk => $_vval) {
								if (substr($_vval, 0, 4) == "http"){
									$varname = reset(explode('?', basename($_vval)));
									$vararr = getImage($_vval, $up_dir_0.date("Y/m/d/"), $varname);
									if ($vararr['error'] == '0'){
										preg_match('/<[img|IMG].*?width=[\'|\"](.*?)[\'|\"].*?[\/]?>/i', $_vlists[0][$_vk], $_matches); 
										$_width = intval($_matches[1]);
										preg_match('/<[img|IMG].*?height=[\'|\"](.*?)[\'|\"].*?[\/]?>/i', $_vlists[0][$_vk], $_matches); 
										$_height = intval($_matches[1]);
										if ($_width > 0 || $_height > 0){
											makethumb($up_dir_0.date("Y/m/d/").$varname, $up_dir_0.date("Y/m/d/"), $_width, $_height, 0, $varname);
										}
										$_urlA = array(
										  'title' => $__post['title'],
										  'name' => $varname,
										  'body' => '',
										  'url' => './'.$up_dir_0.date("Y/m/d/").$varname,
										  'allurl' => $_CFG['site_domain'].$_CFG['site_dir'].$up_dir_0.date("Y/m/d/").$varname,
										  'field' => substr($_k, 6),
										);
										$_fid = _db_fujian($lanmudb['module']."_".$lanmudb['modelid']."_".$__post['catid']."_".$trueid, $lanmudb['modelid'], $_urlA, 1);
										$__val[$_fid]= $_urlA['allurl'];
									}
								}
							}
							if (!empty($__val)){
								if ($__post['subtitle'] != to_subtitle("图", $__post['subtitle'])){
									$db -> query("update ".table("diy_".$lanmudb['module'])." set `subtitle`='".to_subtitle("图", $__post['subtitle'])."' WHERE id=".$trueid."");
								}
								$_poarr = $data[substr($_k, 6)];
								if ($_poarr['issystem']=='1'){										
									$db -> query("update ".table("diy_".$lanmudb['module'])." set `".substr($_k, 6)."`='".array2string($__val)."' WHERE id=".$trueid."");
								}elseif (!empty($_poarr)){
									$__post_data[substr($_k, 6)] = array2string($__val);
								}
							}
						}
					}
				}
				//统计栏目
				$total_sql="SELECT COUNT(*) AS num FROM ".table("diy_".$lanmudb['module'])." WHERE catid = ".intval($_GET['catid'])."";
				$total_count=$db->get_total($total_sql);
				$db -> query("update ".table('neirong_lanmu')." set items='".$total_count."' WHERE id=".intval($_GET['catid'])."");
				refresh_cache_column($lanmudb['site']);
				//统计模型
				$total_sql="SELECT COUNT(*) AS num FROM ".table("diy_".$lanmudb['module'])."";
				$total_count=$db->get_total($total_sql);
				$db -> query("update ".table('neirong_moxing')." set num='".$total_count."' WHERE id=".$lanmudb['modelid']."");
				cache_field($lanmudb['modelid']);

				$links[0]['title'] = '继续发布';
				$links[0]['href'] = get_link("dosubmit")."";
				$links[1]['title'] = '返回列表';
				$links[1]['href'] = get_link("add|dosubmit");
				$wheresql = "modelid={$lanmudb['modelid']} AND status!=1 AND hide=0 AND type in ('downfile','images')";
				$sql = "SELECT * FROM ".table('neirong_moxing_ziduan')." WHERE {$wheresql} ORDER BY listorder asc,id asc";
				$arr = $db->getall($sql); $_i = 2;
				foreach($arr as $_value) {
					$links[$_i]['title'] = '进入:'.$_value['name'];
					$links[$_i]['href'] = get_link("upfile|add|dosubmit")."&amp;edit={$trueid}&amp;upfile=".$_value['field'];
					$_i++;
				}
				//全站搜索
				$fulltext = $__post['title'];
				$fulltext.= $__post['keywords']?$__post['keywords']:strip_tags($__post_data['content']);
				install_search($trueid, $lanmudb['id'], $lanmudb['modelid'], $__post['title'], $fulltext, $__post['title'], $__post['description'], $__post['inputtime'], $_CFG['site']);
				$links[$_i+2]['title'] = '返回后台首页';
				$links[$_i+2]['href'] = $_admin_indexurl;
				$__post_data['id'] = $trueid;
				$__post_data['site'] = $_CFG['site'];
				!empty($__post_data)? inserttable(table("diy_".$lanmudb['module']."_data"),$__post_data):"";
				admin_log("发布了内容“ID{$trueid} | {$__post['title']}”到栏目ID“{$__post['catid']} | {$lanmudb['title']}”。", $admin_val['name']);
				showmsg("系统提醒", "发布成功！", $links);
			}

			$ziduandata = array();
			foreach($data as $r) {
				if ($r['status'] != '1' && $r['hide'] == '0'){
					if ($r['type'] != 'catid' && $r['type'] != 'typeid'){
						$ziduandata[$r['id']] = $r;
					}
				}
			}
			$smarty->assign('ziduandata', $ziduandata);
		}
			
		//删除内容
		if ($_GET['del']){
			$wheresql = "id=".intval($_GET['del'])."";
			$neirongdb1 = $db -> getone("select * from ".table("diy_".$lanmudb['module'])." WHERE {$wheresql} LIMIT 1");
			if (!$neirongdb1) { showmsg("系统提醒", "要删除的内容ID".intval($_GET['del'])."不存在！"); }

			if ($_GET['dosubmit']){
				$links[0]['title'] = '返回列表';
				$links[0]['href'] = get_link("del|dosubmit");
				$links[1]['title'] = '返回后台首页';
				$links[1]['href'] = $_admin_indexurl;
				if (!$db->query("Delete from ".table("diy_".$lanmudb['module'])." where {$wheresql}")){
					showmsg("系统提醒", "删除失败,请稍后再试！", $links);
				}else{
					//统计栏目
					$total_sql="SELECT COUNT(*) AS num FROM ".table("diy_".$lanmudb['module'])." WHERE catid = ".intval($_GET['catid'])."";
					$total_count=$db->get_total($total_sql);
					$db -> query("update ".table('neirong_lanmu')." set items='".$total_count."' WHERE id=".intval($_GET['catid'])."");
					refresh_cache_column($lanmudb['site']);
					//统计模型
					$total_sql="SELECT COUNT(*) AS num FROM ".table("diy_".$lanmudb['module'])."";
					$total_count=$db->get_total($total_sql);
					$db -> query("update ".table('neirong_moxing')." set num='".$total_count."' WHERE id=".$lanmudb['modelid']."");
					cache_field($lanmudb['modelid']);
					//删除附件
					$_wheresql = "commentid='".$lanmudb['module']."_".$lanmudb['modelid']."_".$neirongdb1['catid']."_".$neirongdb1['id']."'";
					$sql = "SELECT * FROM ".table('neirong_fujian')." WHERE {$_wheresql} ORDER BY id asc";
					$arr = $db->getall($sql);
					foreach($arr as $_value) { fujian_del($_value['url']); }
					$db->query("Delete from ".table("neirong_fujian")." WHERE {$_wheresql}");
					//删除评论
					$_wheresql = "commentid REGEXP '^neirong_".$neirongdb1['catid']."_".$neirongdb1['id']."_'";
					$db->query("Delete from ".table('pinglun')." WHERE {$_wheresql}");
					$db->query("Delete from ".table('pinglun_data_'.$neirongdb1['site'])." WHERE {$_wheresql}");
					//删除数据
					$db->query("Delete from ".table("diy_".$lanmudb['module'])." where {$wheresql}");
					$db->query("Delete from ".table("diy_".$lanmudb['module']."_data")." where {$wheresql}");
					$db->query("Delete from ".table("neirong_fabu")." where checkid='c-{$_GET['del']}-{$lanmudb['modelid']}' AND username='{$neirongdb1['username']}'");
					$db->query("Delete from ".table("sousuo")." where catid={$neirongdb1['catid']} AND contentid=".intval($_GET['del'])."");
					admin_log("删除了内容“ID{$_GET['del']} | {$neirongdb1['title']}”。", $admin_val['name']);
					showmsg("系统提醒", "删除成功！", $links);
				}
			}
			$links[0]['title'] = '确定删除';
			$links[0]['href'] = get_link()."&amp;dosubmit=1";
			$links[1]['title'] = '返回修改页面';
			$links[1]['href'] = get_link("del|edit")."&amp;edit=".$_GET['del'];
			$links[2]['title'] = '返回列表页面';
			$links[2]['href'] = get_link("del|edit");
			showmsg("系统提醒", "确定删除【".$neirongdb1['title']."】吗？", $links);
		}
		
		//批量删除内容
		if ($_GET['dels']){
			$wheresql = "id in (".$_GET['dels'].")";
			$neironglist = $db -> getall("select * from ".table("diy_".$lanmudb['module'])." WHERE {$wheresql} ORDER BY `listorder` DESC ,inputtime DESC");
			if ($_GET['dosubmit']){
				$links[0]['title'] = '返回列表';
				$links[0]['href'] = get_link("dels|dosubmit");
				$links[1]['title'] = '返回后台首页';
				$links[1]['href'] = $_admin_indexurl;
				if (!$db->query("Delete from ".table("diy_".$lanmudb['module'])." where {$wheresql}")){
					showmsg("系统提醒", "删除失败,请稍后再试！", $links);
				}else{
					//统计栏目
					$total_sql="SELECT COUNT(*) AS num FROM ".table("diy_".$lanmudb['module'])." WHERE catid = ".intval($_GET['catid'])."";
					$total_count=$db->get_total($total_sql);
					$db -> query("update ".table('neirong_lanmu')." set items='".$total_count."' WHERE id=".intval($_GET['catid'])."");
					refresh_cache_column($lanmudb['site']);
					//统计模型
					$total_sql="SELECT COUNT(*) AS num FROM ".table("diy_".$lanmudb['module'])."";
					$total_count=$db->get_total($total_sql);
					$db -> query("update ".table('neirong_moxing')." set num='".$total_count."' WHERE id=".$lanmudb['modelid']."");
					cache_field($lanmudb['modelid']);
					//
					foreach ($neironglist as $val) {
						//删除附件
						$_wheresql = "commentid='".$lanmudb['module']."_".$lanmudb['modelid']."_".$val['catid']."_".$val['id']."'";
						$sql = "SELECT * FROM ".table('neirong_fujian')." WHERE {$_wheresql} ORDER BY id asc";
						$arr = $db->getall($sql);
						foreach($arr as $_value) { fujian_del($_value['url']); }
						$db->query("Delete from ".table("neirong_fujian")." WHERE {$_wheresql}");
						//删除评论
						$_wheresql = "commentid REGEXP '^neirong_".$val['catid']."_".$val['id']."_'";
						$db->query("Delete from ".table('pinglun')." WHERE {$_wheresql}");
						$db->query("Delete from ".table('pinglun_data_'.$val['site'])." WHERE {$_wheresql}");
						//
						$db->query("Delete from ".table("neirong_fabu")." where checkid='c-{$val['id']}-{$lanmudb['modelid']}' AND username='{$val['username']}'");
						$db->query("Delete from ".table("sousuo")." where catid={$val['catid']} AND contentid=".intval($val['id'])."");
					}
					//删除数据
					$db->query("Delete from ".table("diy_".$lanmudb['module'])." where {$wheresql}");
					$db->query("Delete from ".table("diy_".$lanmudb['module']."_data")." where {$wheresql}");
					admin_log("删除了内容“ID：{$_GET['dels']}”。", $admin_val['name']);
					showmsg("系统提醒", "删除成功！", $links);
				}
			}
			$links[0]['title'] = '确定删除';
			$links[0]['href'] = get_link()."&amp;dosubmit=1";
			$links[1]['title'] = '返回修改页面';
			$links[1]['href'] = get_link("dels|edit")."&amp;edit=".$_GET['del'];
			$links[2]['title'] = '返回列表页面';
			$links[2]['href'] = get_link("dels|edit");
			$_dtitle = "";
			foreach ($neironglist as $val) {
				$_dtitle.= "<br/>(ID:".$val['id'].")".$val['title']."";
			}
			showmsg("系统提醒", "确定删除以下内容吗？".$_dtitle, $links);
		}
		
		//单独处理字段
		if ($_GET['upfile']){
			if ($_GET['upfile'] == 'thumb'){
				if ($_GET['dosubmit']){
					$data = getcache(KF_ROOT_PATH.'caches/model/model_field_'.$lanmudb['modelid'].'.cache.php');
					if ($data[$_GET['upfile']]['issystem']=='1'){
						$db -> query("update ".table("diy_".$lanmudb['module'])." set `{$_GET['upfile']}`='' WHERE id=".$_GET['edit']."");
					}else{
						$db -> query("update ".table("diy_".$lanmudb['module']."_data")." set `{$_GET['upfile']}`='' WHERE id=".$_GET['edit']."");
					}
					$_wheresql = "commentid='".$lanmudb['module']."_".$lanmudb['modelid']."_".$_GET['catid']."_".$_GET['edit']."' AND field='{$_GET['upfile']}'";
					$sql = "SELECT * FROM ".table('neirong_fujian')." WHERE {$_wheresql} ORDER BY id asc";
					$arr = $db->getall($sql);
					foreach($arr as $_value) { fujian_del($_value['url']); }
					$db->query("Delete from ".table("neirong_fujian")." WHERE {$_wheresql}");
					re_subtitle($_GET['edit'], $lanmudb['module']);
					
					admin_log("删除了缩略图{$_wheresql}。", $admin_val['name']);
					$links[0]['title'] = '返回修改页面';
					$links[0]['href'] = get_link("upfile|edit|dosubmit")."&amp;edit=".$_GET['edit'];
					$links[1]['title'] = '返回列表页面';
					$links[1]['href'] = get_link("upfile|edit|dosubmit");
					$links[2]['title'] = '返回后台首页';
					$links[2]['href'] = $_admin_indexurl;
					showmsg("系统提醒", "删除成功。", $links);
				}else{
					$links[0]['title'] = '确定删除';
					$links[0]['href'] = get_link()."&amp;dosubmit=1";
					$links[1]['title'] = '返回修改页面';
					$links[1]['href'] = get_link("upfile|edit")."&amp;edit=".$_GET['edit'];
					$links[2]['title'] = '返回列表页面';
					$links[2]['href'] = get_link("upfile|edit");
					showmsg("系统提醒", "确定删除此文件吗？", $links);
				}
			}else{
				//删除单个文件
				if ($_GET['fd']){
					if ($_GET['dosubmit']){
						$arr = $db->getone("SELECT * FROM ".table('neirong_fujian')." WHERE id=".intval($_GET['fd'])."");
						fujian_del($arr['url']);
						$db->query("Delete from ".table("neirong_fujian")." WHERE id=".intval($_GET['fd'])."");
						//更新字段
						$data = getcache(KF_ROOT_PATH.'caches/model/model_field_'.$lanmudb['modelid'].'.cache.php');
						$_poarr = $data[$_GET['upfile']];
						$_wheresql = "commentid='".$lanmudb['module']."_".$lanmudb['modelid']."_".$_GET['catid']."_".$_GET['edit']."' AND field='{$_GET['upfile']}'";
						$__val = array();
						$arr = $db->getall("SELECT * FROM ".table('neirong_fujian')." WHERE {$_wheresql} ORDER BY id asc");
						foreach($arr as $_value){
							$__val[$_value['id']]= $_value['allurl'];
						}
						if ($_poarr['issystem']=='1'){
							$db -> query("update ".table("diy_".$lanmudb['module'])." set `{$_GET['upfile']}`='".array2string($__val)."' WHERE id=".$_GET['edit']."");
						}else{
							$db -> query("update ".table("diy_".$lanmudb['module']."_data")." set `{$_GET['upfile']}`='".array2string($__val)."' WHERE id=".$_GET['edit']."");
						}
						admin_log("删除了附件{$_wheresql}。", $admin_val['name']);
						re_subtitle($_GET['edit'], $lanmudb['module']);
						
						$links[0]['title'] = '返回文件列表';
						$links[0]['href'] = get_link("fd|dosubmit")."";
						$links[1]['title'] = '返回修改页面';
						$links[1]['href'] = get_link("fd|dosubmit|upfile")."";
						showmsg("系统提醒", "删除成功。", $links);
					}else{
						$links[0]['title'] = '确定删除';
						$links[0]['href'] = get_link()."&amp;dosubmit=1";
						$links[1]['title'] = '返回文件列表';
						$links[1]['href'] = get_link("fd")."";
						showmsg("系统提醒", "确定删除此文件吗？", $links);
					}
				}
				//编辑单个文件
				if ($_GET['fe']){
					$_valtime = '';
					if ($_POST['dosubmit']){
						$db -> query("update ".table('neirong_fujian')." set `body`='".$_POST['body']."',`name`='".$_POST['name']."' WHERE id=".intval($_GET['fe'])."");
						$_valtime.= "<b>修改成功！</b><br/>";
					}
					$arr = $db->getone("SELECT * FROM ".table('neirong_fujian')." WHERE id=".intval($_GET['fe'])."");
						
					$_valtime.= "文件地址:<br/>".$arr['allurl']."<br/>";
					$_valtime.= format_form(array('set'=>'头','notvs'=>'1'));
					$_valtime.= "文件名称:<br/>".format_form(array("set" => "输入框|名称:'name".fenmiao()."'", "data_value" => $arr['name']))."<br/>";
					if ($_GET['vs']==1){
						$_valtime.= "文件说明:<br/>".format_form(array("set" => "输入框|名称:'body".fenmiao()."'", "data_value" => $arr['body']))."<br/>";
						$_valtime.= '
								<anchor title="提交">提交修改
								<go href="'.get_link().'" method="post" accept-charset="utf-8">
								<postfield name="body" value="$(body'.fenmiao().')"/>
								<postfield name="name" value="$(name'.fenmiao().')"/>
								<postfield name="dosubmit" value="1"/>
								</go></anchor>									
							';
					}else{
						$_valtime.= "文件说明:<br/>".format_form(array("set" => "文本框|名称:'body".fenmiao()."'", "data_value" => $arr['body']))."<br/>";
					}
					$_valtime.= format_form(array('set'=>'按钮|名称:dosubmit,值:提交修改','notvs'=>'1'));
					$_valtime.= format_form(array('set'=>'尾','notvs'=>'1'));
					admin_log("修改了附件ID{$_GET['fe']}。", $admin_val['name']);
					$links[0]['title'] = '返回列表';
					$links[0]['href'] = get_link("fe")."";
					$links[1]['title'] = '返回内容修改';
					$links[1]['href'] = get_link("fe|upfile")."";
					showmsg("修改文件说明", $_valtime, $links);
				}
				$templatefile = $__templatefile."/upfile";
				$data = getcache(KF_ROOT_PATH.'caches/model/model_field_'.$lanmudb['modelid'].'.cache.php');

				$wheresql = "id=".intval($_GET['edit'])."";
				$neirongdb1 = $db -> getone("select * from ".table("diy_".$lanmudb['module'])." WHERE {$wheresql} LIMIT 1");
				if (!$neirongdb1) { showmsg("系统提醒", "要修改的内容ID".intval($_GET['edit'])."不存在！"); }
					
				//上传文件
				if ($_POST['dosubmit'] && !$_POST['nextsubmit']) {
					//模型(判断是否论坛模型进行更新副标题)
					$moxingdata = getcache(KF_ROOT_PATH.'caches/cache_neirong_moxing.php');
					$moxingdata = $moxingdata[$lanmudb['modelid']];
					if ($moxingdata['type'] == "bbs"){
						$__post['subtitle'] = $neirongdb1['subtitle'];
					}
					//开始上传处理
					$_wheresql = "commentid='".$lanmudb['module']."_".$lanmudb['modelid']."_".$_GET['catid']."_".$_GET['edit']."' AND field='{$_GET['upfile']}'";
					$total_sql="SELECT COUNT(*) AS num FROM ".table("neirong_fujian")." WHERE {$_wheresql}";
					$total_count=$db->get_total($total_sql);
					kf_class::run_sys_func('upload');
					$_poarr = $data[$_GET['upfile']];
					$_poarrset = string2array($_poarr['setting']);
					$_fi = 0;
					foreach($_FILES as $_k=>$_v) {
						if(strpos($_k, $_GET['upfile']) === false) continue; //跳过不是正常表单的上传
						if (!$_FILES[$_k]['name']) continue; //跳过为空的上传
						if ($total_count >= intval($_poarrset['upload_number']) && $_poarrset['upload_number']) showmsg("系统提醒", "“{$_poarr['name']}”最多允许上传{$_poarrset['upload_number']}个文件，现已经上传达到{$total_count}个！");
						if ($total_count+$_fi >= intval($_poarrset['upload_number']) && $_poarrset['upload_number']) continue; //跳过数量
						//上传格式
						$_file_allowext = $_poarrset['upload_allowext'];
						if ($_poarr['type'] == 'image') $_file_allowext = "gif|jpg|jpeg|png|bmp";
						if ($_poarr['type'] == 'downfile' && empty($_file_allowext)) $_file_allowext = "rar|zip|jar|apk|7z";
						if (!$_file_allowext) showmsg("系统提醒", "“{$_poarr['name']}”未设置允许上传的文件类型，请先设置！");
						$_file_allowext = str_replace('|', '/', $_file_allowext);
						$_file_allowext = str_replace(',', '/', $_file_allowext);
						//上传大小
						$_file_size = intval(ini_get('upload_max_filesize')*1024);
						//目录构造
						$up_dir_0 = "uploadfiles/content/default/";
						make_dir('./'.$up_dir_0.date("Y/m/d/"));
						//开始上传
						$_file[$_k] = _asUpFiles('./'.$up_dir_0.date("Y/m/d/"), $_k, $_file_size, $_file_allowext, true);
						if ($_file[$_k]){
							$_timepost[$_k] = $_CFG['site_domain'].$_CFG['site_dir'].$up_dir_0.date("Y/m/d/").$_file[$_k];
							//处理附件表
							$_urlA = array(
									'title' => $neirongdb1['title'],
									'name' => $_FILES[$_k]['name'],
									'body' => $_POST[$_k],
									'url' => './'.$up_dir_0.date("Y/m/d/").$_file[$_k],
									'allurl' => $_timepost[$_k],
									'field' => $_GET['upfile'],
							);
							//第一张作为缩略图
							if (!empty($_poarrset['uponethumb']) && empty($neirongdb1['thumb'])){
								$get_ext = strtolower(get_extension($_timepost[$_k]));
								if (in_array($get_ext, array('jpg','jpeg','png','gif','bmp'))){
									$up_dir_100 = "uploadfiles/content/100/";
									$up_dir_48 = "uploadfiles/content/48/";
									make_dir('./'.$up_dir_100.date("Y/m/d/"));
									make_dir('./'.$up_dir_48.date("Y/m/d/"));
									makethumb('./'.$up_dir_0.date("Y/m/d/").$_file[$_k], './'.$up_dir_100.date("Y/m/d/"), 100, 100);
									makethumb('./'.$up_dir_0.date("Y/m/d/").$_file[$_k], './'.$up_dir_48.date("Y/m/d/"), 48, 48);
									$_timethumb = array();
									$_timethumb['0'] = 		$_timepost[$_k];
									$_timethumb['100'] = 	$_CFG['site_domain'].$_CFG['site_dir'].$up_dir_100.date("Y/m/d/").$_file[$_k];
									$_timethumb['48'] = 	$_CFG['site_domain'].$_CFG['site_dir'].$up_dir_48.date("Y/m/d/").$_file[$_k];
									$neirongdb1['thumb'] = array2string($_timethumb);
									updatetable(table("diy_".$lanmudb['module']), array("thumb" => $neirongdb1['thumb']), "`id`='{$_GET['edit']}'");
								}
							}
							//
							_db_fujian($lanmudb['module']."_".$lanmudb['modelid']."_".$neirongdb1['catid']."_".$neirongdb1['id'], $lanmudb['modelid'], $_urlA, 1);
							$__post['subtitle'] = to_subtitle($_file[$_k], $__post['subtitle'], 1);
							$_fi++;
						}else{
							showmsg("系统提醒", "“{$_poarr['name']}”上传失败！");
						}
					}
					//论坛模式更新副标题
					if ($moxingdata['type'] == "bbs"){
						updatetable(table("diy_".$lanmudb['module']), array("subtitle" => $__post['subtitle']), "`id`='{$_GET['edit']}'");
					}
					//更新字段
					$__val = array();
					$arr = $db->getall("SELECT * FROM ".table('neirong_fujian')." WHERE {$_wheresql} ORDER BY id asc");
					foreach($arr as $_value){
						$__val[$_value['id']]= $_value['allurl'];
					}
					if ($_poarr['issystem']=='1'){
						$db -> query("update ".table("diy_".$lanmudb['module'])." set `{$_GET['upfile']}`='".array2string($__val)."' WHERE id=".$_GET['edit']."");
					}else{
						$db -> query("update ".table("diy_".$lanmudb['module']."_data")." set `{$_GET['upfile']}`='".array2string($__val)."' WHERE id=".$_GET['edit']."");
					}
					admin_log("上传了附件。{$_wheresql}", $admin_val['name']);
					$links[0]['title'] = '继续上传';
					$links[0]['href'] = get_link("dosubmit")."";
					$links[1]['title'] = '返回修改页面';
					$links[1]['href'] = get_link("upfile|upi|dosubmit|upisubmit");
					if ($_fi == 0) showmsg("系统提醒", "没有选择任何要上传的文件！");
					showmsg("系统提醒", "上传完成！", $links);
				}
					
				$_fujian = array();
				$_wheresql = "commentid='".$lanmudb['module']."_".$lanmudb['modelid']."_".$_GET['catid']."_".$_GET['edit']."' AND field='{$_GET['upfile']}'";
				$sql = "SELECT * FROM ".table('neirong_fujian')." WHERE {$_wheresql} ORDER BY id asc";
				$arr = $db->getall($sql);
				foreach($arr as $_value){
					$_fujian[$_value['id']] = $_value;
				}
				$_POST['upi'] = (intval($_POST['upi'])>0)?intval($_POST['upi']):'1';
				$_POST['upi'] = (intval($_POST['upi'])>50)?'50':intval($_POST['upi']);
				$__input = "";
				for ($i=1; $i<=$_POST['upi']; $i++) {
					$__input.= '文件'.$i.':<input type="file" name="'.$_GET['upfile'].'_'.$i.'" size="10" /><br/>'.chr(13);
					$__input.= '说明'.$i.':<textarea name="'.$_GET['upfile'].'_'.$i.'"></textarea> <br/>'.chr(13);
				}
				
				kf_class::run_sys_func('upload');
				$upconfig = getcache(KF_ROOT_PATH. "caches/caches_peizhi_mokuai/cache.fujian.php");
				$max_size = intval(ini_get('upload_max_filesize')*1024);
				if ($upconfig['upload_maxsize'] > 0 && $upconfig['upload_maxsize'] < $max_size) $max_size = $upconfig['upload_maxsize'];

				$smarty->assign('__input', $__input);
				$smarty->assign('fudata', $data[$_GET['upfile']]);
				$smarty->assign('fudatasetting', string2array($data[$_GET['upfile']]['setting']));
				$smarty->assign('fudatasettingone', _formatSize($max_size*1024)); 
				$smarty->assign('fujian', $_fujian);
			}
		}
			
		//修改内容
		if ($_GET['edit'] && !$_GET['upfile']){
			$templatefile = $__templatefile."/edit";
			kf_class::run_sys_func('form');
			$data = getcache(KF_ROOT_PATH.'caches/model/model_field_'.$lanmudb['modelid'].'.cache.php');

			$wheresql = "id=".intval($_GET['edit'])."";
			$neirongdb1 = $db -> getone("select * from ".table("diy_".$lanmudb['module'])." WHERE {$wheresql} LIMIT 1");
			if (!$neirongdb1) { showmsg("系统提醒", "要修改的内容ID".intval($_GET['edit'])."不存在！"); }
			$neirongdb2 = $db -> getone("select * from ".table("diy_".$lanmudb['module']."_data")." WHERE {$wheresql} LIMIT 1");
			$neirongdb = empty($neirongdb2)?$neirongdb1:array_merge($neirongdb1,$neirongdb2);
			//$neirongdb = array_map('htmlspecialchars_decode',$neirongdb);

			if (isset($_GET['status'])){
				if ($neirongdb1['status']==98 && !$_GET['dosubmit']){
					$links[0]['title'] = '确定修改';
					$links[0]['href'] = get_link("dosubmit").'&amp;dosubmit=1';
					$links[1]['title'] = '返回内容修改页面';
					$links[1]['href'] = get_link("status");
					showmsg("系统提醒", "此内容当前状态为草稿，如果修改状态可能会影响会员的操作，是否确定修改？", $links);
				}
				set_shenhe("diy_".$lanmudb['module'], $_GET['status'], $neirongdb1['id'], $admin_val['name']);
				$links[0]['title'] = '返回继续修改';
				$links[0]['href'] = get_link("status|dosubmit");
				showmsg("系统提醒", "修改审核状态成功。", $links, $links[0]['href']);
			}

			if ($_POST['dosubmit']) {
				$__post = array();
				$__post_data = array();
				//$__post['catid'] = intval($_GET['catid']);
				//$__post['username'] = $admin_val['name'];
				//$__post['site'] = $_CFG['site'];
				//$__post['sysadd'] = 1;
				$__post['subtitle'] = $neirongdb['subtitle'];
				$contentaddition = "";
				foreach($_POST as $_k=>$_v) {
					if (substr($_k, 0, 8) == "bbsinfo_"){
						switch (substr($_k, 12)) {
							case 'yin': //隐藏贴
								if (empty($_v)) showmsg("系统提醒", "请输入要隐藏的内容。");
								$contentaddition.= "[bbs=yin]{$_v}[/bbs]";
								$__post['subtitle'] = to_subtitle("隐", $__post['subtitle']);
								break;
							case 'pai': //派发贴
								if ($_v < $_POST[substr($_k, 0, 12)."hui"]){
									showmsg("系统提醒", "回复奖励不能大于总派发数。");
								}
								if ($_POST[substr($_k, 0, 12)."huobi"] == "amount"){
									if ($_v < 0.01){
										showmsg("系统提醒", "总派发必须大于0.01。");
									}
									if ($_POST[substr($_k, 0, 12)."hui"] < 0.01){
										showmsg("系统提醒", "回复奖励必须大于0.01。");
									}
									//if ($huiyuan_val['amount'] < $_v) showmsg("系统提醒", "你的{$_CFG['amountname']}不足。");
								}elseif ($_POST[substr($_k, 0, 12)."huobi"] == "point"){
									$_v = intval($_v);
									$_POST[substr($_k, 0, 12)."hui"] = intval($_POST[substr($_k, 0, 12)."hui"]);
									if ($_v <= 0){
										showmsg("系统提醒", "积分总派发必须大于0。");
									}
									if ($_POST[substr($_k, 0, 12)."hui"] <= 0){
										showmsg("系统提醒", "积分回复奖励必须大于0。");
									}
									//if ($huiyuan_val['point'] < $_v) showmsg("系统提醒", "你的积分不足。");
								}else{
									showmsg("系统提醒", "请选择正确派发类型。");
								}
								$contentaddition.= "[bbs=pai]".$_POST[substr($_k, 0, 12)."huobi"]."|".$_v."|".$_POST[substr($_k, 0, 12)."hui"]."[/bbs]";
								$__post['subtitle'] = to_subtitle("派", $__post['subtitle']);
								break;
							case 'xuan': //悬赏贴
								if (intval($_POST[substr($_k, 0, 12)."tian"]) < 1){
									showmsg("系统提醒", "悬赏天数不得小于1。");
								}
								if ($_POST[substr($_k, 0, 12)."huobi"] == "amount"){
									if ($_v < 0.01){
										showmsg("系统提醒", "悬赏{$_CFG['amountname']}必须大于0.01。");
									}
									//if ($huiyuan_val['amount'] < $_v) showmsg("系统提醒", "你的{$_CFG['amountname']}不足。");
								}elseif ($_POST[substr($_k, 0, 12)."huobi"] == "point"){
									$_v = intval($_v);
									if ($_v <= 0){
										showmsg("系统提醒", "悬赏积分必须大于0。");
									}
									//if ($huiyuan_val['point'] < $_v) showmsg("系统提醒", "你的积分不足。");
								}else{
									showmsg("系统提醒", "请选择正确悬赏类型。");
								}
								$contentaddition.= "[bbs=xuan]".$_POST[substr($_k, 0, 12)."huobi"]."|".$_v."|".intval($_POST[substr($_k, 0, 12)."tian"])."[/bbs]";
								$__post['subtitle'] = to_subtitle("赏", $__post['subtitle']);
								break;
							case 'mai': //收费贴
								$maitxt = $_POST[substr($_k, 0, 12)."maitxt"];
								$maitxt = str_replace('|', '', $maitxt);
								if (empty($maitxt)) showmsg("系统提醒", "请输入要收费的内容。");
								if ($_POST[substr($_k, 0, 12)."huobi"] == "amount"){
									if ($_v < 0.01){
										showmsg("系统提醒", "收费{$_CFG['amountname']}必须大于0.01。");
									}
								}elseif ($_POST[substr($_k, 0, 12)."huobi"] == "point"){
									$_v = intval($_v);
									if ($_v <= 0){
										showmsg("系统提醒", "收费积分必须大于0。");
									}
								}else{
									showmsg("系统提醒", "请选择正确收费类型。");
								}
								$contentaddition.= "[bbs=mai]".$_POST[substr($_k, 0, 12)."huobi"]."|".$_v."|".$maitxt."[/bbs]";
								$__post['subtitle'] = to_subtitle("卖", $__post['subtitle']);
								break;
						}
						continue;
					}
					$_poarr = $data[$_k];
					$_poarrset = string2array($_poarr['setting']);
					//最小长度
					if (strlen($_v) < intval($_poarr['minlength']) && intval($_poarr['minlength']) > 0){
						showmsg("系统提醒", "“{$_poarr['name']}”最小输入长度为".intval($_poarr['minlength'])."。");
					}
					//最大长度
					if (strlen($_v) > intval($_poarr['maxlength']) && intval($_poarr['maxlength']) > 0){
						if ($_poarr['field'] == 'description'){
							$_v = substr($_v, 0, intval($_poarr['maxlength']));
						}else{
							showmsg("系统提醒", "“{$_poarr['name']}”最大输入长度为".intval($_poarr['maxlength'])."。");
						}
					}
					//数字字段
					if ($_poarr['type'] == 'number'){
						//小数位数
						if ($_poarrset['decimaldigits'] > 0){
							$_v = round($_v, $_poarrset['decimaldigits']);
						}else{
							$_v = intval($_v);
						}
						//取值范围
						if ($_v < $_poarrset['minnumber'] && $_poarrset['minnumber']){
							showmsg("系统提醒", "“{$_poarr['name']}”取值范围最小值为{$_poarrset['minnumber']}。");
						}
						if ($_v > $_poarrset['maxnumber'] && $_poarrset['maxnumber']){
							showmsg("系统提醒", "“{$_poarr['name']}”取值范围最大值为{$_poarrset['maxnumber']}。");
						}
					}
					//时间字段
					if ($_poarr['type'] == 'datetime'){
						$_v = strtotime($_v);
					}
					//多选字段
					if ($_poarr['type'] == 'box' && $_poarrset['boxtype']){
						$_vtime = '';
						foreach($_v as $_kk=>$_vv) {
							if (!empty($_vv)) $_vtime.= $_vv.'///';
						}$_v = rtrim($_vtime, '///');
					}
					//收费字段
					if($_k == 'paytype'){
						$__post_data['paytype'] = $_v;
					}
					//字段主副
					if ($_poarr['issystem'] == '1') $__post[$_k] = $_v;
					if ($_poarr['issystem'] == '0') {
						//内容字段判断替换br
						if ($_poarrset['newline2br']){
							$__post_data[$_k] = $_v;
						}else{
							$__post_data[$_k] = nl2br($_v);
						}
					}
				}
				$__post['subtitle'] = to_subtitle($__post_data['content'], $__post['subtitle'], 2);
				kf_class::run_sys_func('upload');
				foreach($_FILES as $_k=>$_v) {
					$_poarr = $data[$_k];
					$_poarrset = string2array($_poarr['setting']);
					//最小长度
					if (intval($_poarr['minlength']) > 0){
						!$_FILES[$_k]['name']?showmsg("系统提醒", "请选择要上传的“{$_poarr['name']}”"):"";
					}
					//跳过
					if (!$_FILES[$_k]['name'] && intval($_poarr['minlength']) == 0){
						continue;
					}
					//上传格式
					$_file_allowext = $_poarrset['upload_allowext'];
					if ($_poarr['type'] == 'image') $_file_allowext = "gif|jpg|jpeg|png|bmp";
					if ($_poarr['type'] == 'downfile' && empty($_file_allowext)) $_file_allowext = "rar|zip|jar|apk|7z";
					if (!$_file_allowext) showmsg("系统提醒", "“{$_poarr['name']}”未设置允许上传的文件类型，请先设置！");
					$_file_allowext = str_replace('|', '/', $_file_allowext);
					$_file_allowext = str_replace(',', '/', $_file_allowext);
					//上传大小
					$_file_size = intval(ini_get('upload_max_filesize')*1024);
					//目录构造
					$up_dir_0 = "uploadfiles/content/default/";
					$up_dir_100 = "uploadfiles/content/100/";
					$up_dir_48 = "uploadfiles/content/48/";
					make_dir('./'.$up_dir_0.date("Y/m/d/"));
					make_dir('./'.$up_dir_100.date("Y/m/d/"));
					make_dir('./'.$up_dir_48.date("Y/m/d/"));
					//开始上传
					$_file[$_k] = _asUpFiles('./'.$up_dir_0.date("Y/m/d/"), $_k, $_file_size, $_file_allowext, true);
					if ($_file[$_k]){
						makethumb('./'.$up_dir_0.date("Y/m/d/").$_file[$_k], './'.$up_dir_100.date("Y/m/d/"), 100, 100);
						makethumb('./'.$up_dir_0.date("Y/m/d/").$_file[$_k], './'.$up_dir_48.date("Y/m/d/"), 48, 48);
						$_timepost[$_k]['0'] = 		$_CFG['site_domain'].$_CFG['site_dir'].$up_dir_0.date("Y/m/d/").$_file[$_k];
						$_timepost[$_k]['100'] = 	$_CFG['site_domain'].$_CFG['site_dir'].$up_dir_100.date("Y/m/d/").$_file[$_k];
						$_timepost[$_k]['48'] = 	$_CFG['site_domain'].$_CFG['site_dir'].$up_dir_48.date("Y/m/d/").$_file[$_k];
						if ($_poarr['issystem'] == '1') $__post[$_k] = array2string($_timepost[$_k]);
						if ($_poarr['issystem'] == '0') $__post_data[$_k] = array2string($_timepost[$_k]);
						//处理附件表
						$_urlA = array(
								'title' => $__post['title'],
								'name' => $_FILES[$_k]['name'],
								'url' => './'.$up_dir_0.date("Y/m/d/").$_file[$_k],
								'allurl' => $_timepost[$_k]['0'],
								'field' => $_k,
						);
						$__post['subtitle'] = to_subtitle("图", $__post['subtitle']);
						_db_fujian($lanmudb['module']."_".$lanmudb['modelid']."_".$neirongdb['catid']."_".$neirongdb['id'], $lanmudb['modelid'], $_urlA, 1);
					}else{
						showmsg("系统提醒", "“{$_poarr['name']}”上传失败！");
					}
				}
				$__post['description'] = $__post['description']?$__post['description']:substr(strip_tags($__post_data['content']),0,255);
				if (!updatetable(table("diy_".$lanmudb['module']), $__post, $wheresql)){
					showmsg("修改失败", "修改失败，网络繁忙请稍后再试！");
				}else{
					/*
						//统计栏目
						$total_sql="SELECT COUNT(*) AS num FROM ".table("diy_".$lanmudb['module'])." WHERE catid = ".intval($_GET['catid'])."";
						$total_count=$db->get_total($total_sql);
						$db -> query("update ".table('neirong_lanmu')." set items='".$total_count."' WHERE id=".intval($_GET['catid'])."");
						refresh_cache_column($lanmudb['site']);
						//统计模型
						$total_sql="SELECT COUNT(*) AS num FROM ".table("diy_".$lanmudb['module'])."";
						$total_count=$db->get_total($total_sql);
						$db -> query("update ".table('neirong_moxing')." set num='".$total_count."' WHERE id=".$lanmudb['modelid']."");
						cache_field($lanmudb['modelid']);
						*/
					//保存到审核列表中
					updatetable(table("neirong_fabu"), array('title'=>$__post['title']), "checkid='c-{$_GET['edit']}-{$lanmudb['modelid']}'");
					//定义返回
					$links[0]['title'] = '继续修改';
					$links[0]['href'] = get_link("dosubmit");
					$links[1]['title'] = '返回列表';
					$links[1]['href'] = get_link("edit|dosubmit");
					$_wheresql = "modelid={$lanmudb['modelid']} AND status!=1 AND hide=0 AND type in ('downfile','images')";
					$sql = "SELECT * FROM ".table('neirong_moxing_ziduan')." WHERE {$_wheresql} ORDER BY listorder asc,id asc";
					$arr = $db->getall($sql); $_i = 2;
					foreach($arr as $_value) {
						$links[$_i]['title'] = '进入:'.$_value['name'];
						$links[$_i]['href'] = get_link("upfile|edit|dosubmit")."&amp;edit={$_GET['edit']}&amp;upfile=".$_value['field'];
						$_i++;
					}
					//全站搜索
					$fulltext = $__post['title'];
					$fulltext.= $__post['keywords']?$__post['keywords']:strip_tags($__post_data['content']);
					install_search(intval($_GET['edit']), intval($_GET['catid']), $lanmudb['modelid'], $__post['title'], $fulltext, $__post['title'], $__post['description'], $__post['inputtime'], $_CFG['site']);
					//移动内容 start
					if ($_POST['yidonglanmuid'] > 0 && $_POST['yidonglanmuid'] != $neirongdb['catid']){
						//
						$newcatid = intval($_POST['yidonglanmuid']);
						$oldfj = $lanmudb['module']."_".$lanmudb['modelid']."_".$neirongdb['catid']."_".$neirongdb['id'];
						$newfj = $lanmudb['module']."_".$lanmudb['modelid']."_".$newcatid."_".$neirongdb['id'];
						$oldpl = "neirong_".$neirongdb['catid']."_".$neirongdb['id']."_".$_CFG['site'];
						$newpl = "neirong_".$newcatid."_".$neirongdb['id']."_".$_CFG['site'];
						//
						$newlanmu = $db->getone("select * from ".table('neirong_lanmu')." WHERE `id`='{$newcatid}'");
						if (empty($newlanmu)) showmsg("系统提醒", "移至的栏目不存在！");
						if ($newlanmu['module'] != $lanmudb['module']) showmsg("系统提醒", "移至的栏目跟现在的栏目不是用一种模型禁止移动！");
						//
						$db -> query("update ".table("diy_".$lanmudb['module'])." set `catid`='".$newcatid."' WHERE `id`=".$neirongdb['id']."");
						$db -> query("update ".table("neirong_fabu")." set `catid`='".$newcatid."' WHERE `checkid`='".$_GET['checkid']."'");
						$db -> query("update ".table("neirong_fujian")." set `commentid`='".$newfj."' WHERE `commentid`='".$oldfj."'");
						$db -> query("update ".table("pinglun")." set `commentid`='".$newpl."' WHERE `commentid`='".$oldpl."'");
						$db -> query("update ".table("pinglun_data_".$_CFG['site'])." set `commentid`='".$newpl."' WHERE `commentid`='".$oldpl."'");
						$db -> query("update ".table("sousuo")." set `catid`=".$newcatid." WHERE `catid`=".$neirongdb['catid']." AND `contentid`=".$neirongdb['id']."");
						$db -> query("update ".table("xinqing")." set `catid`=".$newcatid." WHERE `catid`=".$neirongdb['catid']." AND `contentid`=".$neirongdb['id']."");
						//统计栏目(旧)
						$total_sql="SELECT COUNT(*) AS num FROM ".table("diy_".$lanmudb['module'])." WHERE catid = ".$neirongdb['catid']."";
						$total_count=$db->get_total($total_sql);
						$db -> query("update ".table('neirong_lanmu')." set items='".$total_count."' WHERE id=".$neirongdb['catid']."");
						//统计栏目(新)
						$total_sql="SELECT COUNT(*) AS num FROM ".table("diy_".$lanmudb['module'])." WHERE catid = ".$newcatid."";
						$total_count=$db->get_total($total_sql);
						$db -> query("update ".table('neirong_lanmu')." set items='".$total_count."' WHERE id=".$newcatid."");
						refresh_cache_column($lanmudb['site']);
						//
						$neirongdb['catid'] = $newcatid;
						$links = array();
						$links[0]['title'] = '继续修改';
						$links[0]['href'] = get_link("dosubmit|catid")."&amp;catid=".$neirongdb['catid'];
						$links[1]['title'] = '返回列表';
						$links[1]['href'] = get_link("edit|dosubmit|catid")."&amp;catid=".$neirongdb['catid'];
					}
					//移动内容 end
					if (!empty($_POST['sys_editwhy'])) { //通知作者
						kf_class::run_sys_func('xinxi');
						$_urlarr = array(
							'm'=>'neirong',
							'c'=>'show',
							'catid'=>$neirongdb['catid'],
							'id'=>$neirongdb['id'],
							'sid'=>"{sid}",
							'vs'=>"{vs}",
						);
						add_message($neirongdb['username'], 0, "管理员修改了你发布的内容", "被修改内容：<a href=\"".url_rewrite("KF_neirongshow", $_urlarr)."\">{$__post['title']}</a><br/>修改人员：系统管理员<br/>修改原因：".$_POST['sys_editwhy']);
					}
					$links[$_i+2]['title'] = '返回后台首页';
					$links[$_i+2]['href'] = $_admin_indexurl;
					$__post_data['content'].= $contentaddition?$contentaddition:"";
					!empty($__post_data)? updatetable(table("diy_".$lanmudb['module']."_data"), $__post_data, $wheresql):"";
					admin_log("修改了内容“ID{$_GET['edit']} | {$__post['title']}”到栏目ID“{$_GET['catid']} | {$lanmudb['title']}”；理由：{$_POST['sys_editwhy']}。", $admin_val['name']);
					showmsg("系统提醒", "修改成功！", $links);
				}
			}else{
				//
				kf_class::run_sys_class('tree','',0);
				$tree= new tree();
				$tree->icon = array('　│ ','　├─ ','　└─ ');
				$tree->nbsp = '　';
				$categorys = array();
				$__n = 0;
				//栏目
				$lanmudata = getcache(KF_ROOT_PATH.'caches/column/cache.'.$_CFG['site'].'.php');
				foreach($lanmudata as $r) {
					//$option = ($_fabu['catid'] == $r['id'])?' selected=\'selected\'':'';
					if (!empty($r['arrchildid'])){
						$r['option'] = "<option value='0'{$option}>x";
					}else{
						if ($r['module'] != $lanmudb['module']){
							continue;
						}
						$r['option'] = "<option value='{$r['id']}'{$option}>√";
						$__n++;
					}
					$categorys[$r['id']] = $r;
				}
				$str  = "\$option\$spacer\$title</option><br/>";
				$tree->init($categorys);
				$_categorys = $tree->get_tree(0, $str);
				$smarty->assign('categorys', $_categorys);
			}
			$ziduandata = array();
			$ziduandata2 = array();
			foreach($data as $r) {
				//处理收费字段
				if($r['field'] == 'readpoint'){
					$r['_paytype'] = $neirongdb['paytype'];
				}
				if ($r['status'] != '1' && $r['hide'] == '0'){
					if ($r['type'] != 'catid' && $r['type'] != 'typeid'){
						$r['__defaultvalue'] = $neirongdb[$r['field']];
						$ziduandata[$r['id']] = $r;
					}
				}elseif($r['field'] == 'updatetime'){
					$ziduandata2[$r['id']] = $r;
				}
			}
			$smarty->assign('neirongdb', $neirongdb);
			$smarty->assign('ziduandata', array_merge($ziduandata,$ziduandata2));
		}
	}else{
		$templatefile = $__templatefile."/index";
		kf_class::run_sys_class('tree','',0);
		$tree= new tree();
		$tree->icon = array('　│ ','　├─ ','　└─ ');
		$tree->nbsp = '　';
		$categorys = array();
		$data = getcache(KF_ROOT_PATH.'caches/column/cache.'.$_CFG['site'].'.php');
		foreach($data as $r) {
			if (!empty($r['arrchildid'])){
				$r['titleurl'] = "<u>{$r['title']}({$r['items']})</u>";
			}else{
				$r['titleurl'] = "<a href='".get_link()."&amp;catid={$r['id']}'>{$r['title']}({$r['items']})</a>";
			}
			$categorys[$r['id']] = $r;
		}
		$str  = "\$id.\$spacer\$titleurl<br/>";
		$tree->init($categorys);
		$categorys = $tree->get_tree(0, $str);
		$smarty->assign('categorys', $categorys);
	}
}

?>