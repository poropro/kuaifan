<?php
/**
 * 会员身份认证
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

$huiyuan_val = array('userid'=>0, 'username'=>'游客', 'usersid'=>'null', );
$_GET['sid'] = $_GET['sid']?$_GET['sid']:$_POST['sid'];
if ($_GET['sid'] == "hide") $_CFG['hideusersid'] = 1;
if (empty($_GET['sid']) || $_CFG['hideusersid']){
    $_GET['sid'] = $_COOKIE["kf_usersid"];
	if (empty($_GET['sid']) && $_SESSION['sid']) $_GET['sid'] = $_SESSION['sid'];
}
if (strtolower($_GET['sid']) != 'null' && !empty($_GET['sid'])){
	$huiyuan_wheresql = " WHERE usersid ='{$_GET['sid']}'";
	$huiyuan_val = $db -> getone("select * from ".table('huiyuan').$huiyuan_wheresql." LIMIT 1");
	if (empty($huiyuan_val)){
		$huiyuan_val = array('userid'=>0, 'username'=>'游客', 'usersid'=>'null', );
	}else{
		//检测是否锁定
		if ($huiyuan_val['islock']) {
			$links[0]['title'] = '返回网站首页';
			$links[0]['href'] = get_link("vs","","1")."&amp;m=index&amp;sid=null";
			showmsg("系统提示", "你的账号已被锁定！", $links, $links[0]['href']);
		}
		//短信注册第一次进入设置用户名
		if ($huiyuan_val['regtype'] == 1){
			if ($_GET['c'] != 'username' && $_GET['m'] != 'admin') {
				tiaozhuan('index.php?m=huiyuan&amp;c=username&amp;sid='.$huiyuan_val['usersid'].'&amp;vs='.$_GET['vs'].'&amp;go_url='.urlencode(get_link('','&')));
			}
		}
		$hy_uparr = array();
		//3分钟更新一次在线
		if($huiyuan_val['indate'] < SYS_TIME - 180) {
			$hy_uparr['indate'] = $huiyuan_val['indate'] = SYS_TIME;
		}
		//60分钟更新一次会员组
		if($huiyuan_val['indateh'] < SYS_TIME - 60*60) {
			$hy_uparr['indateh'] = $huiyuan_val['indateh'] = SYS_TIME;
			//vip过期，更新vip和会员组
			if($huiyuan_val['overduedate'] < SYS_TIME) {
				$hy_uparr['vip'] = $huiyuan_val['vip'] = 0;
			}
			//检查用户积分，更新新用户组，除去邮箱认证、禁止访问、游客组用户、vip用户，如果该用户组不允许自助升级则不进行该操作
			if($huiyuan_val['point'] >= 0 && !in_array($huiyuan_val['groupid'], array('1', '7', '8')) && empty($huiyuan_val['vip'])) {
				$grouplist = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_zu.php');
				if(!empty($grouplist[$huiyuan_val['groupid']]['allowupgrade'])) {
					kf_class::run_sys_func('huiyuan');
					$check_groupid = _get_usergroup_bypoint($huiyuan_val['point']);
					if($check_groupid != $huiyuan_val['groupid']) {
						$hy_uparr['groupid'] = $huiyuan_val['groupid'] = $check_groupid;
					}
				}
				//用户组颜色
				if(!empty($grouplist[$huiyuan_val['groupid']]['usernamecolor'])) {
					$hy_uparr['colorname'] = $huiyuan_val['colorname'] = $grouplist[$huiyuan_val['groupid']]['usernamecolor'];
				}
			}
		}
		if (!empty($hy_uparr)) updatetable(table('huiyuan'), $hy_uparr, "userid=".$huiyuan_val['userid']);
	}
}
define('US_SID',($_CFG['hideusersid'])?"hide":$huiyuan_val['usersid']);
define('US_USERID',$huiyuan_val['userid']);
define('US_USERNAME',$huiyuan_val['username']);
$_GET['sid'] = US_SID;
?>