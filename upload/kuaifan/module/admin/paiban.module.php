<?php
/*
 * 排版中心
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');
kf_class::run_sys_func('paiban');

if ($_GET['a'] == 'del'){

	$wheresql = " WHERE del = 0 and parentid = ".intval($_GET['id']);
	$total_count = $db -> get_total("SELECT COUNT(*) AS num FROM ".table('paiban').$wheresql);

	$wheresql = " WHERE id = ".intval($_GET['id']);
	$val = $db -> getone("select * from ".table('paiban').$wheresql);

	if ($total_count > 0){
		$links[0]['title'] = '返回项目页面';
		$links[0]['href'] = get_link("a")."&amp;a=info";
		showmsg("系统提醒", "删除失败！<br/>原因:名为【".htmlspecialchars($val['title'])."】下有子项目所以无法删除！", $links);
	}
	if ($_GET['dosubmit'] == '1'){
		if (!$db->query("update ".table('paiban')."  SET del='1'{$wheresql}")){
			showmsg("系统提醒", "删除失败,网络繁忙请稍后再试！");
		}
		admin_log("成功删除名为【".$val['title']."】的项目！", $admin_val['name']);
		$links[1]['title'] = '返回项目页面';
		$links[1]['href'] = get_link("a|id|dosubmit")."&amp;id=".$val['parentid'];
		$links[2]['title'] = '排版设计中心';
		$links[2]['href'] = $_admin_indexurl."&amp;c=paiban";
		refresh_cache_paiban();
		showmsg("系统提醒", "成功删除名为【".htmlspecialchars($val['title'])."】的项目！", $links);
	}
	$links[0]['title'] = '确定删除';
	$links[0]['href'] = get_link()."&amp;dosubmit=1";
	$links[1]['title'] = '返回项目页面';
	$links[1]['href'] = get_link("a")."&amp;a=info";
    $links[2]['title'] = '排版设计中心';
    $links[2]['href'] = $_admin_indexurl."&amp;c=paiban";
	showmsg("系统提醒", "确定删除名为【".htmlspecialchars($val['title'])."】的项目吗？", $links);
}elseif (($_GET['a'] == 'addl' || $_GET['a'] == 'edit')){
	if (!empty($_POST['dosubmit'])){
		if (!$_POST['title']) showmsg("添加失败", "添加失败，原因:“项目名称”不能为空！");
		//
		foreach ($_POST as $_k => $_v){
			if (substr($_k, 0, 5) == 'body_'){
				if (!empty($_v)) $_POST['body'][substr($_k, 5)] = $_v;
			}
		}
		//
		if (!empty($_POST['body_picurlarr'])){
			$_POST['body']['link'] = mokuai_url($_POST['body_picurlarr'],'','&');
		}
		//
		$paisqled['title'] = 	$paisql['title'] = $_POST['title'];
		$paisqled['body'] = 	$paisql['body'] = array2string($_POST['body']);
		$paisqled['qianmian'] = $paisql['qianmian'] = $_POST['qianmian'];
		$paisqled['houmian'] = 	$paisql['houmian'] = $_POST['houmian'];
		$paisqled['order'] = 	$paisql['order'] = $_POST['order'];
		$paisqled['hide'] = 	$paisql['hide'] = $_POST['hide'];
		$paisqled['wap'] = 		$paisql['wap'] = str_replace('，',',',$_POST['wap']);
		$paisqled['nocache'] = 	$paisql['nocache'] = $_POST['nocache'];
		$paisqled['islogin'] = 	$paisql['islogin'] = $_POST['islogin'];
		$paisqled['lasttime'] = $paisql['addtime'] = $timestamp;
		$paisqled['lastadmin'] =$paisql['adminuser'] = $admin_val['name'];
		$paisql['addip'] = $online_ip;
		if (intval($_GET['id'])>0) $paisql['parentid'] = intval($_GET['id']);
		$paisql['type'] = ltype($_GET['l']);
		$paisql['type_en'] = $_GET['l'];

		if ($_GET['a'] == 'edit'){
			$wheresql=" id=".intval($_GET['id'])."";
			if (!updatetable(table('paiban'), $paisqled, $wheresql)){
				showmsg("修改失败", "修改失败，网络繁忙请稍后再试！");
			}else{
				admin_log("成功修改名为“{$paisqled['title']}”的项目。", $admin_val['name']);
				$links[0]['title'] = '继续修改';
				$links[0]['href'] = get_link("dosubmit");
				$links[1]['title'] = '返回项目页面';
				$links[1]['href'] = get_link("a")."&amp;a=info";
                $links[2]['title'] = '排版设计中心';
                $links[2]['href'] = $_admin_indexurl."&amp;c=paiban";
				refresh_cache_paiban();
				showmsg("修改成功", "成功修改名为“".htmlspecialchars($paisql['title'])."”的项目。", $links);
			}
		}else{
			if (!inserttable(table('paiban'),$paisql)){
				showmsg("添加失败", "添加失败，网络繁忙请稍后再试！");
			}else{
				admin_log("成功添加名为“{$paisql['title']}”的项目。", $admin_val['name']);
				$links[0]['title'] = '继续添加';
				$links[0]['href'] = get_link("dosubmit");
				$links[1]['title'] = '选择项目添加';
				$links[1]['href'] = get_link("a|l")."&amp;a=add";
                $links[2]['title'] = '排版设计中心';
                $links[2]['href'] = $_admin_indexurl."&amp;c=paiban";
				refresh_cache_paiban();
				showmsg("添加成功", "成功添加名为“".htmlspecialchars($paisql['title'])."”的项目。", $links);
			}
		}
	}

}


if ($_GET['a'] == 'addl'){
	//友链调用
	if ($_GET['l'] == 'lianjie'){
		$lianjiearr = array('不限'=>"0",);
		$arr = $db -> getall("select * from ".table('lianjie_fenlei'));
		foreach ($arr as $val) {
			$lianjiearr[$val['title']] = $val['catid'];
		}
		$smarty->assign('lianjiearr', $lianjiearr);
	}
	//广告位
	if ($_GET['l'] == 'guanggao'){
		$guanggaoarr = array('不限'=>"0",);
		$arr = $db -> getall("select * from ".table('guanggao')." WHERE disabled=0");
		foreach ($arr as $val) {
			$guanggaoarr[$val['title']] = $val['id'];
		}
		$smarty->assign('guanggaoarr', $guanggaoarr);
	}
	//链接快选
	if ($_GET['l'] == 'chaolian' || $_GET['l'] == 'tulian'){
		$mokuaiurl = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_mokuai_url.php');
		$mokuaiarr = array('=快速选择地址='=>"",);
		foreach ($mokuaiurl as $_k => $_v) {
			$mokuaiarr[$_v['urltitle']] = $_k;
		}
		$smarty->assign('linkarr', $mokuaiarr);
	}
	//内容列表、内容栏目
	if ($_GET['l'] == 'nrliebiao' || $_GET['l'] == 'nrlanmu'){
		kf_class::run_sys_class('form','',0);
		$_name = ($_GET['vs'] == '1')?'body_title'.fenmiao():'body[title]';
		$__lanmu = form::select_category('column/cache.'.$_CFG['site'], '', 'name=\''.$_name.'\'', '' , 0, -1);
		$smarty->assign('lanmusel', $__lanmu);
	}
	//最大排序
	$paibanorder = $db->getone("SELECT `order` FROM ".table('paiban')." ORDER BY `order` DESC");
	$smarty->assign('paibanorder', intval($paibanorder['order'] + 1));
}
if ($_GET['a'] == 'edit' && $_GET['id']){
	$wheresql = " WHERE id = ".intval($_GET['id']);
	$val = $db -> getone("select * from ".table('paiban').$wheresql);
	$valstring = string2array($val['body']);
	//友链调用
	if ($val['type_en'] == 'lianjie'){
		$lianjiearr = array('不限'=>"0",);
		$arr = $db -> getall("select * from ".table('lianjie_fenlei'));
		foreach ($arr as $val) {
			$lianjiearr[$val['title']] = $val['catid'];
		}
		$smarty->assign('lianjiearr', $lianjiearr);
	}
	//广告位
	if ($val['type_en'] == 'guanggao'){
		$guanggaoarr = array('不限'=>"0",);
		$arr = $db -> getall("select * from ".table('guanggao')." WHERE disabled=0");
		foreach ($arr as $val) {
			$guanggaoarr[$val['title']] = $val['id'];
		}
		$smarty->assign('guanggaoarr', $guanggaoarr);
	}	
	//内容列表、内容栏目
	if ($val['type_en'] == 'nrliebiao' || $val['type_en'] == 'nrlanmu'){
		kf_class::run_sys_class('form','',0);
		$_name = ($_GET['vs'] == '1')?'body_title'.fenmiao():'body[title]';
		$__lanmu = form::select_category('column/cache.'.$_CFG['site'], $valstring['title'], 'name=\''.$_name.'\'', '' , 0, -1);
		$smarty->assign('lanmusel', $__lanmu);
	}
	//链接快选
	if ($val['type_en'] == 'chaolian' || $val['type_en'] == 'tulian'){
		$mokuaiurl = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_mokuai_url.php');
		$mokuaiarr = array('=快速选择地址='=>"",);
		foreach ($mokuaiurl as $_k => $_v) {
			$mokuaiarr[$_v['urltitle']] = $_k;
		}
		$smarty->assign('linkarr', $mokuaiarr);
	}
}


//高级排序
if ($_GET['a'] == 'info' && $_GET['paixu']){
	$templatefile.= ".paixu";
	$smarty->assign('id', $_GET['id']?$_GET['id']:0);
	
	if (!empty($_POST['dosubmit'])){
		foreach ($_POST as $_k => $_v){
			if (substr($_k, 0, 4) == 'pai_'){
				$db -> query("update ".table('paiban')." set `order`=".intval($_v)." WHERE id='".substr($_k, 4)."'");
			}
		}
		refresh_cache_paiban();
		$links[0]['title'] = '继续修改';
		$links[0]['href'] = get_link("dosubmit");
		$links[1]['title'] = '返回列表';
		$links[1]['href'] = get_link("paixu|dosubmit");
		admin_log("修改排版排序。", $admin_val['name']);
		showmsg("系统提醒", "修改成功！", $links);
	}
}
?>