<?php
/*
 * cms 会员相关函数
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
 * 更新会员数据
 * @param $tablename 表
 * @param $setsqlarr 数据
 * @param $userid 会员ID
 */
function updatetable_detai($tablename, $setsqlarr, $userid) {
	global $db;
	$wheresqlarr = 'userid = '.$userid;
	if ($userid > 0){
		if ($db -> getone("select * from ".$tablename." WHERE ".$wheresqlarr."")){
			updatetable($tablename, $setsqlarr, $wheresqlarr);
		}else{
			$setsqlarr['userid'] = $userid;
			inserttable($tablename,$setsqlarr);
		}
	}
}



function _get_usergroup_bypoint($point=0) {
	global $db;
	$groupid = 2;
	if(empty($point)) {
		$member_setting = $db -> getone("select * from ".table('peizhi_mokuai')." WHERE module='huiyuan'");
		$member_setting = string2array($member_setting['setting']);
		$point = $member_setting['defualtpoint'] ? $member_setting['defualtpoint'] : 0;
	}
	$grouplist = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_zu.php');

	foreach ($grouplist as $k=>$v) {
		$grouppointlist[$k] = $v['point'];
	}
	arsort($grouppointlist);

	//如果超出用户组积分设置则为积分最高的用户组
	if($point > max($grouppointlist)) {
		$groupid = key($grouppointlist);
	} else {
		foreach ($grouppointlist as $k=>$v) {
			if($point >= $v) {
				$groupid = $tmp_k;
				break;
			}
			$tmp_k = $k;
		}
	}
	return $groupid;
}
/**
 * 
 * 获取vip图片
 * @param $_arr 会员信息数组
 */
function get_vip($_arr = array()){
	$_vip = array();
	if (empty($_arr)) return $_vip;
	if ($_arr['vip']){
		$_vip['img'] = IMG_PATH.'icon/vip.gif';
		$_vip['name'] = 'vip会员';
	}elseif ($_arr['overduedate']){
		$_vip['img'] = IMG_PATH.'icon/vip-expired.gif';
		$_vip['name'] = '过期vip';
	}
	return $_vip;
}
/**
 * 
 * 获取会员颜色名称
 * @param 会员名 $_name
 * @param 颜色代码  $_color
 */
function get_colorname($_name, $_color=''){
	if (!empty($_color)){
		if ($_GET['vs']==1){
			$_name = '<img src="index.php?m=api&amp;c=yansezi&amp;str='.urlencode($_name).'&amp;color='.$_color.'" alt="'.$_name.'"/>';
		}else{
			$_name = '<span style="color: #'.$_color.'">'.$_name.'</span>';
		}
	}
	return $_name;
}
/**
 * 
 * 获取会员信息
 * @param $_userstr 会员信息
 * @param $_gettype 获取信息字段，默认userid字段
 * @param $_detail 是否获取详情
 */
function get_user($_userstr, $_gettype = '', $_detail = ''){
	global $db;
	if (!is_numeric($_userstr) || empty($_userstr)) return array();
	$_gettype = $_gettype?$_gettype:'userid';
	$_sql = "select * from ".table('huiyuan')." WHERE `{$_gettype}`='{$_userstr}'";
	$userdb = $db -> getone($_sql);
	if ($_detail && $userdb){
		$modellistarr = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_moxing.php');
		$detaildb = $db -> getone("select * from ".table('huiyuan_diy_'.$modellistarr[$userdb['modelid']]['tablename'])." WHERE userid = {$userdb['userid']} LIMIT 1");
		$userdb = array_merge($userdb, $detaildb);
	}
	return $userdb;
}
/**
 * 
 * 奖罚积分
 * @param $_userid 会员id
 * @param $_num 奖罚数量
 * @param $_type 货币类型：0积分、1金币
 * @param $_title 说明
 */
function set_jiangfa($_userid, $_num, $_type = 0, $_title = ''){
	global $db,$online_ip,$_CFG;
	if (!is_numeric($_userid) || !is_numeric($_num)) return ;
	if ($_num == 0) return ;
	$_hy = $db -> getone("select * from ".table('huiyuan')." WHERE userid='{$_userid}' LIMIT 1");	
	if (empty($_hy)) return ;
	$_info = $_inuser = array();
	$_info['userid'] = $_userid;
	$_info['type'] = $_type;
	$_info['add'] = $_num < 0?'cut':'add'; //add为增加、cut为减少
	$_info['title'] = $_title;
	$_info['num'] = abs($_num);
	$_info['time'] = SYS_TIME;
	$_info['ip'] = $online_ip;
	$_info['site'] = $_CFG['site'];
	if ($_type == 1){
		$_info['nums'] = $_hy['amount'] + $_num;
		$_inuser['amount'] = $_info['nums'];
	}else{
		$_info['nums'] = intval($_hy['point'] + $_num);
		$_inuser['point'] = $_info['nums'];
	}
	updatetable(table('huiyuan'), $_inuser, "userid='{$_userid}'");
	inserttable(table('jiangfa'), $_info);
}
/**
 * 
 * 支付密码判断函数
 * @param 会员信息数组 $_arr
 * @param 支付密码，留空不判断 $_zfmm
 * @param 设置值后不改新密码  $_newzf
 */
function run_zhifumima($_arr, $_zfmm = '', $_newzf = ''){
	$return = false;
	$updataarr = array();
	if (empty($_arr)) return $return;
	
	//未设置支付密码
	if (empty($_arr['passwordpay'])){
		showmsg("系统提醒", "您尚未设置支付密码,，请登录会员中心设置。");
	}
	//24小时内输入错误3次
	if ($_arr['payerr'] >= 3 && SYS_TIME - $_arr['payerrtime'] < 86400){
		showmsg("系统提醒", "您帐号支付功能已经被封锁24小时;<br/>封锁时间: ".date('Y-m-d H:i:s',$_arr['payerrtime'])."");
	}
	//24小时以外有密码输入错误则清零
	if ($_arr['payerr'] > 0 && SYS_TIME - $_arr['payerrtime'] > 86400){
		$updataarr['payerr'] = $_arr['payerr'] = 0;
	}
	//判断支付密码
	if ($_zfmm){
		if (create_password($_zfmm, $_arr['encrypt']) == $_arr['passwordpay']){
			//每次输入正确后将重置密令
			if (empty($_newzf)){
				$updataarr['payerr'] = $_arr['payerr'] = 0;
				$updataarr['encrypt'] = generate_password(6);
				$updataarr['passwordpay'] = create_password($_zfmm, $updataarr['encrypt']);
			}
			$return = true;
		}else{
			$updataarr['payerr'] = $_arr['payerr'] + 1;
			$updataarr['payerrtime'] = SYS_TIME;
		}
	}
	if (!empty($updataarr)){
		if (!updatetable(table('huiyuan'), $updataarr, "userid={$_arr['userid']}")){
			showmsg("系统提醒", "参数错误，请稍后再试。");
		}
	}
	return $return;
}
/**
 * 
 * @param $password 密码
 * @param $random 随机数
 */
function create_password($password, $random='') {
	$random = $random?$random:generate_password(6);
	return md5(md5($password).$random);
}
/**
 * 更新会员勋章数据
 * @param $userid 会员ID
 */
function set_xunzhang($userid){
	global $db,$_CFG;
	if ($userid < 1) return false;
	$cache_file_path = KF_ROOT_PATH.'caches/caches_xunzhang/'.$userid.'.php';
	$rowhy = $db -> getall("select * from ".table('huiyuan_xunzhang')." WHERE `type`='huiyuan' AND `dataid`=".intval($userid)." ORDER BY `intime` DESC");
	if (empty($rowhy)) {
		@unlink($cache_file_path);
		return false;
	}
	$_arrhy = array();
	foreach($rowhy as $_v) {
		if (file_exists(KF_ROOT_PATH.'uploadfiles/avatar/xunzhang/'.$_v['catid'].'.gif')) {
			$_v['imgurl'] = 'uploadfiles/avatar/xunzhang/'.$_v['catid'].'.gif';
		}else{
			$_v['imgurl'] = "";
		}
		$_arrhy[] = array(
			'catid'=>$_v['catid'],
			'title'=>$_v['title'],
			'imgurl'=>$_v['imgurl']
		);
	}
	if (empty($_arrhy)) {
		@unlink($cache_file_path);
		return false;
	}
	//
	$content = "<?php\r\n";
	$content .= "\$data = " . var_export($_arrhy, true) . ";\r\n";
	$content .= "?>";
	make_dir(dirname($cache_file_path));
	if (!file_put_contents($cache_file_path, $content, LOCK_EX)){
		$fp = @fopen($cache_file_path, 'wb+');
		if (!$fp){
			exit('生成缓存文件失败');
		}
		if (!@fwrite($fp, trim($content))){
			exit('生成缓存文件失败');
		}
		@fclose($fp);
	}
	return true;
}
/**
 * 修改会员用户名
 * @param $username 修改成的用户名
 * @param $userid 会员ID
 */
function update_username($username, $userid){
	global $db;
	if (UC_USE == '1'){
		showmsg("系统提示", "网站整合了UCenter无法修改用户名！");
	}
	$userid = intval($userid);
	$username = trim($username);
	if ($userid < 1) showmsg("系统提示", "用户不存在！");

	if (!$username) showmsg("系统提示", "请输入“用户名”！");
	if (strlen($username) < 3 || strlen($username) > 20) showmsg("系统提示", "“用户名”不得小于3位或者大于20位！");
	if (!is_username($username)) showmsg("系统提示", "“用户名”格式输入错误，可能还有非法字符！");
	
	$_rowhy = $db->getone("select * from ".table('huiyuan')." where userid={$userid} LIMIT 1");
	if (empty($_rowhy)) showmsg("系统提示", "用户不存在！");

	$_row = $db->getone("select * from ".table('huiyuan')." where username = '{$username}' AND userid!={$userid} LIMIT 1");
	if (!empty($_row)) showmsg("系统提示", "“用户名称”已被使用！", $links);

	$_row = $db->getone("select * from ".table('huiyuan_shenhe')." where username = '{$username}' AND userid!={$userid} LIMIT 1");
	if (!empty($_row)) showmsg("系统提示", "“用户名称”已被使用！", $links);

	$_row = $db->getall("SELECT * FROM ".table('neirong_moxing')." ORDER BY id asc");
	foreach($_row as $_val){
		$db -> query("update ".table('diy_'.$_val['tablename'])." set `username`='".$username."' WHERE `username`='{$_rowhy['username']}' AND `sysadd`=0");
	}
	$db -> query("update ".table('guanggao_data')." set `username`='".$username."' WHERE `username`='{$_rowhy['username']}'");
	$db -> query("update ".table('lianjie_data')." set `username`='".$username."' WHERE `username`='{$_rowhy['username']}'");
	$db -> query("update ".table('neirong_fabu')." set `username`='".$username."' WHERE `username`='{$_rowhy['username']}'");
	$db -> query("update ".table('pinglun_data_1')." set `username`='".$username."' WHERE `username`='{$_rowhy['username']}'");
	$db -> query("update ".table('tongji')." set `username`='".$username."' WHERE `username`='{$_rowhy['username']}'");
	$db -> query("update ".table('huiyuan')." set `username`='".$username."' WHERE `username`='{$_rowhy['username']}'");
}
?>