<?php
/**
 * 后台必须登录判断文件
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');


$admin_wheresql = " WHERE allow ='{$_REQUEST['allow']}'";
$admin_val = $db -> getone("select * from ".table('guanliyuan').$admin_wheresql." LIMIT 1");
if (empty($admin_val)){
  $_SESSION['guanliname'] = '';
  $url = get_link("vs","",1);
  $links[0]['title'] = '登录后台';
  $links[0]['href'] = $url."&amp;m=admin&amp;c=login";
  $links[1]['title'] = '返回网站首页';
  $links[1]['href'] = $url."&amp;m=index&amp;sid={$_GET['sid']}";
  showmsg("系统提醒", "请先登录后台！", $links, $links[0]['href']);
  //header("Location:".get_link("c,allow","&")."&c=login"); exit;
}
$_SESSION['guanliname'] = $admin_val['name'];
$smarty->assign('admin_val', $admin_val);
?>