<?php
/*
 * 会员登录
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
if ($_POST['dosubmit'] || defined('DENGLU_WHERE')){
	$links[0]['title'] = '重新登录';
	$links[0]['href'] = get_link();
	$links[1]['title'] = '返回网站首页';
	$links[1]['href'] = kf_url('index');
	if(defined('DENGLU_WHERE')) {
		$wheresql = " WHERE ".DENGLU_WHERE;
	}else{
		if (!$_POST['username']) showmsg("系统提示", "请输入“ID/用户名/手机号码/邮箱”！", $links);
		if (!$_POST['userpass']) showmsg("系统提示", "请输入“密码”！", $links);
		//验证码
		if (!$_POST['yanzhengma'] && $yzmpeizhi['denglu']) showmsg("系统提示", "请输入“验证码”！", $links);
		if ($yzmpeizhi['denglu']) {
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
		//		
		$wheresql = " WHERE (userid ='{$_POST['username']}' OR username ='{$_POST['username']}' OR email ='{$_POST['username']}' OR mobile ='{$_POST['username']}') AND password ='".md5s($_POST['userpass'])."'";
	}
	$val = $db -> getone("select * from ".table('huiyuan').$wheresql." LIMIT 1");
	if (!empty($val)){
		$hy_uparr = array('lastdate'=>SYS_TIME, 'lastip'=>$online_ip, 'loginnum'=>$val['loginnum']+1);
		if (strlen($val['usersid'])!=24) {$hy_uparr['usersid'] = $val['usersid'] = generate_password(24);}
		if (strlen($val['encrypt'])!=6)  {$hy_uparr['encrypt'] = $val['encrypt'] = generate_password(6);}
		//如果用户被锁定
		if($val['islock']) {
			showmsg("系统提示", "你的账号已被锁定！", $links);
		}
		//ucenter登陆部份
		$uctext = "";
		kf_class::ucenter();
		if (UC_USE == '1'){
			if(!defined('DENGLU_WHERE')) {
				list($ucuid, $uc['username'], $uc['password'], $uc['email']) = uc_user_login($val['username'], $_POST['userpass'], 0);
				if($ucuid == -1) {	//uc不存在该用户，调用注册接口注册用户
					$ucuid = uc_user_register($val['username'] , $_POST['userpass'], $val['email']);
					if($ucuid >0) {
						$hy_uparr['ucuserid'] = $ucuid;
					}
				}
				$ucsynlogin = uc_user_synlogin($ucuid); //同步登录
				if ($_GET['vs'] == '1'){
					preg_match_all('/<script.+?src=\"(.+?)\".+?>/i', $ucsynlogin, $match);
					foreach ($match[1] as $_val) {
						$_val = str_replace('&', '&amp;', $_val);
						$uctext.= '<img src="'.$_val.'" alt=""/>';
					}
				}else{
					$uctext.= $ucsynlogin; //同步登录
				}
			}
		}
		//vip过期，更新vip和会员组
		if($val['overduedate'] < SYS_TIME) {
			$hy_uparr['vip'] = $val['vip'] = 0;
		}
		//检查用户积分，更新新用户组，除去邮箱认证、禁止访问、游客组用户、vip用户，如果该用户组不允许自助升级则不进行该操作
		if($val['point'] >= 0 && !in_array($val['groupid'], array('1', '7', '8')) && empty($val['vip'])) {
			$grouplist = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_zu.php');
			if(!empty($grouplist[$val['groupid']]['allowupgrade'])) {
				$check_groupid = _get_usergroup_bypoint($val['point']);
				if($check_groupid != $val['groupid']) {
					$hy_uparr['groupid'] = $val['groupid'] = $check_groupid;
				}
			}
		}
		updatetable(table('huiyuan'), $hy_uparr, "userid=".$val['userid']);
		
		$_GET['sid'] = $val['usersid'];
		$_SESSION['sid'] = $val['usersid'];
		if ($_POST['miandenglu'] > 0){
			setcookie("kf_usersid", $val['usersid'], SYS_TIME+86400*intval($_POST['miandenglu']));
		}
		if(defined('DENGLU_WHERE')) exit("1");
		
		if ($val['lastip']){
			$ip_area = kf_class::run_sys_class('ip_area');
			$ip_city = $ip_area->get($val['lastip']);
			if ($ip_city == 'Unknown' || $ip_city == 'IANA' || $ip_city == 'RIPE'){
				$ip_city_arr = $ip_area->getcitybyapi($val['lastip']);
				$ip_city = $ip_city_arr['city'];
			}
		}
		$_go_link = $_GET['go_url']?'<a href="'.goto_url($_GET['go_url']).'">继续访问上一页面</a><br/>':'';
		$text = "登录成功！<br/>欢迎您:{$val['nickname']}<br/>您的ID:{$val['userid']}<br/>{$_go_link}-------------<br/>";
		if (!$val['lastdate'] && $val['regdate'] = $val['lastdate']) {
			$text.= "上次登录:无(第一次登陆)";
		}else{
			$text.= "上次登录:".date('Y-m-d H:i:s',$val['lastdate']);
			$text.= $val['lastip']?"<br/>上次IP:".$val['lastip']:"";
			$text.= $ip_city?"({$ip_city})":"";
		}
		set_xunzhang($val['userid']); //刷新勋章
		$links[0]['title'] = '进入会员中心';
		$links[0]['href'] = get_link('c|sid|go_url')."&amp;c=index&amp;sid=".$val['usersid'];
		$links[1]['title'] = '返回网站首页';
		$links[1]['href'] = kf_url('index');
		showmsg("登录成功", $text.$uctext, $links);
	}else{
		//不是纯数字也不是邮箱地址那就是用户名登陆了
		if (!is_email($_POST['username']) && !is_num($_POST['username'])){
			//ucenter登陆部份
			kf_class::ucenter();
			if (UC_USE == '1'){
				list($ucuid, $uc['username'], $uc['password'], $uc['email']) = uc_user_login($_POST['username'], $_POST['userpass'], 0);
				if ($ucuid > 0) {
					//随机生成手机号码并检测不重复
					$usermobile = "190".generate_password(8,1);
					$usermobiledb = $db->getone("select * from ".table('huiyuan')." where mobile = '{$usermobile}' LIMIT 1");
					if (!empty($usermobiledb)) showmsg("系统提示", "网络繁忙，请稍后再试-1！", $links);
					//当前使用只在ucenter中存在，在主网站中是不存在的。需要进行注册。
					$R = get_uc_database($ucuid);
					$row = $db->getone("select * from ".table('peizhi_mokuai')." where module='huiyuan' LIMIT 1");
					$member_setting = string2array($row['setting']);
					$modellistarr = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_moxing.php');
					reset($modellistarr);
					$usermodelid = current($modellistarr);
					$usermodelid = $usermodelid['id'];
					$userinfo = array();
					$userinfo['ucuserid'] = $ucuid;
					$userinfo['encrypt'] = generate_password(6);
					$userinfo['username'] = $R['username'];
					$userinfo['nickname'] = $R['username'];
					$userinfo['email'] = $R['email'];
					$userinfo['password'] = md5s($_POST['userpass']);
					$userinfo['modelid'] = $usermodelid;
					$userinfo['regip'] = $R['regip'];
					$userinfo['point'] = $member_setting['defualtpoint'] ? $member_setting['defualtpoint'] : 0;
					$userinfo['amount'] = $member_setting['defualtamount'] ? $member_setting['defualtamount'] : 0;
					$userinfo['regdate'] = $R['regdate'];
					$userinfo['lastdate'] = $R['lastlogindate'];
					$userinfo['site'] = $_CFG['site'];
					$userinfo['mobile'] = $usermobile;
					$userinfo['groupid'] = _get_usergroup_bypoint(0);
					$_userid = inserttable(table('huiyuan'), $userinfo, true);
					if(defined('DENGLU_WHERE')) exit("1");
					if ($_userid > 0){
						showmsg("系统提示", "请重新登录！", $links);
					}else{
						showmsg("系统提示", "网络繁忙，请稍后再试-2！", $links);
					}
				}
			}
		}
		if(defined('DENGLU_WHERE')) exit("-1");
		$wheresql = " WHERE (userid ='{$_POST['username']}' OR username ='{$_POST['username']}' OR email ='{$_POST['username']}' OR mobile ='{$_POST['username']}') AND password ='".$_POST['userpass']."'";
		$val_shenhe = $db -> getone("select * from ".table('huiyuan_shenhe').$wheresql." LIMIT 1");
		if ($val_shenhe) {
			showmsg("系统提示", "请耐心等待管理员审核！", $links);
		}
		showmsg("系统提示", "帐号或者密码错误！", $links);
	}
}
$_SEO['title'] = '欢迎登录'.$_CFG['site_namej'];
$_huiyuan = $db -> getall("select * from ".table('huiyuan')." ORDER BY `userid` DESC LIMIT 0, 5");
$_n = 1;
foreach ($_huiyuan as $_k => $_v){
	if (strlen($_v['mobile']) == 11){
		$_huiyuan[$_k]['title'] = substr($_v['mobile'], 0, 4).'*****'.substr($_v['mobile'], -2, 2);
	}else{
		$_huiyuan[$_k]['title'] = substr(substr($_v['email'], 0, strpos($_v['email'],'@')),0,2).'****@'.preg_replace("/(.+?)\@(.+?)/is","\\2",$_v['email']);
	}
	$_huiyuan[$_k]['n'] = $_n;
	$_n++;
}
$smarty->assign('yzmpeizhi', $yzmpeizhi);
$smarty->assign('zuixin', $_huiyuan);

?>