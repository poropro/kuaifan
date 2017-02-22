<?php
/*
 * 查找会员
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

$_nickname = $_GET['key'];
if (empty($_nickname)){
	showmsg("系统提示", "参数错误!");
}
$row = $db -> getall("select * from ".table("huiyuan")." WHERE `nickname`='{$_nickname}' ORDER BY rand()");
if (empty($row)){
	showmsg("系统提示", "该用户不存在!");
}
$i = 0;
foreach ($row as $_k=>$val) {
	$links[$i]['title'] = $val['nickname'].'(ID'.$val['userid'].')';
	$links[$i]['href'] = get_link('vs|sid','',1).'&amp;m=huiyuan&amp;c=ziliao&amp;userid='.$val['userid'];
	$i++;
}
if ($i == 1){
	tiaozhuan($links[0]['href']);
}
showmsg("系统提示", "请选择要查看的用户", $links);
?>