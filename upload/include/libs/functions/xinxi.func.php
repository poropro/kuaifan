<?php
/*
 * cms 信息模块相关函数
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

/**
 * 
 * 发送信息
 * @param $tousername 收信人
 * @param $username 发信人 ,0为系统
 * @param $subject 标题
 * @param $content 内容
 */
function add_message($tousername,$username,$subject,$content) {
	global $db;
	$message = array ();
	$message['send_from_id'] = $username;
	$message['send_to_id'] = $tousername;
	$message['subject'] = $subject;
	$message['content'] = $content;
	$message['message_time'] = SYS_TIME;
	$message['status'] = '1';
	$message['folder'] = 'inbox';

	if(empty($message['send_from_id']) && $message['send_from_id']!="0"){
		if (US_USERNAME >0){
			$message['send_from_id'] = US_USERNAME;
		}
	}
	if(empty($message['content'])){
		showmsg("系统提示","发信内空不能为空！");
	}
		
	$ucpmarr = add_message_uc($message);
	if (!empty($ucpmarr)){
		$message['pmid'] = $ucpmarr['pmid'];
		$message['plid'] = $ucpmarr['plid'];
		$message['authorid'] = $ucpmarr['authorid'];
	}
	$messageid = inserttable(table('xinxi'), $message, true);
	if(!$messageid){
		return FALSE;
	}else {
		return true;
	}
}

/**
 * 发送信息至cuenter
 * @param $_arr
 */
function add_message_uc($_arr){
	//ucenter发送短消息
	kf_class::ucenter();
	if (UC_USE == '1' && UC_MSG == '1'){
		$fromuid = empty($_arr['send_from_id'])?'0':id_name($_arr['send_from_id'], 1, 'ucuserid');
		if (empty($fromuid)) return ;
		$msgto = empty($_arr['send_to_id'])?'0':id_name($_arr['send_to_id'], 1, 'ucuserid');
		if (empty($msgto)) return ;
		$ucpmid = uc_pm_send($fromuid, $msgto, $_arr['subject'], $_arr['content']);	
		return uc_pm_viewnode($fromuid, 0, $ucpmid);
	}else{
		return array();
	}
}

/**
 * 信息内容替换
 */
function ubb_xinxi($strtxt){
	$ubbbl = trim($strtxt);
	if (is_null($ubbbl)) return;
	$ubbbl = str_replace("[sid]",$_GET['sid'],$ubbbl);
	$ubbbl = str_replace("[vs]",$_GET['vs'],$ubbbl);
	$ubbbl = str_replace("{sid}",$_GET['sid'],$ubbbl);
	$ubbbl = str_replace("{vs}",$_GET['vs'],$ubbbl);
	return $ubbbl;
}
?>