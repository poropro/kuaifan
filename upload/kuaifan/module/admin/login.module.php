<?php
/*
 * 登录后台
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
if (!empty($_POST['dosubmit'])){
	//验证码
	$links[0]['title'] = '登录后台';
	$links[0]['href'] = get_link("c,allow")."&amp;c=login";
	$links[1]['title'] = '返回网站首页';
	$links[1]['href'] = kf_url('index');
	if (!$_POST['yanzhengma'] && $yzmpeizhi['houtai']) showmsg("系统提醒", "请输入“验证码”！", $links);
	if ($yzmpeizhi['houtai']) {
		$_POST['ip'] = $_POST['ip']?$_POST['ip']:yanzhengmaip();
		$row = $db->getone("select * from ".table('yanzhengma')." WHERE captcha = '{$_POST['ip']}' AND code = '{$_POST['yanzhengma']}' LIMIT 1");
		if (empty($row)){
			showmsg("系统提醒", "请输入正确的“验证码”！", $links);
		}
		if (intval($timestamp - $row['time']) > 3*60){ //验证码3分钟过期
			showmsg("系统提醒", "“验证码”已过期，请返回换一张刷新！", $links);
		}
		unset($_POST['yanzhengma']);
	}

	$wheresql = " WHERE name ='{$_POST['username']}' and pass ='".md5s($_POST['userpass'])."'";
	$val = $db -> getone("select * from ".table('guanliyuan').$wheresql." LIMIT 1");
	if (!empty($val)){
		admin_log("“ID{$val['id']}[{$val['rank']}]{$val['name']}”登录成功!", $_POST['username'], 1);
		$setsql = "lasttime=nowtime,lastip=nowip,nowtime='".time()."',nowip='".$online_ip."',logins=logins+1";
		if (strlen($val['allow'])!=25) {$val['allow'] = generate_password(25);$setsql.= ",allow='".$val['allow']."'";}
		$db -> query("update ".table('guanliyuan')." set ".$setsql." WHERE id='{$val['id']}'");
		$_links[0]['title'] = '点击进入';
		$_links[0]['href'] = get_link("c,allow")."&amp;c=index&amp;allow=".$val['allow'];
		$_links[1]['title'] = '返回网站首页';
		$_links[1]['href'] = kf_url('index');
		$ip_area = kf_class::run_sys_class('ip_area');
		$ip_city = $ip_area->get($val['nowip']);
		if ($ip_city == 'Unknown' || $ip_city == 'IANA' || $ip_city == 'RIPE'){
			$ip_city_arr = $ip_area->getcitybyapi($val['nowip']);
			$ip_city = $ip_city_arr['city'];
		}
		$text = "登录成功！<br/>-------------<br/>上次登录:".date('Y-m-d H:i:s',$val['nowtime'])."<br/>上次IP:".$val['nowip'];
		$text.= $ip_city?"({$ip_city})":"";
		if (!$val['nowtime']) $text = "登录成功！<br/>-------------<br/>上次登录:无(第一次登陆)";
		showmsg("系统提醒", $text, $_links, $_links[0]['href']);
		//header("Location:".get_link("c,allow","&")."&c=index&allow=".$val['allow']); exit;
	}else{
		admin_log("登录失败!", $_POST['username']);
		showmsg("登录失败", "帐号或者密码错误！", $links, $links[0]['href']);
	}
}
$smarty->assign('yzmpeizhi', $yzmpeizhi);
?>