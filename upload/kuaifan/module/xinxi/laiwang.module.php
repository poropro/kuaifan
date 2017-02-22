<?php
/*
 * 来往信息
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

$huiyuan = $db->getone("select * from ".table('huiyuan')." WHERE username='{$_GET['username']}' LIMIT 1");
if (empty($huiyuan)) showmsg("系统提醒", "此会员不存在！", $links);
$_SEO['title'] = '与“'.$huiyuan['username'].'”来往信息';
$smarty->assign('huiyuan', $huiyuan);
$smarty->assign('username', $huiyuan_val['username']);

?>