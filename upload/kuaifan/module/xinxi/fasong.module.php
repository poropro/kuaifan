<?php
/*
 * 发送信息
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
$_SEO['title'] = '发送短消息';
if ($_GET['messageid']){
	$whereval = "messageid='{$_GET['messageid']}' AND folder='inbox' AND send_to_id='{$huiyuan_val['username']}'";
	$row = $db->getone("select * from ".table('xinxi')." WHERE {$whereval} LIMIT 1");
	if (!empty($row)){
		//是否被查看状态
		if ($row['status']==1) {
			//ucenter标记短消息已读/未读状态
			kf_class::ucenter();
			if (UC_USE == '1'){
				if ($huiyuan_val['ucuserid'] > 0 && $row['plid'] > 0){
					$plidarr = explode(',', $row['plid']);
					uc_pm_readstatus($huiyuan_val['ucuserid'], array(), $plidarr, 0);
				}
			}
			//
			$db -> query("update ".table('xinxi')." set `status`='0' WHERE {$whereval}");
		}
		//发送对象
		if (empty($row['send_from_id'])){
			$templatefile.= ".xitong";
			$row['send_from_id'] = $row['send_from_id']?$row['send_from_id']:'系统';
			$_SEO['title'] = '系统发送的信息';
		}else{
			$row['huiyuan'] = $db->getone("select * from ".table('huiyuan')." WHERE username='{$row['send_from_id']}' LIMIT 1");
			$_SEO['title'] = '回复“'.$row['send_from_id'].'”信息';
		}
		$smarty->assign('message', $row);
	}else{
		$whereval = "messageid='{$_GET['messageid']}' AND folder='inbox' AND send_from_id='{$huiyuan_val['username']}'";
		$row = $db->getone("select * from ".table('xinxi')." WHERE {$whereval} LIMIT 1");
		if (!empty($row)){
			$html = $row['status']?"未读":"已读";
			$html = '
					发送给“'.$row['send_to_id'].'”的信息<br/>
					-------------<br/>
					标题: '.$row['subject'].'<br/>
					状态: '.$html.'<br/>
					时间: '.date("Y-m-d H:i:s",$row['message_time']).'<br/>
					内容: '.$row['content'].'
				';
			showmsg("发送给“{$row['send_to_id']}”的信息", $html);
		}
	}
}

//
$grouplist = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_zu.php');
$group_id = $grouplist[$huiyuan_val['groupid']];
if (empty($group_id['allowsendmessage'])) showmsg("系统提醒", "您所在的用户组不允许发送短信息！");

//
if ($_GET['username']){
	$huiyuan = $db->getone("select * from ".table('huiyuan')." WHERE username='{$_GET['username']}' LIMIT 1");
	if (!empty($huiyuan)){
		$_SEO['title'] = '给“'.$huiyuan['username'].'”发送信息';
		$smarty->assign('huiyuan', $huiyuan);
	}
}
if ($_POST['dosubmit']){
	$links[0]['title'] = '返回继续发送';
	$links[0]['href'] = get_link();
	$links[1]['title'] = '返回会员中心';
	$links[1]['href'] = get_link("vs|sid",'','1').'&amp;m=huiyuan&amp;c=index';
	if ($row) $_POST['username'] = $row['send_from_id'];
	if ($huiyuan) $_POST['username'] = $huiyuan['username'];
	if (!$_POST['username']) showmsg("系统提醒", "请输入“用户名”、“用户ID”！", $links);
	if (!$_POST['subject']) showmsg("系统提醒", "请输入“标题”！", $links);
	if (!$_POST['content']) showmsg("系统提醒", "请输入“内容”！", $links);
	if (strlen($_POST['subject']) < 2 || strlen($_POST['subject']) > 80) showmsg("系统提醒", "“标题”长度范围在2到80个字符！", $links);
	if (strlen($_POST['content']) < 2 || strlen($_POST['content']) > 500) showmsg("系统提醒", "“内容”长度范围在2到500个字符！", $links);
	//验证码
	if (!$_POST['yanzhengma'] && $yzmpeizhi['xinxi']) showmsg("系统提醒", "请输入“验证码”！", $links);
	if ($yzmpeizhi['xinxi']) {
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
		
	$usertype = $_POST['usertype']?$_POST['usertype']:'username';
	$row = $db->getone("select * from ".table('huiyuan')." WHERE {$usertype} = '{$_POST['username']}' LIMIT 1");
	if (empty($row)) showmsg("系统提醒", "你要发送的会员不存在！", $links);
	if ($row['username'] == $huiyuan_val['username']) showmsg("系统提醒", "不能给自己发送信息！", $links);
	$subject = new_html_special_chars($_POST['subject']);
	$content = new_html_special_chars($_POST['content']);
	add_message($row['username'],$huiyuan_val['username'],$subject,$content);
	showmsg("系统提醒", "信息发送成功！", $links);
}
$smarty->assign('yzmpeizhi', $yzmpeizhi);
?>