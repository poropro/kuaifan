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


switch ($_GET['a']) {
	case "shuaxin":
		$row['newkey'] = generate_password(32);
		$htmlid = chongzhi_open(SYS_URL."?m=shuaxin&keyval={$row['key']}&newkeyval={$row['newkey']}&url=".urlencode($_SERVER['HTTP_HOST']), 'keyidstart', 'keyidend');
		if (intval($htmlid) > 0){
			$row['key'] = $row['newkey'];
			$db -> query("update ".table('zhifu')." set `key` = '{$row['key']}' WHERE path='shenzhouxing'");
			showmsg("系统提醒", "刷新成功，系统正常！");
		}else{
			showmsg("系统提醒", "刷新失败，请返回重新刷新！");
		}
		break;
}

?>