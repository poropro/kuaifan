<?php
/*
 * 短信 注册/找回密码
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

if (empty($_CFG['sms_key'])) $_CFG['sms_key'] = '52b0fb6a69503f41cdadf050504f62ed';
$info = array();
$info['phone'] = $_REQUEST['phone'];
$info['message'] = $_REQUEST['message'];
$info['smskey'] = $_REQUEST['smskey'];
$info['username'] = $_REQUEST['phone'];
if (strlen($info['phone']) != 11) exit('0'); //手机号码错误
if ($info['smskey'] != $_CFG['sms_key']) exit('2'); //密令验证失败
if(strpos($info['message'], '#')!==false){
	$_messagearr = explode('#', $info['message']);
	if ($_messagearr[0] != $_CFG['sms_zl']) exit(); //指定错误
	$info['password'] = $_messagearr[1];
	if (!empty($_messagearr[2])){
		$info['username'] = $_messagearr[1];
		$info['password'] = $_messagearr[2];
	}
}
$info['password'] = !empty($info['password'])?$info['password']:substr($info['phone'], -6, 6);
$row = $db->getone("select * from ".table('huiyuan')." where mobile='{$info['phone']}' OR username='{$info['phone']}' LIMIT 1");
if (!empty($row)){
	if (!$db->query("UPDATE ".table('huiyuan')." SET password='".md5s($info['password'])."' WHERE userid=".$row['userid'])){
		exit('0');
	}else{
		exit('11'); //重置密码成功
	}
}
$row = $db->getone("select * from ".table('huiyuan')." where username='{$info['username']}' LIMIT 1");
if (!empty($row)){
	exit('8'); //用户名已经存在
}
//判断关闭短信注册
if ($_CFG['regtype'] == '1'){
	exit('0');
}
$info['email'] = $info['phone']."@".substr($info['phone'], 0, 3).".com";

//UCenter会员注册
$ucuserid = 0;
kf_class::ucenter();
if (UC_USE == '1'){
	list($ucuid, $uc['username'], $uc['email']) = uc_get_user($info['phone']);
	if ($ucuid > 0){
		$ucuserid = $ucuid;
	}else{
		$ucuid= uc_user_register($info['phone'], $info['password'], $info['email'], '', '', $online_ip);
		if(is_numeric($ucuid)) {
			switch ($ucuid) {
				case '-1':
					exit('0');
					break;
				case '-2':
					exit('0');
					break;
				case '-3':
					exit('0');
					break;
				case '-4':
					exit('0');
					break;
				case '-5':
					exit('0');
					break;
				case '-6':
					exit('0');
					break;
				default :
					$ucuserid = $ucuid;
					break;
			}
		}else{
			exit('0');
		}
	}
}

$row = $db->getone("select * from ".table('peizhi_mokuai')." where module='huiyuan' LIMIT 1");
$member_setting = string2array($row['setting']);
$modellistarr = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_moxing.php');
reset($modellistarr);
$info['modelid'] = current($modellistarr);
$info['modelid'] = $info['modelid']['id'];

$userinfo = array();
$userinfo['ucuserid'] = $ucuserid;
$userinfo['encrypt'] = generate_password(6);
$userinfo['username'] = $info['phone'];
$userinfo['nickname'] = '会员'.substr($info['phone'], -4, 4);
$userinfo['email'] = $info['email'];
$userinfo['password'] = md5s($info['password']);
$userinfo['modelid'] = $info['modelid'];
$userinfo['regip'] = $online_ip;
$userinfo['point'] = $member_setting['defualtpoint'] ? $member_setting['defualtpoint'] : 0;
$userinfo['amount'] = $member_setting['defualtamount'] ? $member_setting['defualtamount'] : 0;
$userinfo['regdate'] = $userinfo['lastdate'] = $timestamp;
$userinfo['site'] = $_CFG['site'];
$userinfo['mobile'] = $info['phone'];
$userinfo['regtype'] = 1;
$userinfo['groupid'] = _get_usergroup_bypoint(0);
	
$_userid = inserttable(table('huiyuan'), $userinfo, true);
if ($_userid > 0){
	exit('1');
}else{
	exit('err');
}

?>