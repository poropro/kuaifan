<?php
/*
 * 帐号管理
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

require(KF_INC_PATH.'denglu.php');
$templatefile.= '/'.$_GET['a'];

switch ($_GET['a']) {
	case 'xinxi'://修改信息
		$modellistarr = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_moxing.php');
		$huiyuan_val['field'] = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'model'.DIRECTORY_SEPARATOR.'model_field_'.$huiyuan_val['modelid'].'.huiyuan.cache.php');
		$huiyuan_val['detail'] = $db -> getone("select * from ".table('huiyuan_diy_'.$modellistarr[$huiyuan_val['modelid']]['tablename'])." WHERE userid = {$huiyuan_val['userid']} LIMIT 1");
		$smarty->assign('huiyuan_val', $huiyuan_val);
		$grouplist = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_zu.php');
		$groupuser = $grouplist[$huiyuan_val['groupid']];
		if($_POST['dosubmit']) {
			$links[0]['title'] = '重新修改';
			$links[0]['href'] = get_link();
			$links[1]['title'] = '返回会员中心';
			$links[1]['href'] = get_link("vs|sid",'','1').'&amp;m=huiyuan&amp;c=index';
			$info = $detail = array();
			$info['nickname'] = $_POST['info_nickname'];
			$info['qianming'] = ($groupuser['qianminglength'] > 0)?cut_str($_POST['info_qianming'],$groupuser['qianminglength']):$_POST['info_qianming'];
			if (!$info['nickname']) showmsg("系统提醒", "请输入“昵称”！", $links);
			if (strlen($info['nickname'])<2 || strlen($info['nickname'])>20) showmsg("系统提醒", "“昵称”应该为2-20个字符之间！", $links);
				
			foreach ($_POST as $_k => $_v){
				$_k = (substr($_k,7,5) == 'date_')?substr($_k,12):substr($_k,7);
				$_poarr = $huiyuan_val['field'][$_k];
				if (empty($_poarr)) continue;
				$_poarrset = string2array($_poarr['setting']);
				//最小长度
				if (strlen($_v) < intval($_poarr['minlength']) && intval($_poarr['minlength']) > 0){
					showmsg("系统提醒", "“{$_poarr['name']}”最小输入长度为".intval($_poarr['minlength'])."。");
				}
				//最大长度
				if (strlen($_v) > intval($_poarr['maxlength']) && intval($_poarr['maxlength']) > 0){
					if ($_poarr['field'] == 'description'){
						$_v = substr($_v, 0, intval($_poarr['maxlength']));
					}else{
						showmsg("系统提醒", "“{$_poarr['name']}”最大输入长度为".intval($_poarr['maxlength'])."。");
					}
				}
				//数字字段
				if ($_poarr['type'] == 'number'){
					//小数位数
					if ($_poarrset['decimaldigits'] > 0){
						$_v = round($_v, $_poarrset['decimaldigits']);
					}else{
						$_v = intval($_v);
					}
					//取值范围
					if ($_v < $_poarrset['minnumber'] && $_poarrset['minnumber']){
						showmsg("系统提醒", "“{$_poarr['name']}”取值范围最小值为{$_poarrset['minnumber']}。");
					}
					if ($_v > $_poarrset['maxnumber'] && $_poarrset['maxnumber']){
						showmsg("系统提醒", "“{$_poarr['name']}”取值范围最大值为{$_poarrset['maxnumber']}。");
					}
				}
				//时间字段
				if ($_poarr['type'] == 'datetime'){
					$_v = strtotime($_v);
				}
				$detail[$_k] = $_v;

			}
			$wheresql = "userid = ".$huiyuan_val['userid'];
			if ($info) updatetable(table('huiyuan'), $info, $wheresql);
			if ($detail) updatetable_detai(table('huiyuan_diy_'.$modellistarr[$huiyuan_val['modelid']]['tablename']), $detail, $huiyuan_val['userid']);
			showmsg("系统提醒", "修改成功！", $links);
		}
		break;
	case 'touxiang':
		require(KF_INC_PATH.'denglu.php');
		$smarty->assign('huiyuan', $huiyuan_val);
		if($_POST['dosubmit']) {
			$links[0]['title'] = '重新修改';
			$links[0]['href'] = get_link();
			$links[1]['title'] = '返回会员中心';
			$links[1]['href'] = get_link("vs|sid",'','1').'&amp;m=huiyuan&amp;c=index';
			kf_class::run_sys_func('upload');
			$avatardir = getavatar($huiyuan_val['userid'],1);
			$pdefault = $huiyuan_val['userid'].'_default.gif';
			$avatar = '0'; $_fileret = false;
			if ($_FILES['shangchuan']['name']){
				$_file_size = intval(ini_get('upload_max_filesize')*1024);
				$_fileret = _asUpFiles($avatardir, 'shangchuan', $_file_size, 'gif/jpg/jpeg/png', $pdefault, true);
			}elseif (get_extension($_POST['wangluo']) == 'jpg' || get_extension($_POST['wangluo']) == 'gif' || get_extension($_POST['wangluo']) == 'jpeg' || get_extension($_POST['wangluo']) == 'png'){
				$_fileret = get_file($_POST['wangluo'],$avatardir,$pdefault);
			}
			if ($_fileret){
				makethumb($avatardir.$pdefault, $avatardir, 180, 180, 0, $huiyuan_val['userid'].'_180.gif');
				makethumb($avatardir.$pdefault, $avatardir, 90, 90, 0, $huiyuan_val['userid'].'_90.gif');
				makethumb($avatardir.$pdefault, $avatardir, 45, 45, 0, $huiyuan_val['userid'].'_45.gif');
				makethumb($avatardir.$pdefault, $avatardir, 30, 30, 0, $huiyuan_val['userid'].'_30.gif');
				$avatar = '1';
			}
			if ($avatar == '1'){
				//ucenter修改用户头像
				kf_class::ucenter();
				if (UC_USE == '1'){
					if ($huiyuan_val['ucuserid'] > 0){
						$_avauculr = UC_API.'/avatar_kuaifan.php?uid='.$huiyuan_val['ucuserid'].'&wangluo='.urlencode(getavatar($huiyuan_val['userid'], 'default'));
						dfopen($_avauculr);
					}
				}
				//
				$wheresql = "userid = ".$huiyuan_val['userid'];
				$db->query("UPDATE ".table('huiyuan')." SET avatar='1' WHERE ".$wheresql);
				showmsg("系统提醒", "保存成功！", $links);
			}else{
				showmsg("系统提醒", "保存失败！", $links);
			}
		}
		if($_GET['dosubmit']) {
			$links[0]['title'] = '重新修改';
			$links[0]['href'] = get_link('dosubmit');
			$links[1]['title'] = '返回会员中心';
			$links[1]['href'] = get_link("vs|sid",'','1').'&amp;m=huiyuan&amp;c=index';
			//ucenter删除用户头像
			kf_class::ucenter();
			if (UC_USE == '1'){
				if (!empty($huiyuan_val['ucuserid'])) uc_user_deleteavatar($huiyuan_val['ucuserid']);
			}
			//
			del_touxiang($huiyuan_val['userid']);
			showmsg("系统提醒", "操作成功！", $links);
		}
		break;
	case 'mima':
		if($_POST['dosubmit']) {
			$links[0]['title'] = '重新修改';
			$links[0]['href'] = get_link();
			$links[1]['title'] = '返回会员中心';
			$links[1]['href'] = get_link("vs|sid",'','1').'&amp;m=huiyuan&amp;c=index';
				
			if (!$_POST['email']) showmsg("系统提醒", "请输入“邮箱”！", $links);
			if (!$_POST['password']) showmsg("系统提醒", "请输入“原密码”！", $links);
			if (!$_POST['newpassword']) showmsg("系统提醒", "请输入“新密码”！", $links);
			if (!$_POST['renewpassword']) showmsg("系统提醒", "请输入“重复新密码”！", $links);
			if ($_POST['newpassword'] != $_POST['renewpassword']) showmsg("系统提醒", "请相同输入“新密码”与“重复新密码”！", $links);
			if (strlen($_POST['newpassword']) < 6 || strlen($_POST['newpassword']) > 20) showmsg("系统提醒", "“新密码”不得小于6位或者大于20位！", $links);
			if (strlen($_POST['email']) < 3 || strlen($_POST['email']) > 32) showmsg("系统提醒", "“邮箱”不得小于3位或者大于32位！", $links);
			if (!is_email($_POST['email'])) showmsg("系统提醒", "“邮箱”格式输入错误！", $links);
			if (md5s($_POST['password']) != $huiyuan_val['password']) showmsg("系统提醒", "“原密码”错误！", $links);
			$row = $db->getone("select * from ".table('huiyuan')." where email = '{$_POST['email']}' AND userid != {$huiyuan_val['userid']} LIMIT 1");
			if (!empty($row)) showmsg("系统提醒", "“邮箱”已存在！", $links);
			$info = array();
			$info['email'] = $_POST['email'];
			$info['password'] = md5s($_POST['newpassword']);
			$wheresql = "userid = ".$huiyuan_val['userid'];
			if ($info) {
				//ucenter更新用户资料
				kf_class::ucenter();
				if (UC_USE == '1'){
					if ($row['ucuserid'] > 0){
						$ucresult = uc_user_edit($row['username'], $_POST['password'], $_POST['newpassword'], $_POST['email'], 1);
						if($ucresult == -1) {
							showmsg("系统提示:UCenter", "旧密码不正确！", $links);
						} elseif($ucresult == -4) {
							showmsg("系统提示:UCenter", "Email 格式有误！", $links);
						} elseif($ucresult == -5) {
							showmsg("系统提示:UCenter", "Email 不允许注册！", $links);
						} elseif($ucresult == -6) {
							showmsg("系统提示:UCenter", "该 Email 已经被注册！", $links);
						}
					}
				}
				updatetable(table('huiyuan'), $info, $wheresql);
			}
			//新密码发送邮箱
			if (get_mail_yes('mail_set_editpwd')){
				$info['password'] = $_POST['newpassword'];
				$info['newpassword'] = $_POST['newpassword'];
				$_mail = get_mail('editpwd',$info,$_CFG);
				smtp_mail($info['email'],$_mail['smtp_title'],$_mail['smtp_body']);
			}
			showmsg("系统提醒", "修改成功！", $links);
		}
		$smarty->assign('huiyuan', $huiyuan_val);
		break;
	case 'zhifumima':
		if($_POST['dosubmit']) {
			$links[0]['title'] = '重新修改';
			$links[0]['href'] = get_link();
			$links[1]['title'] = '返回会员中心';
			$links[1]['href'] = get_link("vs|sid",'','1').'&amp;m=huiyuan&amp;c=index';
			if ($huiyuan_val['passwordpay']){
				if (!$_POST['newpasswordpay']) showmsg("系统提醒", "请输入“新支付密码”！", $links);
				if (!$_POST['renewpasswordpay']) showmsg("系统提醒", "请输入“重复新支付密码”！", $links);
				if ($_POST['newpasswordpay'] != $_POST['renewpasswordpay']) showmsg("系统提醒", "请相同输入“新支付密码”与“重复新支付密码”！", $links);
				foreach ($_POST as $_k=>$_v) {
					if (substr($_k,0,5)=='daan_'){
						if (!$db -> getone("select * from ".table('anquan_daan')." WHERE solution='{$_v}' AND questionid='".substr($_k,5)."' AND userid={$huiyuan_val['userid']} LIMIT 1")){
							showmsg("系统提醒", "密保答案输入错误！", $links);
						}
					}
				}
			}else{
				if (!$_POST['newpasswordpay']) showmsg("系统提醒", "请输入“支付密码”！", $links);
				if (!$_POST['renewpasswordpay']) showmsg("系统提醒", "请输入“重复支付密码”！", $links);
				if ($_POST['newpasswordpay'] != $_POST['renewpasswordpay']) showmsg("系统提醒", "请相同输入“支付密码”与“重复支付密码”！", $links);
				if ($_POST['wenti1'] == $_POST['wenti2'] || $_POST['wenti2'] == $_POST['wenti3'] || $_POST['wenti3'] == $_POST['wenti1']){
					showmsg("系统提醒", "不能选任意相同的密保问题！", $links);
				}
				if (!$_POST['daan1'] || !$_POST['daan2'] || !$_POST['daan2']) showmsg("系统提醒", "请输入“密保答案”！", $links);
				$wentiinfo = array();
				$wentiinfo['userid'] = $huiyuan_val['userid'];
				$wentiinfo['time'] = SYS_TIME;
				$wentiinfo['questionid'] = $_POST['wenti1'];
				$wentiinfo['solution'] = $_POST['daan1'];
				inserttable(table('anquan_daan'), $wentiinfo);
				$wentiinfo['questionid'] = $_POST['wenti2'];
				$wentiinfo['solution'] = $_POST['daan2'];
				inserttable(table('anquan_daan'), $wentiinfo);
				$wentiinfo['questionid'] = $_POST['wenti3'];
				$wentiinfo['solution'] = $_POST['daan3'];
				inserttable(table('anquan_daan'), $wentiinfo);
			}
			$info = array();
			$info['encrypt'] = generate_password(6);
			$info['passwordpay'] = create_password($_POST['newpasswordpay'], $info['encrypt']);
			$wheresql = "userid = ".$huiyuan_val['userid'];
			if ($info) updatetable(table('huiyuan'), $info, $wheresql);
			showmsg("系统提醒", "保存成功！", $links);
		}
		//
		$row = $db -> getall("select * from ".table('anquan_wenti')." ORDER BY `pid` DESC");
		$wenti = array(); foreach ($row as $_v){ $wenti[$_v['questionid']] = $_v;}

		if ($huiyuan_val['passwordpay']){
			$n = 1; $daan = $db -> getall("select * from ".table('anquan_daan')." WHERE userid={$huiyuan_val['userid']} ORDER BY rand() LIMIT 0, 2");
			foreach ($daan as $_v){
				$_v['n'] = $n;
				$_v['question'] = $wenti[$_v['questionid']]['question'];
				$n ++ ;
				$wentiarr[$_v['questionid']] = $_v;
			}
		}else{
			$wentiarr = array('请选择密保问题'=>'0');
			foreach ($row as $_v){
				$wentiarr[$_v['question']] = $_v['questionid'];
			}
		}
			
		$smarty->assign('huiyuan', $huiyuan_val);
		$smarty->assign('wentiarr', $wentiarr);
		break;
	case 'shengji':
		$grouplist = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_zu.php');
		if(empty($grouplist[$huiyuan_val['groupid']]['allowupgrade'])) {
			showmsg('系统提醒','您所在的会员组禁止自主升级！');
		}
		$huiyuan_val['groupname'] = $grouplist[$huiyuan_val['groupid']]['name'];
		$huiyuan_val['grouppoint'] = $grouplist[$huiyuan_val['groupid']]['point'];
			
		unset($grouplist[$huiyuan_val['groupid']]);
		$group_list = array();
		foreach ($grouplist as $_k => $_v){
			if ($_v['point'] > $huiyuan_val['grouppoint']) {
				$_v['starimg'] = IMG_PATH.'icon/xing-'.$_v['starnum'].'.gif';
				$group_list[$_k] = $_v;
			}
		}
		if ($_GET['groupid']){
			$group_id = $group_list[$_GET['groupid']];
			if (empty($group_id['point'])) showmsg('系统提醒','没有可升级的组！');
			//获取上一级积分
			$group_list2 = $db -> getone("select * from ".table('huiyuan_zu')." WHERE point<{$group_id['point']} ORDER BY `point` DESC  LIMIT 1");
			$group_id['point_back'] = $group_list2['point'];
			$smarty->assign('group_id', $group_id);
			if($_POST['dosubmit']) {
				if (empty($group_id)) showmsg('系统提醒','参数错误！');
				if (!intval($_POST['upgradedate'])) showmsg('系统提醒','请输入有效的购买时限！');
				if ($_POST['upgradetype'] == 1){
					$price = $group_id['price_y'];
					$pricetime = 365*86400;//年(365天)
				}elseif ($_POST['upgradetype'] == 2){
					$price = $group_id['price_m'];
					$pricetime = 31*86400;//月(31天)
				}elseif ($_POST['upgradetype'] == 3){
					$price = $group_id['price_d'];
					$pricetime = 1*86400;//1天
				}else{
					showmsg('系统提醒','请选择有效的时限类型！');
				}
				$num = intval($_POST['upgradedate']);
				$paysql = "update ".table('huiyuan')." set vip=1,groupid={$group_id['groupid']},overduedate={ubb}[miao]{/ubb}+{$pricetime} WHERE userid={$huiyuan_val['userid']}";
				kf_class::run_sys_func('dingdan');
				$dingdanid = tianjia_dingdan("自助升级(购买VIP)-{$group_id['name']}", $price, $num, $huiyuan_val['userid'], 0, 1, $paysql, '', '自助升级', 0, 'amount');
				if (!$dingdanid){
					showmsg('系统提醒','参数错误，请重新操作！');
				}else{
					$links[0]['title'] = '现在去付款';
					$links[0]['href'] = get_link("vs|sid",'','1').'&amp;m=dingdan&amp;c=xiangqing&amp;id='.$dingdanid;
					$links[1]['title'] = '返回继续购买';
					$links[1]['href'] = get_link();
					$links[2]['title'] = '返回会员中心';
					$links[2]['href'] = get_link("vs|sid",'','1').'&amp;m=huiyuan&amp;c=index';
					showmsg('系统提醒','已经成功下好订单！', $links);
				}
			}
		}
		$smarty->assign('group_list', $group_list);
		$smarty->assign('huiyuan', $huiyuan_val);
		break;
	case 'qianming':
		$grouplist = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_zu.php');
		$smarty->assign('group_list', $grouplist);
		$smarty->assign('groupid', $huiyuan_val['groupid']);
		break;
	case 'tuichu':
		if ($_GET['dosubmit']){
			//ucenter 同步退出
			$uctext = "";
			kf_class::ucenter();
			if (UC_USE == '1'){
				setcookie('Example_auth', '', -86400);
				$ucsynlogout = uc_user_synlogout();
				if ($_GET['vs'] == '1'){
					preg_match_all('/<script.+?src=\"(.+?)\".+?>/i', $ucsynlogout, $match);
					foreach ($match[1] as $_val) {
						$_val = str_replace('&', '&amp;', $_val);
						$uctext.= '<img src="'.$_val.'" alt=""/>';
					}
				}else{
					$uctext.= $ucsynlogout; //同步登录
				}
			}
			//
			$_SESSION['sid'] = '';
			$wheresql = "userid = ".$huiyuan_val['userid'];
			$db -> query("update ".table('huiyuan')." set `usersid` = '".generate_password(24)."' WHERE {$wheresql}");
			$links[0]['title'] = '重新登录';
			$links[0]['href'] = get_link("vs|m","",1)."&amp;c=denglu";
			$links[1]['title'] = '返回网站首页';
			$links[1]['href'] = get_link("vs","",1);
			showmsg("系统提醒", "已经安全退出。".$uctext, $links);
		}
		$links[0]['title'] = '返回会员中心';
		$links[0]['href'] = get_link("vs|sid",'','1').'&amp;m=huiyuan&amp;c=index';
		$_time = '<br/> <a href="'.get_link().'&amp;dosubmit=1">确定退出</a>';
		showmsg("系统提醒", "确定安全退出并且重置会员标识使原保存的书签失效吗？".$_time, $links);
		break;
}

?>