<?php
/*
 * 邮箱认证
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
//定义链接
$url = get_link("vs|sid","&amp;","1");
//删除过期邮箱认证
$db->query("Delete from ".table('huiyuan_shenhe')." WHERE email_encrypt!='' AND email_encrypt_time < ".SYS_TIME."");
//获取邮箱认证
$usersh = $db->getone("select * from ".table('huiyuan_shenhe')." where email_encrypt='{$_REQUEST['enc']}' AND email_encrypt_time > ".SYS_TIME." LIMIT 1");
$links[0]['title'] = '返回网站首页';
$links[0]['href'] = kf_url('index');
if (empty($usersh)){
	showmsg("系统提醒", "此链接已经过期！", $links);
}
if ($usersh['username']){
	if ($db -> getone("select * from ".table('huiyuan')." WHERE username = '{$usersh['username']}'")){
		showmsg("系统提醒", "会员用户名已被注册，无法验证，请<a href=\"{$url}&amp;m=huiyuan&amp;c=zhuce\">重新注册</a>！",$links);}
}
if ($usersh['email']){
	if ($db -> getone("select * from ".table('huiyuan')." WHERE email = '{$usersh['email']}'")){
		showmsg("系统提醒", "会员邮箱已被注册，无法审核，请<a href=\"{$url}&amp;m=huiyuan&amp;c=zhuce\">重新注册</a>！",$links);}
}
if ($usersh['mobile']){
	if ($db -> getone("select * from ".table('huiyuan')." WHERE mobile = '{$usersh['mobile']}'")){
		showmsg("系统提醒", "会员手机号码已被注册，无法审核，请<a href=\"{$url}&amp;m=huiyuan&amp;c=zhuce\">重新注册</a>！",$links);}
}
//

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
		$modellistarr = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_moxing.php');
		$modellist = $modellistarr[$usersh['modelid']];
		$modelinfo['userid'] = $info_id;
		inserttable(table('huiyuan_diy_'.$modellist['tablename']),$modelinfo,true);
	}
	$db->query("Delete from ".table('huiyuan_shenhe')." WHERE userid=".intval($usersh['userid']));
	//发送邮件提示
	if (get_mail_yes('mail_set_reg')){
		$_mail = get_mail('reg',$usersh,$_CFG);
		smtp_mail($usersh['email'],$_mail['smtp_title'],$_mail['smtp_body']);
	}
	$links[0]['title'] = '马上登录';
	$links[0]['href'] = $url."&amp;m=huiyuan&amp;c=denglu";
	$links[1]['title'] = '返回网站首页';
	$links[1]['href'] = kf_url('index');
	showmsg("系统提醒", "恭喜您，注册成功！", $links);
}else{
	showmsg("系统提醒", "系统繁忙，请稍后再试！",$links);
}

?>