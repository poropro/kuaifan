<?php
/*
 * 数据库管理
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');


kf_class::run_sys_func('database');
if ($_GET['a'] == 'huanyuan') {
	if($_GET['dosubmit']) {
		if ($admin_val['id']!='1') showmsg("系统提醒", "只有网站创始人才可以操作！");
		$_pre = trim($_GET['pre']);
		admin_log("还原数据库！", $admin_val['name']);
		//数据库还原
		if($_GET['confirm']) {
			import_database($_pre,$_GET['fileid']);
		}else{
			showmsg("系统提醒", "你确定还原数据库吗？<br/><a href=\"".get_link('confirm')."&amp;confirm=1\">确定还原</a>");
		}
	}else{
		$sqlfiles = glob(KF_ROOT_PATH.'caches/bakup/default/*.sql');
		if(is_array($sqlfiles)) {
			asort($sqlfiles);
			$prepre = '';
			$info = $infos = $other = $others = array();
			foreach($sqlfiles as $id=>$sqlfile) {
				if(preg_match("/(kuaifancms_[0-9]{8}_[0-9a-z]{4}_)([0-9]{1,5}+)\.sql/i",basename($sqlfile),$num)) {
					$info['filename'] = basename($sqlfile);
					$info['maketime'] = date('Y-m-d H:i:s', filemtime($sqlfile));
					$info['pre'] = $num[1];
					$info['number'] = ($num[2]>$info['number'])?$num[2]:$info['number'];
					if($info['pre'] == $prepre) {
						$info['filesize']+= filesize($sqlfile)/(1024*1024);
					} else {
						$info['filesize'] = filesize($sqlfile)/(1024*1024);
					}
					$prepre = $info['pre'];
					$infos[$info['pre']] = $info;
				} else {
					$other['filename'] = basename($sqlfile);
					$other['filesize'] = filesize($sqlfile)/(1024*1024);
					$other['maketime'] = date('Y-m-d H:i:s',filemtime($sqlfile));
					$others[] = $other;
				}
			}
			foreach ($infos as $_key => $_value) {
				$_pre[$_key] = $_value['pre'];
				$_maketime[$_key] = $_value['maketime'];
			}
			array_multisort($_maketime, $_pre, $infos);
		}
		$smarty->assign('infos', $infos);
		$smarty->assign('others', $others);
	}
}elseif ($_GET['a'] == 'youhua') {
	if($_POST['dosubmit']) {
		$_timeval = '';
		foreach ($_POST as $_k => $_v){
			if (substr($_k,0,10)=='tablename_' && $_v){
				$_timeval.= $_v.',';
			}
		}
		if ($_timeval){
            $_timeval = rtrim(trim($_timeval),',');
			if ($db->query("OPTIMIZE TABLE {$_timeval}")){
				$links[0]['title'] = '返回继续';
				$links[0]['href'] = get_link("dosubmit");
				$links[1]['title'] = '返回后台首页';
				$links[1]['href'] = $_admin_indexurl;
				admin_log("优化数据库。", $admin_val['name']);
				showmsg("系统提醒", "优化成功！", $links);
			}
		}
	}
	$smarty->assign('list_arr',get_optimize_list());
}elseif ($_GET['a'] == 'xiufu') {
    if($_POST['dosubmit']) {
        $_timeval = '';
        foreach ($_POST as $_k => $_v){
            if (substr($_k,0,10)=='tablename_' && $_v){
                $_timeval.= $_v.',';
            }
        }
        if ($_timeval){
            $_timeval = rtrim(trim($_timeval),',');
            if ($db->query("REPAIR TABLE {$_timeval}")){
                $links[0]['title'] = '返回继续';
                $links[0]['href'] = get_link("dosubmit");
                $links[1]['title'] = '返回后台首页';
                $links[1]['href'] = $_admin_indexurl;
                admin_log("修复数据库。", $admin_val['name']);
                showmsg("系统提醒", "修复成功！", $links);
            }
        }
    }
    $smarty->assign('list_arr',get_optimize_list(true));
}elseif ($_GET['a'] == 'del') {
	if ($_GET['dosubmit']){
		if ($admin_val['id']!='1') showmsg("系统提醒", "只有网站创始人才可以操作！");
		$fileid = 1;
		while($fileid>0){
			$filename = $_GET['pre'].$fileid.'.sql';
			$filepath = KF_ROOT_PATH.'caches/bakup'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.$filename;
			if(file_exists($filepath)) {
				if (fujian_del($filepath)){
					$fileid++;
				}else{
					$links[0]['title'] = '返回后台首页';
					$links[0]['href'] = $_admin_indexurl;
					showmsg("系统提醒", "数据文件 {$filename} 删除失败！",$links);
				}
			}else{
				$fileid = 0;
			}
		}
		$links[0]['title'] = '返回列表页面';
		$links[0]['href'] = get_link("a|pre|dosubmit").'&amp;a=huanyuan';
		$links[1]['title'] = '返回后台首页';
		$links[1]['href'] = $_admin_indexurl;
		admin_log("删除名为【".rtrim($_GET['pre'],'_')."】的数据库备份项目！", $admin_val['name']);
		showmsg("系统提醒", "成功删除名为【".rtrim($_GET['pre'],'_')."】的数据库备份项目！", $links);
	}
	$links[0]['title'] = '确定删除';
	$links[0]['href'] = get_link()."&amp;dosubmit=1";
	$links[1]['title'] = '返回列表页面';
	$links[1]['href'] = get_link("a|pre").'&amp;a=huanyuan';
	showmsg("系统提醒", "确定删除名为【".rtrim($_GET['pre'],'_')."】的数据库备份项目吗？", $links);

}else{
	if ($_REQUEST['dosubmit']){
		//传递变量
		$sqlcharset = $_POST['sqlcharset'] ? $_POST['sqlcharset'] :$_GET['sqlcharset'];
		$sqlcompat = $_POST['sqlcompat'] ? $_POST['sqlcompat'] : $_GET['sqlcompat'];
		$sizelimit = $_POST['sizelimit'] ? $_POST['sizelimit'] : $_GET['sizelimit'];
		$action = $_POST['action'] ? $_POST['action'] : $_GET['action'];
		$fileid = $_POST['fileid'] ? $_POST['fileid'] : $_GET['fileid'];
		$random = $_POST['random'] ? $_POST['random'] : $_GET['random'];
		$tableid = $_POST['tableid'] ? $_POST['tableid'] : $_GET['tableid'];
		$startfrom = $_POST['startfrom'] ? $_POST['startfrom'] : $_GET['startfrom'];
		//获取数据表
		$_pre = str_replace('_', '\_', $pre);
		$tables = array();
		$_timearr = $db->getall("SHOW TABLES LIKE '$_pre%'", MYSQL_NUM);
		foreach ($_timearr as $_val){
			$tables[] = $_val[0];
		}
		admin_log("备份数据库！", $admin_val['name']);
		//备份数据库
		export_database($tables,$sqlcompat,$sqlcharset,$sizelimit,$action,$fileid,$random,$tableid,$startfrom,'kuaifancms');
	}
}

?>