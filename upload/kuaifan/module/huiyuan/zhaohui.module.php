<?php
/*
 * 找回密码
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

$yzmpeizhi = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_peizhi_mokuai'.DIRECTORY_SEPARATOR.'cache.yanzhengma.php');
//
if ($_POST['dosubmit']){
	$links[0]['title'] = '返回继续';
	$links[0]['href'] = get_link();
	$links[1]['title'] = '返回网站首页';
	$links[1]['href'] = kf_url('index');

	if (!$_POST['username']) showmsg("系统提醒", "请输入“用户名或手机号码”！", $links);
	if (!$_POST['email']) showmsg("系统提醒", "请输入“注册时设置的邮箱”！", $links);
	//验证码
	if (!$_POST['yanzhengma'] && $yzmpeizhi['zhaohui']) showmsg("系统提醒", "请输入“验证码”！", $links);
	if ($yzmpeizhi['zhaohui']) {
		$_POST['ip'] = $_POST['ip']?$_POST['ip']:yanzhengmaip();
		$row = $db->getone("select * from ".table('yanzhengma')." where captcha = '{$_POST['ip']}' AND code = '{$_POST['yanzhengma']}' LIMIT 1");
		if (empty($row)){
			showmsg("系统提醒", "请输入正确的“验证码”！", $links);
		}
		if (intval($timestamp - $row['time']) > 3*60){ //验证码3分钟过期
			showmsg("系统提醒", "“验证码”已过期，请返回换一张刷新！", $links);
		}
		unset($_POST['yanzhengma']);
	}
	//
	$row = $db->getone("select * from ".table('huiyuan')." WHERE (username='{$_POST['username']}' OR mobile='{$_POST['username']}') AND email='{$_POST['email']}' LIMIT 1");
	if (empty($row)){
		showmsg("系统提醒", "你输入的“注册时设置的邮箱”与“用户名或手机号码”不匹配！", $links);
	}else{
		//找回发送邮件
		if (!get_mail_yes('mail_set_zhaohui')) showmsg("系统提醒", "系统未开通邮件找回密码，请联系管理员！", $links);
		$row['password'] = generate_password(intval(rand(5,8)),1);
		$_mail = get_mail('zhaohui',$row,$_CFG,array('userpass'=>$row['password']));
		if (!smtp_mail($row['email'],$_mail['smtp_title'],$_mail['smtp_body'])){
			showmsg("系统提醒", "找回密码失败。<br/>错误原因: 发送找回邮件失败，请联系管理员！", $links);
		}else{
			if (!$db->query("UPDATE ".table('huiyuan')." SET password='".md5s($row['password'])."' WHERE userid=".$row['userid'])){
				showmsg("系统提醒", "找回密码失败。<br/>错误原因: 重置密码失败，请稍后再试！", $links);
			}else{
				$db->query("Delete from ".table('yanzhengma')." WHERE captcha = '{$_POST['ip']}'");
				$links[0]['title'] = '返回登录';
				$links[0]['href'] = get_link('c').'&amp;c=denglu';
				showmsg("系统提醒", "恭喜您，密码重置成功<br/>系统已为您重置一个新密码并发送到你的邮箱:{$_POST['email']}，请登录邮箱查看！", $links);
			}
		}
	}
		
}
$smarty->assign('yzmpeizhi', $yzmpeizhi);
kf_class::run_sys_func('ubb');
$row = $db->getone("select * from ".table('peizhi_mokuai')." WHERE module='duanxin' LIMIT 1");
$smarty->assign('sms', string2array($row['setting']));
$row = $db->getone("select * from ".table('youxiang')." WHERE type='rule' LIMIT 1");
$smarty->assign('mail', string2array($row['body']));

?>