<?php
/*
 * 搜索
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

//加载搜索记录
$row = $db -> getone("select * from ".table('sousuo')." WHERE `id` = '{$_GET['id']}'");
if (empty($row)){
	showmsg("系统提醒", "参数错误,内容不存在！");
}

$cna = $_CFG['cookie_pre'].substr(md5('key'.$_GET['key']),8,16);
$cke = $_COOKIE[$cna];
if (empty($cke)){
	$db -> query("update ".table('sousuo')." set `searchnums`=searchnums+1 WHERE `id`={$row['id']}");
	//记录关键词
	if (check_str($_GET['key'])==2){
		$rowkey = $db -> getone("select * from ".table('sousuo_key')." WHERE `key` = '{$_GET['key']}'");
		if (!$rowkey){
			inserttable(table('sousuo_key'), array('key'=>$_GET['key'],'searchnums'=>1,'uptime'=>SYS_TIME));
		}else{
			$db -> query("update ".table('sousuo_key')." set `uptime`='".SYS_TIME."',`searchnums`=searchnums+1 WHERE `id`={$rowkey['id']}");
		}
	}
	setcookie($cna, $row['id'], SYS_TIME+3600*24);
}

//跳转至相关页面
switch ($row['typeid']) {
	case 'neirong':
		$_urlarr = array(
			'm'=>'neirong',
			'c'=>'show',
			'catid'=>$row['catid'],
			'id'=>$row['contentid'],
			'sid'=>$_GET['sid'],
			'vs'=>$_GET['vs'],
		);
		$url = url_rewrite('KF_neirongshow', $_urlarr);
		tiaozhuan($url);
		break;
	default:
		showmsg("系统提醒", "参数错误！");
}


?>