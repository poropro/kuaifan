<?php
 /*
 * 神州行充值系统
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
if(!defined('IN_KUAIFAN')) exit('Access Denied!');
require(KF_INC_PATH.'denglu_admin.php');
require "function.php";


$htmlarr = array();
$meiyeshu = 10; //每页显示
$_GET['pp'] = $_POST['pp']?$_GET['pp']:$_GET['pp']; //当前页
if (empty($_GET['pp'])) $_GET['pp'] = 1;
$html = dfopen(SYS_URL."?m=jilu&keyval={$row['key']}&ll={$meiyeshu}&pp=".$_GET['pp']);
//总数量
$htmlarr['zongshu'] = chongzhi_cut($html, 'totalstart', 'totalstart');
//返回记录
$htmlval = chongzhi_cut($html, 'liststart', 'listend');
if ($htmlval == '-1') showmsg("系统提醒", "数据获取失败请<a href='".get_link()."'>刷新</a>！");

$cache_file_path = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
if(!file_exists($dirto)) mkdir($cache_file_path); //如果目录不存在，则建立之
$cache_file_path.= $_GET['pp'].'.php';
write_static_jilu($cache_file_path, $htmlval);

$htmlarr['jilu'] = getcache($cache_file_path);
kf_class::run_sys_func('huiyuan');
foreach ($htmlarr['jilu'] as $_k=>$_v) {
	$_v['userdb'] = get_user($_v['userid']);
	$_v['dingdan'] = $db->getone("select * from ".table('dingdan')." WHERE setting='".md5($_v['md5'])."' AND userid={$_v['userid']} LIMIT 1");
	$htmlarr['jilu'][$_k] = $_v;
}
//生成分页
kf_class::run_sys_class('page','',0);
$pagelist = new page(array('total'=>$htmlarr['zongshu'],'perpage'=>$meiyeshu,'getarray'=>$_GET,'page_name'=>'pp'));
$htmlarr['pagelist'] = $pagelist->show('wap');
//传递列表
$smarty->assign('htmlarr', $htmlarr);
?>