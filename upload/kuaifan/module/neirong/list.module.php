<?php
/*
 * 列表页
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

//栏目
$columnarr = getcache('caches/column/cache.'.$_CFG['site'].'.php');
$lanmudata = $columnarr[$_GET['catid']];
if (empty($lanmudata)) {
  $links[0]['title'] = '返回网站首页';
  $links[0]['href'] = kf_url('index');
  showmsg("提示信息", "栏目不存在！", $links);
}
$lanmudata['setting'] = string2array($lanmudata['setting']);
if (empty($lanmudata['arrchildid'])) $lanmudata['arrchildid'] = $lanmudata['id'];

//栏目查看权限
switch (category_priv()) {
	case '-1':
		require(KF_INC_PATH.'denglu.php');
		break;
	case '-2':
		showmsg("提示信息", "您没有访问该信息的权限！");
		break;
	case '0':
		showmsg("提示信息", "权限错误！");
		break;
}

//注册多久进入权限
$xianshi_zcsj = intval($lanmudata['setting']['xianshi_zcsj']);
if ($xianshi_zcsj > 0){
	if ($huiyuan_val['regdate'] > SYS_TIME - intval($xianshi_zcsj * 60)){
		showmsg("提示信息", "限制注册时间未达到{$xianshi_zcsj}会员进入！");
	}
}

//模型
$moxingdata = getcache(KF_ROOT_PATH.'caches/cache_neirong_moxing.php');
$moxingdata = $moxingdata[$lanmudata['modelid']];

//模板风格
$_CFG['template_dir'] = rtrim($lanmudata['setting']['default_style']?$lanmudata['setting']['default_style']:$moxingdata['default_style'],'/').'/';
if ($lanmudata['arrchildid'] == $lanmudata['id']){
	//栏目列表页模板
	$templatefile = rtrim($lanmudata['setting']['list_template']?$lanmudata['setting']['list_template']:$moxingdata['list_template'],'/');
}else{
	//栏目首页模板
	$templatefile = rtrim($lanmudata['setting']['category_template']?$lanmudata['setting']['category_template']:$moxingdata['category_template'],'/');
	$lanmulist = array();
	foreach ($columnarr as $_val) {
		if ($_val['parentid'] == $_GET['catid']){
			$_val['setting'] = string2array($_val['setting']);
			if (empty($_val['arrchildid'])) $_val['arrchildid'] = $_val['id'];
			$_urlarr = array(
				'm'=>'neirong',
				'c'=>'list',
				'catid'=>$_val['id'],
				'sid'=>$_GET['sid'],
				'vs'=>$_GET['vs'],
			);
			$_val['url'] = url_rewrite('KF_neironglist', $_urlarr);
			$lanmulist[] = $_val;
		}
	}
	$smarty->assign('ML', $lanmulist);
}

//栏目GET组
$smarty->assign('getarr', array('m'=>'neirong','c'=>'list','catid'=>$lanmudata['id']));

//bbs模型 start
if ($moxingdata['type'] == 'bbs'){
	$_urlarr = array(
		'm'=>'neirong',
		'c'=>'list',
		'catid'=>$lanmudata['id'],
		'sid'=>$_GET['sid'],
		'vs'=>$_GET['vs'],
	);
	$_valurl = url_rewrite('KF_neironglist', $_urlarr, false);
	if ($_GET['type'] == 're'){
		$lanmudata['type_head']='<a href="'.$_valurl.'">所有</a>|热帖|<a href="'.$_valurl.'&amp;type=jing">精华</a>|<a href="'.$_valurl.'&amp;type=xin">最新</a>|<a href="'.$_valurl.'&amp;type=tu">图贴</a>';
	}elseif ($_GET['type'] == 'jing'){
		$lanmudata['type_head']='<a href="'.$_valurl.'">所有</a>|<a href="'.$_valurl.'&amp;type=re">热帖</a>|精华|<a href="'.$_valurl.'&amp;type=xin">最新</a>|<a href="'.$_valurl.'&amp;type=tu">图贴</a>';
	}elseif ($_GET['type'] == 'xin'){
		$lanmudata['type_head']='<a href="'.$_valurl.'">所有</a>|<a href="'.$_valurl.'&amp;type=re">热帖</a>|<a href="'.$_valurl.'&amp;type=jing">精华</a>|最新|<a href="'.$_valurl.'&amp;type=tu">图贴</a>';
	}elseif ($_GET['type'] == 'tu'){
		$lanmudata['type_head']='<a href="'.$_valurl.'">所有</a>|<a href="'.$_valurl.'&amp;type=re">热帖</a>|<a href="'.$_valurl.'&amp;type=jing">精华</a>|<a href="'.$_valurl.'&amp;type=xin">最新</a>|图贴';
	}else{
		$lanmudata['type_head']='所有|<a href="'.$_valurl.'&amp;type=re">热帖</a>|<a href="'.$_valurl.'&amp;type=jing">精华</a>|<a href="'.$_valurl.'&amp;type=xin">最新</a>|<a href="'.$_valurl.'&amp;type=tu">图贴</a>';
	}
}
//bbs模型 end


//栏目SEO
$_SEO['title'] = $lanmudata['setting']['meta_title']?$lanmudata['setting']['meta_title']:$lanmudata['title'];
$_SEO['keywords'] = $lanmudata['setting']['meta_keywords']?$lanmudata['setting']['meta_keywords']:'';
$_SEO['description'] = $lanmudata['setting']['meta_description']?$lanmudata['setting']['meta_description']:'';

$smarty->assign('M', $lanmudata);
?>