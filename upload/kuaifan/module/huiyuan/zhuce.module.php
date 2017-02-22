<?php
/*
 * 会员注册
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

//判断关闭注册
if ($_CFG['closereg'] == '1'){
	showmsg("系统提醒", "网站暂停注册会员。");
}
$peizhi = $db -> getone("select * from ".table('peizhi_mokuai')." WHERE module='huiyuan'");
$peizhiarr = string2array($peizhi['setting']);
$smarty->assign('peizhiarr', $peizhiarr);
//
$yzmpeizhi = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_peizhi_mokuai'.DIRECTORY_SEPARATOR.'cache.yanzhengma.php');
$smarty->assign('yzmpeizhi', $yzmpeizhi);
//  
$modellistarr = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_moxing.php');
if (count($modellistarr) > 1 && $peizhiarr['choosemodel']){
	$_modelarr = array();
	foreach ($modellistarr as $_value) {
		$_modelarr[$_value['title']] = $_value['id'];
	}
	$smarty->assign('modelarr', $_modelarr);
}

if ($_POST['dosubmit']){
	$links[0]['title'] = '重新注册';
	$links[0]['href'] = get_link();
	$links[1]['title'] = '返回网站首页';
	$links[1]['href'] = kf_url('index');
		
	if (count($modellistarr) > 1 && $peizhiarr['choosemodel']){
		if ($modellistarr[$_POST['modelid']]){
			$_POST['modelid'] = intval($_POST['modelid']);
		}else{
			showmsg("系统提示", "请选择正确的“会员模型”！", $links);
		}
	}else{
		reset($modellistarr);
		$_POST['modelid'] = current($modellistarr);
		$_POST['modelid'] = $_POST['modelid']['id'];
	}
		
	if (!$_POST['mobile']) showmsg("系统提示", "请输入“手机号码”！", $links);
	if (!$_POST['email']) showmsg("系统提示", "请输入“邮箱地址”！", $links);
	if (!$_POST['username']) showmsg("系统提示", "请输入“用户名”！", $links);
	if (!$_POST['nickname']) showmsg("系统提示", "请输入“昵称”！", $links);
	if (!$_POST['userpass']) showmsg("系统提示", "请输入“密码”！", $links);
	if (!$_POST['yanzhengma'] && $yzmpeizhi['zhuce']) showmsg("系统提示", "请输入“验证码”！", $links);
		
	if (strlen($_POST['mobile']) != 11) showmsg("系统提示", "请输入正确的“手机号码”！", $links);
	if (strlen($_POST['email']) < 3 || strlen($_POST['email']) > 32) showmsg("系统提示", "“邮箱地址”不得小于3位或者大于32位！", $links);
	if (strlen($_POST['username']) < 3 || strlen($_POST['username']) > 20) showmsg("系统提示", "“用户名”不得小于3位或者大于20位！", $links);
	if (strlen($_POST['nickname']) < 2 || strlen($_POST['nickname']) > 20) showmsg("系统提示", "“昵称”不得小于2位或者大于20位！", $links);
	if (strlen($_POST['userpass']) < 6 || strlen($_POST['userpass']) > 20) showmsg("系统提示", "“密码”不得小于6位或者大于20位！", $links);
		
	if (!is_username($_POST['username'])) showmsg("系统提示", "“用户名”格式输入错误，可能还有非法字符！", $links);
	if (!is_username($_POST['nickname'])) showmsg("系统提示", "“昵称”格式输入错误，可能还有非法字符！", $links);
	if (!is_email($_POST['email'])) showmsg("系统提示", "“邮箱地址”格式输入错误！", $links);
	if(!preg_match('/^1([0-9]{10})/',$_POST['mobile']) || !is_num($_POST['mobile'])) showmsg("系统提示", "“手机号码”格式输入错误！", $links);

	if ($yzmpeizhi['zhuce']) {
		$_POST['ip'] = $_POST['ip']?$_POST['ip']:yanzhengmaip();
		$row = $db->getone("select * from ".table('yanzhengma')." where captcha = '{$_POST['ip']}' AND code = '{$_POST['yanzhengma']}' LIMIT 1");
		if (empty($row)){
			showmsg("系统提示", "请输入正确的“验证码”！", $links);
		}
		if (intval($timestamp - $row['time']) > 3*60){ //验证码3分钟过期
			showmsg("系统提示", "“验证码”已过期，请返回换一张刷新！", $links);
		}
		unset($_POST['yanzhengma']);
	}
		
	$row = $db->getone("select * from ".table('huiyuan')." where mobile = '{$_POST['mobile']}' LIMIT 1");
	if (!empty($row)) showmsg("系统提示", "“手机号码”已被注册！", $links);
	$row = $db->getone("select * from ".table('huiyuan')." where email = '{$_POST['email']}' LIMIT 1");
	if (!empty($row)) showmsg("系统提示", "“邮箱地址”已被注册！", $links);
	$row = $db->getone("select * from ".table('huiyuan')." where username = '{$_POST['username']}' LIMIT 1");
	if (!empty($row)) showmsg("系统提示", "“用户名称”已被注册！", $links);

	$row = $db->getone("select * from ".table('huiyuan_shenhe')." where mobile = '{$_POST['mobile']}' LIMIT 1");
	if (!empty($row)) showmsg("系统提示", "“手机号码”已被注册！", $links);
	$row = $db->getone("select * from ".table('huiyuan_shenhe')." where email = '{$_POST['email']}' LIMIT 1");
	if (!empty($row)) showmsg("系统提示", "“邮箱地址”已被注册！", $links);
	$row = $db->getone("select * from ".table('huiyuan_shenhe')." where username = '{$_POST['username']}' LIMIT 1");
	if (!empty($row)) showmsg("系统提示", "“用户名称”已被注册！", $links);

	//UCenter会员注册
	$ucuserid = 0;
	kf_class::ucenter();
	if (UC_USE == '1'){
		$ucuid= uc_user_register($_POST['username'], $_POST['userpass'], $_POST['email'], '', '', $online_ip);
		if(is_numeric($ucuid)) {
			switch ($ucuid) {
				case '-1':
					showmsg("系统提示:UCenter", "用户名不合法！", $links);
					break;
				case '-2':
					showmsg("系统提示:UCenter", "包含要允许注册的词语！", $links);
					break;
				case '-3':
					showmsg("系统提示:UCenter", "用户名已经存在！", $links);
					break;
				case '-4':
					showmsg("系统提示:UCenter", "Email 格式有误！", $links);
					break;
				case '-5':
					showmsg("系统提示:UCenter", "Email 不允许注册！", $links);
					break;
				case '-6':
					showmsg("系统提示:UCenter", "该 Email 已经被注册！", $links);
					break;
				default :
					$ucuserid = $ucuid;
					break;
			}
		}else{
			showmsg("系统提示:UCenter", "网络繁忙，请稍后再试。", $links);
		}
	}
	//
	
	$row = $db->getone("select * from ".table('peizhi_mokuai')." where module='huiyuan' LIMIT 1");
	$member_setting = string2array($row['setting']);
	$userinfo = array();
	$userinfo['ucuserid'] = $ucuserid;
	$userinfo['encrypt'] = generate_password(6);
	$userinfo['username'] = $_POST['username'];
	$userinfo['nickname'] = $_POST['nickname'];
	$userinfo['email'] = $_POST['email'];
	$userinfo['password'] = md5s($_POST['userpass']);
	$userinfo['modelid'] = $_POST['modelid'];
	$userinfo['regip'] = $online_ip;
	$userinfo['point'] = $member_setting['defualtpoint'] ? $member_setting['defualtpoint'] : 0;
	$userinfo['amount'] = $member_setting['defualtamount'] ? $member_setting['defualtamount'] : 0;
	$userinfo['regdate'] = $userinfo['lastdate'] = $timestamp;
	$userinfo['site'] = $_CFG['site'];
	$userinfo['mobile'] = $_POST['mobile'];
	$userinfo['groupid'] = _get_usergroup_bypoint(0);
		
	//邮箱认证
	if ($peizhiarr['enablemailcheck'] == '1'){
		if (!get_mail_yes('mail_set_renzheng')) showmsg("系统提示", "注册需要邮箱认证但系统未开通，请联系管理员！", $links);
		$userinfo['email_encrypt'] = generate_password(24);
		$userinfo['email_encrypt_time'] = SYS_TIME + 86400;
		$userinfo['password'] = $_POST['userpass'];
		$arr = array();
		$arr['url'] = get_link('index', "&amp", 0, array('index'=>generate_password(6)))."&amp;m=huiyuan&amp;c=renzheng&amp;enc={$userinfo['email_encrypt']}";
		$arr['click'] = "<a href=\"{$arr['url']}\">{$arr['url']}</a>";
		$_mail = get_mail('renzheng',$userinfo,$_CFG,$arr);
		if (!smtp_mail($userinfo['email'],$_mail['smtp_title'],$_mail['smtp_body'])){
			showmsg("系统提示", "注册邮件发送失败，请联系管理员！", $links);
		}
		$_userid = inserttable(table('huiyuan_shenhe'), $userinfo, true);
		if ($_userid > 0){
			$db->query("Delete from ".table('yanzhengma')." WHERE captcha = '{$_POST['ip']}'");
			$links[0]['title'] = '已认证';
			$links[0]['href'] = get_link('c|a').'&amp;c=denglu';
			$links[1]['title'] = '返回网站首页';
			$links[1]['href'] = kf_url('index');
			showmsg("系统提醒", "注册需要邮箱认证，认证链接已经发送到您的邮箱，请登录邮箱查看！", $links);
		}else{
			showmsg("系统提示", "注册失败，请稍后再试！", $links);
		}
	}
	//注册审核
	if ($peizhiarr['registerverify'] == '1'){
		$userinfo['password'] = $_POST['userpass'];
		$_userid = inserttable(table('huiyuan_shenhe'), $userinfo, true);
		if ($_userid > 0){
			$db->query("Delete from ".table('yanzhengma')." WHERE captcha = '{$_POST['ip']}'");
			$links[0]['title'] = '马上登录';
			$links[0]['href'] = get_link('c|a').'&amp;c=denglu';
			$links[1]['title'] = '返回网站首页';
			$links[1]['href'] = kf_url('index');
			showmsg("系统提醒", "注册成功，请耐心等待管理员审核！", $links);
		}else{
			showmsg("系统提示", "注册失败，请稍后再试！", $links);
		}
	}
	//正常注册
	$_userid = inserttable(table('huiyuan'), $userinfo, true);
	if ($_userid > 0){
		$db->query("Delete from ".table('yanzhengma')." WHERE captcha = '{$_POST['ip']}'");
		//发送邮件提示
		if (get_mail_yes('mail_set_reg')){
			$userinfo['password'] = $_POST['userpass'];
			$_mail = get_mail('reg',$userinfo,$_CFG);
			smtp_mail($userinfo['email'],$_mail['smtp_title'],$_mail['smtp_body']);
		}
		$links[0]['title'] = '马上登录';
		$links[0]['href'] = get_link('c|a').'&amp;c=denglu';
		$links[1]['title'] = '返回网站首页';
		$links[1]['href'] = kf_url('index');
		showmsg("系统提示", "恭喜您，注册成功！", $links);
	}else{
		showmsg("系统提示", "注册失败，请稍后再试！", $links);
	}
		
}

kf_class::run_sys_func('ubb');
$row = $db->getone("select * from ".table('peizhi_mokuai')." WHERE module='duanxin' LIMIT 1");
$smarty->assign('sms', string2array($row['setting']));
if ($_GET['a'] == 'duanxin'){
	//判断关闭注册
	if ($_CFG['regtype'] == '1'){
		showmsg("系统提醒", "网站暂停短信注册; <a href=\"".get_link('a')."\">在线注册</a>。");
	}
	$_SEO['title'] = '短信注册'.$_CFG['site_namej'];
}else{
	//判断关闭注册
	if ($_CFG['regtype'] == '2'){
		showmsg("系统提醒", "网站暂停在线注册; <a href=\"".get_link('a')."&amp;a=duanxin\">短信注册</a>。");
	}
	$_SEO['title'] = '快速注册'.$_CFG['site_namej'];
}

?>