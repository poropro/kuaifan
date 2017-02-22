<?php
/*
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');
if ($_GET['a'] != 'fabu') require(KF_INC_PATH.'denglu.php');

switch ($_GET['a']){
	case 'fabu': //发布
		$templatefile.= '/fabu';
		if (intval($_POST['catid'])) $_GET['catid'] = $_POST['catid'];
		kf_class::run_sys_class('tree','',0);
		$tree= new tree();
		$tree->icon = array('　│ ','　├─ ','　└─ ');
		$tree->nbsp = '　';
		$categorys = array();
		$__n = 0;
		//
		$yzmpeizhi = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_peizhi_mokuai'.DIRECTORY_SEPARATOR.'cache.yanzhengma.php');
		$smarty->assign('yzmpeizhi', $yzmpeizhi);
		//游客
		if (empty($huiyuan_val['groupid'])) { $huiyuan_val['groupid'] = 8; }
		//栏目
		$lanmudata = getcache(KF_ROOT_PATH.'caches/column/cache.'.$_CFG['site'].'.php');
		$row = $db->getall("select catid from ".table('neirong_lanmu_quanxian')." WHERE `action`='add' AND `is`=1 AND `site` = {$_CFG['site']} AND `roleid`={$huiyuan_val['groupid']}");
		$quanxianarr = array(); foreach($row as $r) {$quanxianarr[] = $r['catid'];}
		foreach($lanmudata as $r) {
			$option = ($_GET['catid'] == $r['id'])?' selected=\'selected\'':'';
			if (!empty($r['arrchildid'])){
				$r['option'] = "<option value='0'{$option}>x";
			}else{
				if (!in_array($r['id'], $quanxianarr)){
					continue;
				}
				if (!intval($_GET['catid'])){
					$_GET['catid'] = $r['id'];
					$option = ' selected=\'selected\'';
				}
				$r['option'] = "<option value='{$r['id']}'{$option}>√";
				$__n++;
			}
			$categorys[$r['id']] = $r;
		}
		if (empty($__n)){
			showmsg("提示信息", "网站尚未开通在线投稿！");
		}
		$str  = "\$option\$spacer\$title</option><br/>";
		$tree->init($categorys);
		$_categorys = $tree->get_tree(0, $str);
		$smarty->assign('categorys', $_categorys);
		//字段
		kf_class::run_sys_func('form');
		$data = getcache(KF_ROOT_PATH.'caches/model/model_field_'.$lanmudata[$_GET['catid']]['modelid'].'.cache.php');
		$ziduandata = array();
		foreach($data as $r) {
			if ($r['status'] != '1' && $r['isadd'] == '1' && $r['hide'] == '0'){
				if ($r['type'] != 'catid' && $r['type'] != 'typeid'){
					$ziduandata[$r['id']] = $r;
				}
			}
		}
		$smarty->assign('ziduandata', $ziduandata);
		//
		$catarr = $lanmudata[$_GET['catid']];
		$catarr['setting'] = string2array($catarr['setting']);
		$smarty->assign('catarr', $catarr);
		//审核
		$grouplist = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_zu.php');
		$allowpostverify = $grouplist[$huiyuan_val['groupid']]['allowpostverify'];
		$row = $db->getone("select `is` from ".table('neirong_lanmu_quanxian')." WHERE `action`='shen' AND `catid`={$_GET['catid']} AND `site` = {$_CFG['site']} AND `roleid`={$huiyuan_val['groupid']}");
		if ($row['is'] == 2) $allowpostverify = 0;
		if ($row['is'] == 1) $allowpostverify = 1;
		$smarty->assign('allowpostverify', $allowpostverify);
		//模型
		$moxingdata = getcache(KF_ROOT_PATH.'caches/cache_neirong_moxing.php');
		$moxingdata = $moxingdata[$lanmudata[$_GET['catid']]['modelid']];
		if ($moxingdata['type'] == 'bbs'){
			$_url = get_link('type');
			if ($_GET['type'] == 'yin'){
				$fabu_head = '<a href="'.$_url.'">文本</a>/隐藏/<a href="'.$_url.'&amp;type=pai">派币</a>/<a href="'.$_url.'&amp;type=xuan">悬赏</a>/<a href="'.$_url.'&amp;type=mai">收费</a>';
			}elseif ($_GET['type'] == 'pai'){
				$fabu_head = '<a href="'.$_url.'">文本</a>/<a href="'.$_url.'&amp;type=yin">隐藏</a>/派币/<a href="'.$_url.'&amp;type=xuan">悬赏</a>/<a href="'.$_url.'&amp;type=mai">收费</a>';
			}elseif ($_GET['type'] == 'xuan'){
				$fabu_head = '<a href="'.$_url.'">文本</a>/<a href="'.$_url.'&amp;type=yin">隐藏</a>/<a href="'.$_url.'&amp;type=pai">派币</a>/悬赏/<a href="'.$_url.'&amp;type=mai">收费</a>';
			}elseif ($_GET['type'] == 'mai'){
				$fabu_head = '<a href="'.$_url.'">文本</a>/<a href="'.$_url.'&amp;type=yin">隐藏</a>/<a href="'.$_url.'&amp;type=pai">派币</a>/<a href="'.$_url.'&amp;type=xuan">悬赏</a>/收费';
			}else{
				$fabu_head = '文本/<a href="'.$_url.'&amp;type=yin">隐藏</a>/<a href="'.$_url.'&amp;type=pai">派币</a>/<a href="'.$_url.'&amp;type=xuan">悬赏</a>/<a href="'.$_url.'&amp;type=mai">收费</a>';
			}
			$smarty->assign('fabu_head', '<br/>发表贴:<b>'.$fabu_head.'</b><br/><a href="'.get_link('m|catid|vs|sid','',1).'&amp;c=list">返回论坛列表</a><br/>');
			$smarty->assign('mtype', $moxingdata['type']);
		}else{
			unset($_GET['type']);
		}
		
		//发布投稿
		if ($_POST['dosubmit'] && !$_POST['nextsubmit']) {
			//判断会员组是否允许投稿
			if(!$grouplist[$huiyuan_val['groupid']]['allowpost']) {
				showmsg("提示信息", "您所属的会员组禁止投稿！");
			}
			//判断每日投稿数
			if($grouplist[$huiyuan_val['groupid']]['allowpostnum'] > 0) {
				$todaytime = strtotime(date('y-m-d',SYS_TIME));
				$allowpostnum = $db->get_total("SELECT COUNT(*) AS num FROM ".table('neirong_fabu')." WHERE `inputtime` > {$todaytime} AND `username`='{$huiyuan_val['username']}'");
				if ($allowpostnum >= $grouplist[$huiyuan_val['groupid']]['allowpostnum']){
					showmsg("提示信息", "今日投稿已超过限制数！");
				}
			}
			$__post = array();
			$__post_data = array();
			$__post['catid'] = intval($_GET['catid']);
			$__post['username'] = $huiyuan_val['username'];
			$__post['inputtime'] = SYS_TIME;
			$__post['replytime'] = $__post['inputtime'];
			$__post['site'] = $_CFG['site'];
			$__post['sysadd'] = 0;
			if (!$db->getone("select catid from ".table('neirong_lanmu_quanxian')." WHERE `action`='add' AND `is`=1 AND `catid`={$__post['catid']} AND `site` = {$_CFG['site']} AND `roleid`={$huiyuan_val['groupid']}")){
				showmsg("提示信息", "你没有这个权限！");
			}
			//限时投稿间隔
			$xianshi_tg = intval($catarr['setting']['xianshi_tg']);
			if ($xianshi_tg > 0){
				$lastrow = $db->getone("select * from ".table("diy_".$catarr['module'])." WHERE `catid`={$_GET['catid']} AND `username`='{$huiyuan_val['username']}' AND `inputtime`>".intval(SYS_TIME - $xianshi_tg));
				if (!empty($lastrow)){
					showmsg("提示信息", "限制投稿间隔时间为{$xianshi_tg}秒！");
				}
			}
			//注册多久限制投稿
			$xianshi_zctg = intval($catarr['setting']['xianshi_zctg']);
			if ($xianshi_zctg > 0){
				if ($huiyuan_val['regdate'] > SYS_TIME - intval($xianshi_zctg * 60)){
					showmsg("提示信息", "限制注册时间未达到{$xianshi_zcsj}分钟的会员投稿！");
				}
			}
			//验证码
			if (!$_POST['yanzhengma'] && $yzmpeizhi['fabu']) showmsg("系统提示", "请输入“验证码”！", $links);
			if ($yzmpeizhi['fabu']) {
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
			$contentaddition = "";
			foreach($_POST as $_k=>$_v) {
				if (substr($_k, 0, 8) == "bbsinfo_"){
					switch (substr($_k, 12)) {
						case 'yin': //隐藏贴
							if (empty($_v)) showmsg("系统提醒", "请输入要隐藏的内容。");
							$contentaddition.= "[bbs=yin]{$_v}[/bbs]";
							$__post['subtitle'] = to_subtitle("隐", $__post['subtitle']);
							break;
						case 'pai': //派发贴
							if ($_v < $_POST[substr($_k, 0, 12)."hui"]){
								showmsg("系统提醒", "回复奖励不能大于总派发数。");
							}
							if ($_POST[substr($_k, 0, 12)."huobi"] == "amount"){
								if ($_v < 0.01){
									showmsg("系统提醒", "总派发必须大于0.01。");
								}
								if ($_POST[substr($_k, 0, 12)."hui"] < 0.01){
									showmsg("系统提醒", "回复奖励必须大于0.01。");
								}
								if ($huiyuan_val['amount'] < $_v) showmsg("系统提醒", "你的{$_CFG['amountname']}不足。");
							}elseif ($_POST[substr($_k, 0, 12)."huobi"] == "point"){
								$_v = intval($_v);
								$_POST[substr($_k, 0, 12)."hui"] = intval($_POST[substr($_k, 0, 12)."hui"]);
								if ($_v <= 0){
									showmsg("系统提醒", "积分总派发必须大于0。");
								}
								if ($_POST[substr($_k, 0, 12)."hui"] <= 0){
									showmsg("系统提醒", "积分回复奖励必须大于0。");
								}
								if ($huiyuan_val['point'] < $_v) showmsg("系统提醒", "你的积分不足。");
							}else{
								showmsg("系统提醒", "请选择正确派发类型。");
							}
							$contentaddition.= "[bbs=pai]".$_POST[substr($_k, 0, 12)."huobi"]."|".$_v."|".$_POST[substr($_k, 0, 12)."hui"]."[/bbs]";
							$__post['subtitle'] = to_subtitle("派", $__post['subtitle']);
							break;
						case 'xuan': //悬赏贴
							if (intval($_POST[substr($_k, 0, 12)."tian"]) < 1){
								showmsg("系统提醒", "悬赏天数不得小于1。");
							}
							if ($_POST[substr($_k, 0, 12)."huobi"] == "amount"){
								if ($_v < 0.01){
									showmsg("系统提醒", "悬赏{$_CFG['amountname']}必须大于0.01。");
								}
								if ($huiyuan_val['amount'] < $_v) showmsg("系统提醒", "你的{$_CFG['amountname']}不足。");
							}elseif ($_POST[substr($_k, 0, 12)."huobi"] == "point"){
								$_v = intval($_v);
								if ($_v <= 0){
									showmsg("系统提醒", "悬赏积分必须大于0。");
								}
								if ($huiyuan_val['point'] < $_v) showmsg("系统提醒", "你的积分不足。");
							}else{
								showmsg("系统提醒", "请选择正确悬赏类型。");
							}
							$contentaddition.= "[bbs=xuan]".$_POST[substr($_k, 0, 12)."huobi"]."|".$_v."|".intval($_POST[substr($_k, 0, 12)."tian"])."[/bbs]";
							$__post['subtitle'] = to_subtitle("赏", $__post['subtitle']);
							break;
						case 'mai': //收费贴
							$maitxt = $_POST[substr($_k, 0, 12)."maitxt"];
							$maitxt = str_replace('|', '', $maitxt);
							if (empty($maitxt)) showmsg("系统提醒", "请输入要收费的内容。");
							if ($_POST[substr($_k, 0, 12)."huobi"] == "amount"){
								if ($_v < 0.01){
									showmsg("系统提醒", "收费{$_CFG['amountname']}必须大于0.01。");
								}
							}elseif ($_POST[substr($_k, 0, 12)."huobi"] == "point"){
								$_v = intval($_v);
								if ($_v <= 0){
									showmsg("系统提醒", "收费积分必须大于0。");
								}
							}else{
								showmsg("系统提醒", "请选择正确收费类型。");
							}
							$contentaddition.= "[bbs=mai]".$_POST[substr($_k, 0, 12)."huobi"]."|".$_v."|".$maitxt."[/bbs]";
							$__post['subtitle'] = to_subtitle("卖", $__post['subtitle']);
							break;
					}
					continue;
				}
				$_poarr = $data[$_k];
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
				//收费字段
				if($_k == 'paytype'){
					$__post_data['paytype'] = $_v;
				}
				//字段主副
				if ($_poarr['issystem'] == '1') $__post[$_k] = $_v;
				if ($_poarr['issystem'] == '0') {
					//内容字段判断替换br
					if ($_poarrset['newline2br']){
						$__post_data[$_k] = $_v;
					}else{
						$__post_data[$_k] = nl2br($_v);
					}
				}
			}
			$__post['subtitle'] = to_subtitle($__post_data['content'], $__post['subtitle'], 2);
			//上传部分
			kf_class::run_sys_func('upload');
			$_urlA = $_urlB = $_urlC = array();
			foreach($_FILES as $_k=>$_v) {
				shangchuanquanxian($huiyuan_val['groupid'], $_k); //检测上传权限
				$_poarr = $data[$_k];
				//字段是否存在
				if (empty($_poarr)){
					if (strrchr($_k, '_')) {
						$_kk = substr($_k, 0, strlen(strrchr($_k,'_'))*-1);
						$_poarr = $data[$_kk];
					}
					if (empty($_poarr)){
						showmsg("系统提醒", "参数错误！");
					}
				}
				$_poarrset = string2array($_poarr['setting']);
				//最小长度
				if (intval($_poarr['minlength']) > 0){
					!$_FILES[$_k]['name']?showmsg("系统提醒", "请选择要上传的“{$_poarr['name']}”"):"";
				}
				//跳过
				if (!$_FILES[$_k]['name'] && intval($_poarr['minlength']) == 0){
					continue;
				}
				//跳过数量
				if ($_urlC[$_poarr['field']] > intval($_poarrset['upload_number']) && $_poarrset['upload_number']) {
					continue; 
				}
				//上传格式
				$_file_allowext = $_poarrset['upload_allowext'];
				if ($_poarr['type'] == 'image') $_file_allowext = "gif|jpg|jpeg|png|bmp";
				if ($_poarr['type'] == 'downfile' && empty($_file_allowext)) $_file_allowext = "rar|zip|jar|apk|7z";
				if (!$_file_allowext) showmsg("系统提醒", "“{$_poarr['name']}”未设置允许上传的文件类型，请先设置！");
				$_file_allowext = str_replace('|', '/', $_file_allowext);
				$_file_allowext = str_replace(',', '/', $_file_allowext);
				//上传大小
				$_file_size = intval(ini_get('upload_max_filesize')*1024);
				//目录构造
				$up_dir_0 = "uploadfiles/content/default/";
				make_dir('./'.$up_dir_0.date("Y/m/d/"));
				if ($_k == 'thumb') { //*缩列图
					$up_dir_100 = "uploadfiles/content/100/";
					$up_dir_48 = "uploadfiles/content/48/";
					make_dir('./'.$up_dir_100.date("Y/m/d/"));
					make_dir('./'.$up_dir_48.date("Y/m/d/"));
				}
				//开始上传
				$_file[$_k] = _asUpFiles('./'.$up_dir_0.date("Y/m/d/"), $_k, $_file_size, $_file_allowext, true);
				if ($_file[$_k]){
					if ($_k == 'thumb') { //*缩列图
						makethumb('./'.$up_dir_0.date("Y/m/d/").$_file[$_k], './'.$up_dir_100.date("Y/m/d/"), 100, 100);
						makethumb('./'.$up_dir_0.date("Y/m/d/").$_file[$_k], './'.$up_dir_48.date("Y/m/d/"), 48, 48);
						$_timepost[$_k]['0'] = 		$_CFG['site_domain'].$_CFG['site_dir'].$up_dir_0.date("Y/m/d/").$_file[$_k];
						$_timepost[$_k]['100'] = 	$_CFG['site_domain'].$_CFG['site_dir'].$up_dir_100.date("Y/m/d/").$_file[$_k];
						$_timepost[$_k]['48'] = 	$_CFG['site_domain'].$_CFG['site_dir'].$up_dir_48.date("Y/m/d/").$_file[$_k];
						if ($_poarr['issystem'] == '1') $__post[$_k] = array2string($_timepost[$_k]);
						if ($_poarr['issystem'] == '0') $__post_data[$_k] = array2string($_timepost[$_k]);
						//处理附件表
						$_urlA = array(
									'title' => $__post['title'],
									'name' => $_FILES[$_k]['name'],
									'url' => './'.$up_dir_0.date("Y/m/d/").$_file[$_k],
									'allurl' => $_timepost[$_k]['0'],
									'field' => $_k,
						);
						$__post['subtitle'] = to_subtitle("图", $__post['subtitle']);
					}else{
						$_timepost[$_k] = $_CFG['site_domain'].$_CFG['site_dir'].$up_dir_0.date("Y/m/d/").$_file[$_k];
						//处理附件表
						$_urlB[] = array(
									'title' => $__post['title'],
									'name' => $_FILES[$_k]['name'],
									'body' => $_POST[$_k],
									'url' => './'.$up_dir_0.date("Y/m/d/").$_file[$_k],
									'allurl' => $_timepost[$_k],
									'field' => $_poarr['field'],
						);
						//第一张作为缩略图
						if (!empty($_poarrset['uponethumb']) && empty($__post['thumb'])){
							$get_ext = strtolower(get_extension($_timepost[$_k]));
							if (in_array($get_ext, array('jpg','jpeg','png','gif','bmp'))){
								$up_dir_100 = "uploadfiles/content/100/";
								$up_dir_48 = "uploadfiles/content/48/";
								make_dir('./'.$up_dir_100.date("Y/m/d/"));
								make_dir('./'.$up_dir_48.date("Y/m/d/"));
								makethumb('./'.$up_dir_0.date("Y/m/d/").$_file[$_k], './'.$up_dir_100.date("Y/m/d/"), 100, 100);
								makethumb('./'.$up_dir_0.date("Y/m/d/").$_file[$_k], './'.$up_dir_48.date("Y/m/d/"), 48, 48);
								$_timethumb = array();
								$_timethumb['0'] = 		$_timepost[$_k];
								$_timethumb['100'] = 	$_CFG['site_domain'].$_CFG['site_dir'].$up_dir_100.date("Y/m/d/").$_file[$_k];
								$_timethumb['48'] = 	$_CFG['site_domain'].$_CFG['site_dir'].$up_dir_48.date("Y/m/d/").$_file[$_k];
								$__post['thumb'] = array2string($_timethumb);
							}
						}
						//
						$__post['subtitle'] = to_subtitle($_file[$_k], $__post['subtitle'], 1);
						$_urlC[$_poarr['field']]++;
					}
				}else{
					showmsg("系统提醒", "“{$_poarr['name']}”上传失败！");
				}
			}
			//判断会员组投稿是否需要审核
			$allowpostverify = $grouplist[$huiyuan_val['groupid']]['allowpostverify'];
			$row = $db->getone("select `is` from ".table('neirong_lanmu_quanxian')." WHERE `action`='shen' AND `catid`={$__post['catid']} AND `site` = {$_CFG['site']} AND `roleid`={$huiyuan_val['groupid']}");
			if ($row['is'] == 2) $allowpostverify = 0;
			if ($row['is'] == 1) $allowpostverify = 1;
			if ($_POST['caogao'] == '1'){
				$__post['status'] = 98;
			}elseif(!$allowpostverify) {
				$__post['status'] = 99;
			} else {
				$__post['status'] = 1;
			}
			$lanmudb = $lanmudata[$__post['catid']];
			$__post['description'] = $__post['description']?$__post['description']:substr(strip_tags($__post_data['content']),0,255);
			$trueid = inserttable(table("diy_".$lanmudb['module']),$__post, true);
			if (empty($trueid)){
				showmsg("添加失败", "投稿失败，网络繁忙请稍后再试！");
			}
			//更新到附件数据表
			if ($_urlA) _db_fujian($lanmudb['module']."_".$lanmudb['modelid']."_".$__post['catid']."_".$trueid, $lanmudb['modelid'], $_urlA, 1);
			if ($_urlB) {
				foreach($_urlB as $_k=>$_v) {
					if ($_v) _db_fujian($lanmudb['module']."_".$lanmudb['modelid']."_".$__post['catid']."_".$trueid, $lanmudb['modelid'], $_v, 1);
				}
				//更新字段
				$_wheresqlB = "commentid='".$lanmudb['module']."_".$lanmudb['modelid']."_".$__post['catid']."_".$trueid."'";
				$__val = array();
				$arr = $db->getall("SELECT * FROM ".table('neirong_fujian')." WHERE {$_wheresqlB} ORDER BY id asc");
				foreach($arr as $_value){
					$__val[$_value['field']][$_value['id']]= $_value['allurl'];
				}
				foreach($__val as $_k2=>$_v2) {
					$_poarr2 = $data[$_k2];
					if ($_poarr2['issystem']=='1'){
						$db -> query("update ".table("diy_".$lanmudb['module'])." set `{$_k2}`='".array2string($_v2)."' WHERE id=".$trueid."");
					}else{
						$__post_data[$_k2] = array2string($_v2);
						//$db -> query("update ".table("diy_".$lanmudb['module']."_data")." set `{$_k2}`='".array2string($_v2)."' WHERE id=".$trueid."");
					}
				}
			}
            /*
            //通过采集上传附件(开源自用)
			foreach($_POST as $_k=>$_v) {
                kf_class::run_sys_func('pinyin');
                if (substr($_k, 0, 6) == '__img_'){
                    $pattern = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.jpeg|\.png]))[\'|\"].*?[\/]?>/";
                    preg_match_all($pattern, stripslashes($_v), $_vlists);
                    $_vlist = $_vlists[1];
                    if (!empty($_vlist)){
                        kf_class::run_sys_func('upload');
                        $up_dir_0 = "uploadfiles/content/default/";
                        make_dir('./'.$up_dir_0.date("Y/m/d/"));
                        $__val = array();
                        foreach ($_vlist as $_vk => $_vval) {
                            if (substr($_vval, 0, 4) == "http"){
                                $varname = reset(explode('?', basename($_vval)));
                                $varname2 = cn2pinyin($varname,'',1);
                                $vararr = getImage(urlencode_ch($_vval), $up_dir_0.date("Y/m/d/"), $varname2);
                                if ($vararr['error'] == '0'){
                                    preg_match('/<[img|IMG].*?width=[\'|\"](.*?)[\'|\"].*?[\/]?>/i', $_vlists[0][$_vk], $_matches); 
                                    $_width = intval($_matches[1]);
                                    preg_match('/<[img|IMG].*?height=[\'|\"](.*?)[\'|\"].*?[\/]?>/i', $_vlists[0][$_vk], $_matches); 
                                    $_height = intval($_matches[1]);
                                    if ($_width > 0 || $_height > 0){
                                        makethumb($up_dir_0.date("Y/m/d/").$varname2, $up_dir_0.date("Y/m/d/"), $_width, $_height, 0, $varname2);
                                    }
                                    $_urlA = array(
                                      'title' => $__post['title'],
                                      'name' => $varname,
                                      'body' => '',
                                      'url' => './'.$up_dir_0.date("Y/m/d/").$varname2,
                                      'allurl' => $_CFG['site_domain'].$_CFG['site_dir'].$up_dir_0.date("Y/m/d/").$varname2,
                                      'field' => substr($_k, 6),
                                    );
                                    $_fid = _db_fujian($lanmudb['module']."_".$lanmudb['modelid']."_".$__post['catid']."_".$trueid, $lanmudb['modelid'], $_urlA, 1);
                                    $__val[$_fid]= $_urlA['allurl'];
                                }
                            }
                        }
                        if (!empty($__val)){
                            if ($__post['subtitle'] != to_subtitle("图", $__post['subtitle'])){
                                $db -> query("update ".table("diy_".$lanmudb['module'])." set `subtitle`='".to_subtitle("图", $__post['subtitle'])."' WHERE id=".$trueid."");
                            }
                            $_poarr = $data[substr($_k, 6)];
                            if ($_poarr['issystem']=='1'){										
                                $db -> query("update ".table("diy_".$lanmudb['module'])." set `".substr($_k, 6)."`='".array2string($__val)."' WHERE id=".$trueid."");
                            }elseif (!empty($_poarr)){
                                $__post_data[substr($_k, 6)] = array2string($__val);
                            }
                        }
                    }
                }
                if (substr($_k, 0, 6) == '__fii_'){
                    $_vlist = explode('||||', $_v);
                    kf_class::run_sys_func('upload');
                    $up_dir_0 = "uploadfiles/content/default/";
                    make_dir('./'.$up_dir_0.date("Y/m/d/"));
                    $__val = array();
                    foreach ($_vlist as $_vk => $_vval) {
                        $_vvalist = explode('////', $_vval);
                        $_vval = $_vvalist[1];
                        if (substr($_vval, 0, 4) == "http"){
                            $varname = reset(explode('?', basename($_vval))); 
                            $varname2 = cn2pinyin(urldecode($varname),'',1);
                            if (empty($varname2)) $varname2 = time().'_'.generate_password(8,21).strrchr($varname,'.');
                            $varname = $varname?urldecode($varname):$varname2;
                            $vararr = getImage($_vval, $up_dir_0.date("Y/m/d/"), $varname2);
                            if ($vararr['error'] == '0'){
                                $_urlA = array(
                                  'title' => $__post['title'],
                                  'name' => $varname,
                                  'body' => $_vvalist[2],
                                  'url' => './'.$up_dir_0.date("Y/m/d/").$varname2,
                                  'allurl' => $_CFG['site_domain'].$_CFG['site_dir'].$up_dir_0.date("Y/m/d/").$varname2,
                                  'field' => substr($_k, 6),
                                );
                                $_fid = _db_fujian($lanmudb['module']."_".$lanmudb['modelid']."_".$__post['catid']."_".$trueid, $lanmudb['modelid'], $_urlA, 1);
                                $__val[$_fid]= $_urlA['allurl'];
                            }
                        }
                    }
                    if (!empty($__val)){
                        if ($__post['subtitle'] != to_subtitle("附", $__post['subtitle'])){
                            $db -> query("update ".table("diy_".$lanmudb['module'])." set `subtitle`='".to_subtitle("附", $__post['subtitle'])."' WHERE id=".$trueid."");
                        }
                        $_poarr = $data[substr($_k, 6)];
                        if ($_poarr['issystem']=='1'){										
                            $db -> query("update ".table("diy_".$lanmudb['module'])." set `".substr($_k, 6)."`='".array2string($__val)."' WHERE id=".$trueid."");
                        }elseif (!empty($_poarr)){
                            $__post_data[substr($_k, 6)] = array2string($__val);
                        }
                    }
                }
            }			
			*/
			kf_class::run_sys_func('admin');
			//统计栏目
			$total_sql="SELECT COUNT(*) AS num FROM ".table("diy_".$lanmudb['module'])." WHERE catid = ".intval($_GET['catid'])."";
			$total_count=$db->get_total($total_sql);
			$db -> query("update ".table('neirong_lanmu')." set items='".$total_count."' WHERE id=".intval($_GET['catid'])."");
			refresh_cache_column($lanmudb['site']);
			//统计模型
			$total_sql="SELECT COUNT(*) AS num FROM ".table("diy_".$lanmudb['module'])."";
			$total_count=$db->get_total($total_sql);
			$db -> query("update ".table('neirong_moxing')." set num='".$total_count."' WHERE id=".$lanmudb['modelid']."");
			cache_field($lanmudb['modelid']);
			//发布到审核列表中
			$check_data = array(
					'checkid'=>'c-'.$trueid.'-'.$lanmudb['modelid'],
					'catid'=>$__post['catid'],
					'site'=>$_CFG['site'],
					'title'=>$__post['title'],
					'username'=>$__post['username'],
					'inputtime'=>$__post['inputtime'],
					'status'=>$__post['status'],
			);
			$checkid = inserttable(table("neirong_fabu"),$check_data,true);
			//定义返回
			$links[0]['title'] = '继续投稿';
			$links[0]['href'] = get_link("dosubmit");
			$links[-1]['title'] = '返回列表';
			$links[-1]['href'] = get_link("catid|a|type").'&amp;a=guanli';
			$links[1]['title'] = '返回会员中心';
			$links[1]['href'] = get_link("vs|sid",'','1').'&amp;m=huiyuan&amp;c=index';
			$wheresql = "modelid={$lanmudb['modelid']} AND status!=1 AND hide=0 AND isadd=1 AND type in ('downfile','images')";
			$sql = "SELECT * FROM ".table('neirong_moxing_ziduan')." WHERE {$wheresql} ORDER BY listorder asc,id asc";
			$arr = $db->getall($sql); $_i = 2;
			foreach($arr as $_value) {
				$links[$_i]['title'] = '进入:'.$_value['name'];
				$links[$_i]['href'] = get_link("sid|vs|m",'',1)."&amp;c=guanli&amp;a=xiugai&amp;checkid={$check_data['checkid']}&amp;edit={$trueid}&amp;upfile=".$_value['field'];
				$_i++;
			}
			//全站搜索
			$fulltext = $__post['title'];
			$fulltext.= $__post['keywords']?$__post['keywords']:strip_tags($__post_data['content']);
			install_search($trueid, $lanmudb['id'], $lanmudb['modelid'], $__post['title'], $fulltext, $__post['title'], $__post['description'], $__post['inputtime'], $_CFG['site'], $__post['status']);
			$links[$_i+2]['title'] = '返回网站首页';
			$links[$_i+2]['href'] = kf_url('index');
			$__post_data['id'] = $trueid;
			$__post_data['site'] = $_CFG['site'];
			$__post_data['content'].= $contentaddition?$contentaddition:"";
			!empty($__post_data)? inserttable(table("diy_".$lanmudb['module']."_data"),$__post_data):"";
			if ($__post['status'] == 98){
				showmsg("系统提醒", "稿件保存到草稿箱成功！", $links);
			}elseif ($__post['status'] == 99){
				showmsg("系统提醒", "投稿成功，等待管理员审核中！", $links);
			}else{
				$_urlarr = array(
					'm'=>'neirong',
					'c'=>'show',
					'catid'=>$__post['catid'],
					'id'=>$trueid,
					'sid'=>$_GET['sid'],
					'vs'=>$_GET['vs'],
				);
				$_nrurl = url_rewrite('KF_neirongshow', $_urlarr);
				//投稿通过审核积分奖罚
				set_shenhe("diy_".$lanmudb['module'], 1, $trueid);
				showmsg("系统提醒", "投稿成功！<a href='{$_nrurl}'>(查看内容)</a>", $links);
			}
		}
		break;
	case 'xiugai': //修改
		$_fabu = $db->getone("select * from ".table('neirong_fabu')." WHERE `checkid`='{$_GET['checkid']}' AND `site` = {$_CFG['site']}");
		if (empty($_fabu)) showmsg("提示信息", "你要修改的内容不存在！");
		$lanmudb = $db->getone("select * from ".table('neirong_lanmu')." WHERE `id`='{$_fabu['catid']}' AND `site` = {$_CFG['site']}");
		$lanmudbsetting = string2array($lanmudb['setting']);
		$bbsbz = explode('|', $lanmudbsetting['bbs_banzhu']); 
		if ($_fabu['username'] != $huiyuan_val['username']){
			if (in_array($huiyuan_val['userid'], $bbsbz)){
				$is_gly = 1;
			}else{
				showmsg("提示信息", "你没有这个权限！");
			}
		}
		if ($_fabu['status'] == '1' && empty($lanmudbsetting['shenhehou'])) showmsg("提示信息", "你要修改的内容已通过审核无法修改！");
		$arr_checkid = explode('-',$_fabu['checkid']);
		$_fabu['id'] = $arr_checkid[1];
		$_fabu['modelid'] = $arr_checkid[2];
		//1、单独处理字段
		if ($_GET['upfile']){
			kf_class::run_sys_func('admin');
			if ($_GET['upfile'] == 'thumb'){
				if ($_GET['dosubmit']){
					$data = getcache(KF_ROOT_PATH.'caches/model/model_field_'.$lanmudb['modelid'].'.cache.php');
					if ($data[$_GET['upfile']]['issystem']=='1'){
						$db -> query("update ".table("diy_".$lanmudb['module'])." set `{$_GET['upfile']}`='' WHERE id=".$_fabu['id']."");
					}else{
						$db -> query("update ".table("diy_".$lanmudb['module']."_data")." set `{$_GET['upfile']}`='' WHERE id=".$_fabu['id']."");
					}
					$_wheresql = "commentid='".$lanmudb['module']."_".$lanmudb['modelid']."_".$_fabu['catid']."_".$_fabu['id']."' AND field='{$_GET['upfile']}'";
					$sql = "SELECT * FROM ".table('neirong_fujian')." WHERE {$_wheresql} ORDER BY id asc";
					$arr = $db->getall($sql);
					foreach($arr as $_value) { fujian_del($_value['url']); }
					$db->query("Delete from ".table("neirong_fujian")." WHERE {$_wheresql}");
					re_subtitle($_fabu['id'], $lanmudb['module']);
					if ($is_gly == 1){
						$dataarr = array();
						$dataarr['type'] = 'bzrz-scslt';
						$dataarr['userid'] = $huiyuan_val['userid'];
						$dataarr['dataid'] = $_fabu['id'];
						$dataarr['dataid2'] = $_fabu['catid'];
						$dataarr['intime'] = SYS_TIME;
						$dataarr['setting']['title'] = $_fabu['title'];
						$dataarr['setting']['username'] = $huiyuan_val['username'];
						$dataarr['setting']['txt'] = "删除缩略图";
						$dataarr['setting'] = array2string($dataarr['setting']);
						inserttable(table("neirong_data"), $dataarr);
					}
					
					$links[0]['title'] = '返回修改页面';
					$links[0]['href'] = get_link("upfile|edit|dosubmit");
					$links[1]['title'] = '返回列表页面';
					$links[1]['href'] = get_link("upfile|edit|dosubmit|checkid|a");
					showmsg("系统提醒", "删除成功。", $links);
				}else{
					$links[0]['title'] = '确定删除';
					$links[0]['href'] = get_link()."&amp;dosubmit=1";
					$links[1]['title'] = '返回修改页面';
					$links[1]['href'] = get_link("upfile|edit");
					$links[2]['title'] = '返回列表页面';
					$links[2]['href'] = get_link("upfile|edit|checkid|a");
					showmsg("系统提醒", "确定删除此文件吗？", $links);
				}
			}else{
				$templatefile.= '/fujian';
				$data = getcache(KF_ROOT_PATH.'caches/model/model_field_'.$lanmudb['modelid'].'.cache.php');
				$wheresql = "id=".intval($_fabu['id'])." AND `site` = {$_CFG['site']}";
				$neirongdb1 = $db -> getone("select * from ".table("diy_".$lanmudb['module'])." WHERE {$wheresql} LIMIT 1");
				if (!$neirongdb1) { showmsg("系统提醒", "要修改的内容ID".intval($_fabu['id'])."不存在！"); }
				$_wheresql = "commentid='".$lanmudb['module']."_".$lanmudb['modelid']."_".$_fabu['catid']."_".$_fabu['id']."' AND field='{$_GET['upfile']}'";
					
				//删除单个文件
				if ($_GET['fd']){
					if ($_GET['dosubmit']){
						$arr = $db->getone("SELECT * FROM ".table('neirong_fujian')." WHERE id=".intval($_GET['fd'])." AND {$_wheresql}");
						fujian_del($arr['url']);
						$db->query("Delete from ".table("neirong_fujian")." WHERE id=".intval($_GET['fd'])." AND {$_wheresql}");
						//更新字段
						$data = getcache(KF_ROOT_PATH.'caches/model/model_field_'.$lanmudb['modelid'].'.cache.php');
						$_poarr = $data[$_GET['upfile']];
						$__val = array();
						$arr = $db->getall("SELECT * FROM ".table('neirong_fujian')." WHERE {$_wheresql} ORDER BY id asc");
						foreach($arr as $_value){
							$__val[$_value['id']]= $_value['allurl'];
						}
						if ($_poarr['issystem']=='1'){
							$db -> query("update ".table("diy_".$lanmudb['module'])." set `{$_GET['upfile']}`='".array2string($__val)."' WHERE id=".$_fabu['id']."");
						}else{
							$db -> query("update ".table("diy_".$lanmudb['module']."_data")." set `{$_GET['upfile']}`='".array2string($__val)."' WHERE id=".$_fabu['id']."");
						}
						re_subtitle($_fabu['id'], $lanmudb['module']);
						if ($is_gly == 1){
							$dataarr = array();
							$dataarr['type'] = 'bzrz-scfj';
							$dataarr['userid'] = $huiyuan_val['userid'];
							$dataarr['dataid'] = $_fabu['id'];
							$dataarr['dataid2'] = $_fabu['catid'];
							$dataarr['intime'] = SYS_TIME;
							$dataarr['setting']['title'] = $_fabu['title'];
							$dataarr['setting']['username'] = $huiyuan_val['username'];
							$dataarr['setting']['txt'] = "删除附件".$arr['name'];
							$dataarr['setting'] = array2string($dataarr['setting']);
							inserttable(table("neirong_data"), $dataarr);
						}
						
						$links[0]['title'] = '返回文件列表';
						$links[0]['href'] = get_link("fd|dosubmit")."";
						$links[1]['title'] = '返回修改页面';
						$links[1]['href'] = get_link("fd|edit|upfile|upi|upisubmit")."";
						showmsg("系统提醒", "删除成功。", $links);
					}else{
						$links[0]['title'] = '确定删除';
						$links[0]['href'] = get_link()."&amp;dosubmit=1";
						$links[1]['title'] = '返回文件列表';
						$links[1]['href'] = get_link("fd")."";
						showmsg("系统提醒", "确定删除此文件吗？", $links);
					}
				}
				//编辑单个文件
				if ($_GET['fe']){
					$_valtime = '';
					if ($_POST['dosubmit']){
						$db -> query("update ".table('neirong_fujian')." set `body`='".$_POST['body']."',`name`='".$_POST['name']."' WHERE id=".intval($_GET['fe'])."");
						$_valtime.= "<b>修改成功！</b><br/>";
					}
					$arr = $db->getone("SELECT * FROM ".table('neirong_fujian')." WHERE id=".intval($_GET['fe'])." AND {$_wheresql}");
						
					$_valtime.= "文件地址:<br/>".$arr['allurl']."<br/>";
					$_valtime.= format_form(array('set'=>'头','notvs'=>'1'));
					$_valtime.= "文件名称:<br/>".format_form(array("set" => "输入框|名称:'name".fenmiao()."'", "data_value" => $arr['name']))."<br/>";
					if ($_GET['vs']==1){
						$_valtime.= "文件说明:<br/>".format_form(array("set" => "输入框|名称:'body".fenmiao()."'", "data_value" => $arr['body']))."<br/>";
						$_valtime.= '
								<anchor title="提交">提交修改
								<go href="'.get_link().'" method="post" accept-charset="utf-8">
								<postfield name="body" value="$(body'.fenmiao().')"/>
								<postfield name="name" value="$(name'.fenmiao().')"/>
								<postfield name="dosubmit" value="1"/>
								</go></anchor>									
							';
					}else{
						$_valtime.= "文件说明:<br/>".format_form(array("set" => "文本框|名称:'body".fenmiao()."'", "data_value" => $arr['body']))."<br/>";
					}
					$_valtime.= format_form(array('set'=>'按钮|名称:dosubmit,值:提交修改','notvs'=>'1'));
					$_valtime.= format_form(array('set'=>'尾','notvs'=>'1'));
					$links[0]['title'] = '返回列表';
					$links[0]['href'] = get_link("fe");
					$links[1]['title'] = '返回内容修改';
					$links[1]['href'] = get_link("fe|edit|upfile|upi|upisubmit");
					showmsg("修改文件说明", $_valtime, $links);
				}
					
				//上传文件	
				if ($_POST['dosubmit'] && !$_POST['nextsubmit']) {
					//模型(判断是否论坛模型进行更新副标题)
					$moxingdata = getcache(KF_ROOT_PATH.'caches/cache_neirong_moxing.php');
					$moxingdata = $moxingdata[$lanmudb['modelid']];
					if ($moxingdata['type'] == "bbs"){
						$__post['subtitle'] = $neirongdb1['subtitle'];
					}
					//开始上传处理
					$total_sql="SELECT COUNT(*) AS num FROM ".table("neirong_fujian")." WHERE {$_wheresql}";
					$total_count=$db->get_total($total_sql);
					kf_class::run_sys_func('upload');
					$_poarr = $data[$_GET['upfile']];
					$_poarrset = string2array($_poarr['setting']);
					$_fi = 0;
					foreach($_FILES as $_k=>$_v) {
						if ($_fabu['username'] == $huiyuan_val['username']) shangchuanquanxian($huiyuan_val['groupid'], $_k); //检测上传权限
						if (strpos($_k, $_GET['upfile']) === false) continue; //跳过不是正常表单的上传
						if (!$_FILES[$_k]['name']) continue; //跳过为空的上传
						if ($total_count >= intval($_poarrset['upload_number']) && $_poarrset['upload_number']) showmsg("系统提醒", "“{$_poarr['name']}”最多允许上传{$_poarrset['upload_number']}个文件，现已经上传达到{$total_count}个！");
						if ($total_count+$_fi >= intval($_poarrset['upload_number']) && $_poarrset['upload_number']) continue; //跳过数量
						//上传格式
						$_file_allowext = $_poarrset['upload_allowext'];
						if ($_poarr['type'] == 'image') $_file_allowext = "gif|jpg|jpeg|png|bmp";
						if ($_poarr['type'] == 'downfile' && empty($_file_allowext)) $_file_allowext = "rar|zip|jar|apk|7z";
						if (!$_file_allowext) showmsg("系统提醒", "“{$_poarr['name']}”未设置允许上传的文件类型，请先设置！");
						$_file_allowext = str_replace('|', '/', $_file_allowext);
						$_file_allowext = str_replace(',', '/', $_file_allowext);
						//上传大小
						$_file_size = intval(ini_get('upload_max_filesize')*1024);
						//目录构造
						$up_dir_0 = "uploadfiles/content/default/";
						make_dir('./'.$up_dir_0.date("Y/m/d/"));
						//开始上传
						$_file[$_k] = _asUpFiles('./'.$up_dir_0.date("Y/m/d/"), $_k, $_file_size, $_file_allowext, true);
						if ($_file[$_k]){
							$_timepost[$_k] = $_CFG['site_domain'].$_CFG['site_dir'].$up_dir_0.date("Y/m/d/").$_file[$_k];
							//处理附件表
							$_urlA = array(
									'title' => $neirongdb1['title'],
									'name' => $_FILES[$_k]['name'],
									'body' => $_POST[$_k],
									'url' => './'.$up_dir_0.date("Y/m/d/").$_file[$_k],
									'allurl' => $_timepost[$_k],
									'field' => $_GET['upfile'],
							);
							//第一张作为缩略图
							if (!empty($_poarrset['uponethumb']) && empty($neirongdb1['thumb'])){
								$get_ext = strtolower(get_extension($_timepost[$_k]));
								if (in_array($get_ext, array('jpg','jpeg','png','gif','bmp'))){
									$up_dir_100 = "uploadfiles/content/100/";
									$up_dir_48 = "uploadfiles/content/48/";
									make_dir('./'.$up_dir_100.date("Y/m/d/"));
									make_dir('./'.$up_dir_48.date("Y/m/d/"));
									makethumb('./'.$up_dir_0.date("Y/m/d/").$_file[$_k], './'.$up_dir_100.date("Y/m/d/"), 100, 100);
									makethumb('./'.$up_dir_0.date("Y/m/d/").$_file[$_k], './'.$up_dir_48.date("Y/m/d/"), 48, 48);
									$_timethumb = array();
									$_timethumb['0'] = 		$_timepost[$_k];
									$_timethumb['100'] = 	$_CFG['site_domain'].$_CFG['site_dir'].$up_dir_100.date("Y/m/d/").$_file[$_k];
									$_timethumb['48'] = 	$_CFG['site_domain'].$_CFG['site_dir'].$up_dir_48.date("Y/m/d/").$_file[$_k];
									$neirongdb1['thumb'] = array2string($_timethumb);
									updatetable(table("diy_".$lanmudb['module']), array("thumb" => $neirongdb1['thumb']), "`id`='{$_fabu['id']}'");
								}
							}
							//
							_db_fujian($lanmudb['module']."_".$lanmudb['modelid']."_".$neirongdb1['catid']."_".$neirongdb1['id'], $lanmudb['modelid'], $_urlA, 1);
							if ($is_gly == 1){
								$dataarr = array();
								$dataarr['type'] = 'bzrz-tjfj';
								$dataarr['userid'] = $huiyuan_val['userid'];
								$dataarr['dataid'] = $neirongdb1['id'];
								$dataarr['dataid2'] = $neirongdb1['catid'];
								$dataarr['intime'] = SYS_TIME;
								$dataarr['setting']['title'] = $neirongdb1['title'];
								$dataarr['setting']['username'] = $huiyuan_val['username'];
								$dataarr['setting']['txt'] = "上传附件".$_urlA['name'];
								$dataarr['setting'] = array2string($dataarr['setting']);
								inserttable(table("neirong_data"), $dataarr);
							}
							$__post['subtitle'] = to_subtitle($_file[$_k], $__post['subtitle'], 1);
							$_fi++;
						}else{
							showmsg("系统提醒", "“{$_poarr['name']}”上传失败！");
						}
					}
					//论坛模式更新副标题
					if ($moxingdata['type'] == "bbs"){
						updatetable(table("diy_".$lanmudb['module']), array("subtitle" => $__post['subtitle']), "`id`='{$_fabu['id']}'");
					}
					//更新字段
					$__val = array();
					$arr = $db->getall("SELECT * FROM ".table('neirong_fujian')." WHERE {$_wheresql} ORDER BY id asc");
					foreach($arr as $_value){
						$__val[$_value['id']]= $_value['allurl'];
					}
					if ($_poarr['issystem']=='1'){
						$db -> query("update ".table("diy_".$lanmudb['module'])." set `{$_GET['upfile']}`='".array2string($__val)."' WHERE id=".$_fabu['id']."");
					}else{
						$db -> query("update ".table("diy_".$lanmudb['module']."_data")." set `{$_GET['upfile']}`='".array2string($__val)."' WHERE id=".$_fabu['id']."");
					}
					
					$links[0]['title'] = '继续上传';
					$links[0]['href'] = get_link("dosubmit")."";
					$links[1]['title'] = '返回修改页面';
					$links[1]['href'] = get_link("edit|upfile|upi|dosubmit|upisubmit");
					if ($_fi == 0) showmsg("系统提醒", "没有选择任何要上传的文件！");
					showmsg("系统提醒", "上传完成！", $links);
				}
					
				$_fujian = array();
				$_wheresql = "commentid='".$lanmudb['module']."_".$lanmudb['modelid']."_".$_fabu['catid']."_".$_fabu['id']."' AND field='{$_GET['upfile']}'";
				$sql = "SELECT * FROM ".table('neirong_fujian')." WHERE {$_wheresql} ORDER BY id asc";
				$arr = $db->getall($sql);
				foreach($arr as $_value){
					$_fujian[$_value['id']] = $_value;
				}
				$_POST['upi'] = (intval($_POST['upi'])>0)?intval($_POST['upi']):'1';
				$_POST['upi'] = (intval($_POST['upi'])>50)?'50':intval($_POST['upi']);
				$__input = "";
				for ($i=1; $i<=$_POST['upi']; $i++) {
					$__input.= '文件'.$i.':<input type="file" name="'.$_GET['upfile'].'_'.$i.'" size="10" /><br/>'.chr(13);
					$__input.= '说明'.$i.':<textarea name="'.$_GET['upfile'].'_'.$i.'"></textarea> <br/>'.chr(13);
				}
				
				kf_class::run_sys_func('upload');
				$upconfig = getcache(KF_ROOT_PATH. "caches/caches_peizhi_mokuai/cache.fujian.php");
				$max_size = intval(ini_get('upload_max_filesize')*1024);
				if ($upconfig['upload_maxsize'] > 0 && $upconfig['upload_maxsize'] < $max_size) $max_size = $upconfig['upload_maxsize'];

				$smarty->assign('__input', $__input);
				$smarty->assign('fudata', $data[$_GET['upfile']]);
				$smarty->assign('fudatasetting', string2array($data[$_GET['upfile']]['setting']));
				$smarty->assign('fudatasettingone', _formatSize($max_size*1024)); 
				$smarty->assign('fujian', $_fujian);
					
			}
			break;
		}
		//2、修改内容
		$templatefile.= '/xiugai';
		$_fabu['lanmu'] = $lanmudb;
		$_moxing = getcache(KF_ROOT_PATH.'caches/cache_neirong_moxing.php');
		$_moxing_arr = $_moxing[$_fabu['modelid']];
		$wheresql = "`id`='{$_fabu['id']}'";
		$neirongdb1 = $db->getone("select * from ".table("diy_".$_moxing_arr['tablename'])." WHERE {$wheresql} AND `catid`='{$_fabu['catid']}' AND `site` = {$_CFG['site']}");
		if (empty($neirongdb1)) showmsg("提示信息", "你要修改的内容不存在！");
		$neirongdb2 = $db->getone("select * from ".table("diy_".$_moxing_arr['tablename']."_data")." WHERE {$wheresql} AND `site` = {$_CFG['site']}");
		$neirongdb = array_merge($neirongdb1, $neirongdb2);
		//$neirongdb = array_map('htmlspecialchars_decode',$neirongdb);
		$_fabu['status_y'] = $_fabu['status'];
		$_fabu['status'] = ($_fabu['status'] == 98)?'1':'0';
		//字段
		kf_class::run_sys_func('form');
		$data = getcache(KF_ROOT_PATH.'caches/model/model_field_'.$lanmudb['modelid'].'.cache.php');
		$ziduandata = array();
		$ziduandata2 = array();
		foreach($data as $r) {
			//处理收费字段
			if($r['field'] == 'readpoint'){
				$r['_paytype'] = $neirongdb['paytype'];
			}
			if ($r['status'] != '1' && $r['isadd'] == '1' && $r['hide'] == '0'){
				if ($r['type'] != 'catid' && $r['type'] != 'typeid'){
					$r['__defaultvalue'] = $neirongdb[$r['field']];
					$ziduandata[$r['id']] = $r;
				}
			}elseif($r['field'] == 'updatetime'){
				$ziduandata2[$r['id']] = $r;
			}
		}
		$smarty->assign('fabu', $_fabu);
		$smarty->assign('ziduandata', array_merge($ziduandata));
		//
		$catarr = $lanmudb;
		$catarr['setting'] = string2array($catarr['setting']);
		$smarty->assign('catarr', $catarr);

		//审核
		$grouplist = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_zu.php');
		$allowpostverify = $grouplist[$huiyuan_val['groupid']]['allowpostverify'];
		$row = $db->getone("select `is` from ".table('neirong_lanmu_quanxian')." WHERE `action`='shen' AND `catid`={$_fabu['catid']} AND `site` = {$_CFG['site']} AND `roleid`={$huiyuan_val['groupid']}");
		if ($row['is'] == 2) $allowpostverify = 0;
		if ($row['is'] == 1) $allowpostverify = 1;
		$smarty->assign('allowpostverify', $allowpostverify);
			
		//修改投稿
		if ($_POST['dosubmit']) {
			$lanmudata = getcache(KF_ROOT_PATH.'caches/column/cache.'.$_CFG['site'].'.php');
			$lanmudb = $lanmudata[$_fabu['catid']];

			$__post = array();
			$__post_data = array();
			//$__post['catid'] = intval($_GET['catid']);
			//$__post['username'] = $huiyuan_val['username'];
			//$__post['inputtime'] = SYS_TIME;
			//$__post['site'] = $_CFG['site'];
			//$__post['sysadd'] = 0;
			$__post['updatetime'] = SYS_TIME;
			$__post['subtitle'] = $neirongdb['subtitle'];
			$contentaddition = "";
			foreach($_POST as $_k=>$_v) {
				if (substr($_k, 0, 8) == "bbsinfo_"){
					switch (substr($_k, 12)) {
						case 'yin': //隐藏贴
							if (empty($_v)) showmsg("系统提醒", "请输入要隐藏的内容。");
							$contentaddition.= "[bbs=yin]{$_v}[/bbs]";
							$__post['subtitle'] = to_subtitle("隐", $__post['subtitle']);
							break;
						case 'pai': //派发贴
							/*
							if ($_v < $_POST[substr($_k, 0, 12)."hui"]){
								showmsg("系统提醒", "回复奖励不能大于总派发数。");
							}
							if ($_POST[substr($_k, 0, 12)."huobi"] == "amount"){
								if ($_v < 0.01){
									showmsg("系统提醒", "总派发必须大于0.01。");
								}
								if ($_POST[substr($_k, 0, 12)."hui"] < 0.01){
									showmsg("系统提醒", "回复奖励必须大于0.01。");
								}
								if ($huiyuan_val['amount'] < $_v) showmsg("系统提醒", "你的{$_CFG['amountname']}不足。");
							}elseif ($_POST[substr($_k, 0, 12)."huobi"] == "point"){
								$_v = intval($_v);
								$_POST[substr($_k, 0, 12)."hui"] = intval($_POST[substr($_k, 0, 12)."hui"]);
								if ($_v <= 0){
									showmsg("系统提醒", "积分总派发必须大于0。");
								}
								if ($_POST[substr($_k, 0, 12)."hui"] <= 0){
									showmsg("系统提醒", "积分回复奖励必须大于0。");
								}
								if ($huiyuan_val['point'] < $_v) showmsg("系统提醒", "你的积分不足。");
							}else{
								showmsg("系统提醒", "请选择正确派发类型。");
							}
							*/
							$_paiarr =  explode("|", to_content($neirongdb['content'],"pai"));
							$contentaddition.= "[bbs=pai]".$_paiarr[0]."|".$_paiarr[1]."|".$_paiarr[2]."[/bbs]";
							$__post['subtitle'] = to_subtitle("派", $__post['subtitle']);
							break;
						case 'xuan': //悬赏贴
							/*
							if (intval($_POST[substr($_k, 0, 12)."tian"]) < 1){
								showmsg("系统提醒", "悬赏天数不得小于1。");
							}
							if ($_POST[substr($_k, 0, 12)."huobi"] == "amount"){
								if ($_v < 0.01){
									showmsg("系统提醒", "悬赏{$_CFG['amountname']}必须大于0.01。");
								}
								if ($huiyuan_val['amount'] < $_v) showmsg("系统提醒", "你的{$_CFG['amountname']}不足。");
							}elseif ($_POST[substr($_k, 0, 12)."huobi"] == "point"){
								$_v = intval($_v);
								if ($_v <= 0){
									showmsg("系统提醒", "悬赏积分必须大于0。");
								}
								if ($huiyuan_val['point'] < $_v) showmsg("系统提醒", "你的积分不足。");
							}else{
								showmsg("系统提醒", "请选择正确悬赏类型。");
							}
							*/
							$_xuanarr =  explode("|", to_content($neirongdb['content'],"xuan"));
							$contentaddition.= "[bbs=xuan]".$_xuanarr[0]."|".$_xuanarr[1]."|".$_xuanarr[2]."[/bbs]";
							$__post['subtitle'] = to_subtitle("赏", $__post['subtitle']);
							break;
						case 'mai': //收费贴
							$maitxt = $_POST[substr($_k, 0, 12)."maitxt"];
							$maitxt = str_replace('|', '', $maitxt);
							if (empty($maitxt)) showmsg("系统提醒", "请输入要收费的内容。");
							if ($_POST[substr($_k, 0, 12)."huobi"] == "amount"){
								if ($_v < 0.01){
									showmsg("系统提醒", "收费{$_CFG['amountname']}必须大于0.01。");
								}
							}elseif ($_POST[substr($_k, 0, 12)."huobi"] == "point"){
								$_v = intval($_v);
								if ($_v <= 0){
									showmsg("系统提醒", "收费积分必须大于0。");
								}
							}else{
								showmsg("系统提醒", "请选择正确收费类型。");
							}
							$contentaddition.= "[bbs=mai]".$_POST[substr($_k, 0, 12)."huobi"]."|".$_v."|".$maitxt."[/bbs]";
							$__post['subtitle'] = to_subtitle("卖", $__post['subtitle']);
							break;
					}
					continue;
				}
				$_poarr = $data[$_k];
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
				//收费字段
				if($_k == 'paytype'){
					$__post_data['paytype'] = $_v;
				}
				//字段主副
				if ($_poarr['issystem'] == '1') $__post[$_k] = $_v;
				if ($_poarr['issystem'] == '0') {
					//内容字段判断替换br
					if ($_poarrset['newline2br']){
						$__post_data[$_k] = $_v;
					}else{
						$__post_data[$_k] = nl2br($_v);
					}
				}
			}
			$__post['subtitle'] = to_subtitle($__post_data['content'], $__post['subtitle'], 2);
			kf_class::run_sys_func('upload');
			foreach($_FILES as $_k=>$_v) {
				if ($_fabu['username'] == $huiyuan_val['username']) shangchuanquanxian($huiyuan_val['groupid'], $_k); //检测上传权限
				$_poarr = $data[$_k];
				$_poarrset = string2array($_poarr['setting']);
				//最小长度
				if (intval($_poarr['minlength']) > 0){
					!$_FILES[$_k]['name']?showmsg("系统提醒", "请选择要上传的“{$_poarr['name']}”"):"";
				}
				//跳过
				if (!$_FILES[$_k]['name'] && intval($_poarr['minlength']) == 0){
					continue;
				}
				//上传格式
				$_file_allowext = $_poarrset['upload_allowext'];
				if ($_poarr['type'] == 'image') $_file_allowext = "gif|jpg|jpeg|png|bmp";
				if ($_poarr['type'] == 'downfile' && empty($_file_allowext)) $_file_allowext = "rar|zip|jar|apk|7z";
				if (!$_file_allowext) showmsg("系统提醒", "“{$_poarr['name']}”未设置允许上传的文件类型，请先设置！");
				$_file_allowext = str_replace('|', '/', $_file_allowext);
				$_file_allowext = str_replace(',', '/', $_file_allowext);
				//上传大小
				$_file_size = intval(ini_get('upload_max_filesize')*1024);
				//目录构造
				$up_dir_0 = "uploadfiles/content/default/";
				$up_dir_100 = "uploadfiles/content/100/";
				$up_dir_48 = "uploadfiles/content/48/";
				make_dir('./'.$up_dir_0.date("Y/m/d/"));
				make_dir('./'.$up_dir_100.date("Y/m/d/"));
				make_dir('./'.$up_dir_48.date("Y/m/d/"));
				//开始上传
				$_file[$_k] = _asUpFiles('./'.$up_dir_0.date("Y/m/d/"), $_k, $_file_size, $_file_allowext, true);
				if ($_file[$_k]){
					makethumb('./'.$up_dir_0.date("Y/m/d/").$_file[$_k], './'.$up_dir_100.date("Y/m/d/"), 100, 100);
					makethumb('./'.$up_dir_0.date("Y/m/d/").$_file[$_k], './'.$up_dir_48.date("Y/m/d/"), 48, 48);
					$_timepost[$_k]['0'] = 		$_CFG['site_domain'].$_CFG['site_dir'].$up_dir_0.date("Y/m/d/").$_file[$_k];
					$_timepost[$_k]['100'] = 	$_CFG['site_domain'].$_CFG['site_dir'].$up_dir_100.date("Y/m/d/").$_file[$_k];
					$_timepost[$_k]['48'] = 	$_CFG['site_domain'].$_CFG['site_dir'].$up_dir_48.date("Y/m/d/").$_file[$_k];
					if ($_poarr['issystem'] == '1') $__post[$_k] = array2string($_timepost[$_k]);
					if ($_poarr['issystem'] == '0') $__post_data[$_k] = array2string($_timepost[$_k]);
					//处理附件表
					$_urlA = array(
								'title' => $__post['title'],
								'name' => $_FILES[$_k]['name'],
								'url' => './'.$up_dir_0.date("Y/m/d/").$_file[$_k],
								'allurl' => $_timepost[$_k]['0'],
								'field' => $_k,
					);
					$__post['subtitle'] = to_subtitle("图", $__post['subtitle']);
					_db_fujian($lanmudb['module']."_".$lanmudb['modelid']."_".$neirongdb['catid']."_".$neirongdb['id'], $lanmudb['modelid'], $_urlA, 1);
					if ($is_gly == 1){
						$dataarr = array();
						$dataarr['type'] = 'bzrz-tjslt';
						$dataarr['userid'] = $huiyuan_val['userid'];
						$dataarr['dataid'] = $neirongdb['id'];
						$dataarr['dataid2'] = $neirongdb['catid'];
						$dataarr['intime'] = SYS_TIME;
						$dataarr['setting']['title'] = $neirongdb['title'];
						$dataarr['setting']['username'] = $huiyuan_val['username'];
						$dataarr['setting']['txt'] = "上传缩略图";
						$dataarr['setting'] = array2string($dataarr['setting']);
						inserttable(table("neirong_data"), $dataarr);
					}
				}else{
					showmsg("系统提醒", "“{$_poarr['name']}”上传失败！");
				}
			}
			//判断会员组投稿是否需要审核
			if ($_fabu['username'] == $huiyuan_val['username']){
				$allowpostverify = $grouplist[$huiyuan_val['groupid']]['allowpostverify'];
				$row = $db->getone("select `is` from ".table('neirong_lanmu_quanxian')." WHERE `action`='shen' AND `catid`={$neirongdb['catid']} AND `site` = {$_CFG['site']} AND `roleid`={$huiyuan_val['groupid']}");
				if ($row['is'] == 2) $allowpostverify = 0;
				if ($row['is'] == 1) $allowpostverify = 1;
				if ($_POST['caogao'] == '1'){
					$__post['status'] = 98;
				}elseif(!$allowpostverify) {
					$__post['status'] = 99;
				} else {
					$__post['status'] = 1;
				}
			}else{
				$__post['status'] = $neirongdb['status'];
			}
			$__post['description'] = $__post['description']?$__post['description']:substr(strip_tags($__post_data['content']),0,255);
			if (!updatetable(table("diy_".$lanmudb['module']), $__post, $wheresql)){
				showmsg("修改失败", "修改失败，网络繁忙请稍后再试！");
			}
			//保存到审核列表中
			$check_data = array(
					'title'=>$__post['title'],
					'status'=>$__post['status'],
			);
			updatetable(table("neirong_fabu"), $check_data, "checkid='{$_GET['checkid']}'");
			//定义返回
			$links[0]['title'] = '重新修改';
			$links[0]['href'] = get_link("dosubmit");
			$links[-1]['title'] = '返回列表';
			$links[-1]['href'] = get_link("checkid|a").'&amp;a=guanli';
			$links[1]['title'] = '返回会员中心';
			$links[1]['href'] = get_link("vs|sid",'','1').'&amp;m=huiyuan&amp;c=index';
			$_wheresql = "modelid={$lanmudb['modelid']} AND status!=1 AND hide=0 AND isadd=1 AND type in ('downfile','images')";
			$sql = "SELECT * FROM ".table('neirong_moxing_ziduan')." WHERE {$_wheresql} ORDER BY listorder asc,id asc";
			$arr = $db->getall($sql); $_i = 2;
			foreach($arr as $_value) {
				$links[$_i]['title'] = '进入:'.$_value['name'];
				$links[$_i]['href'] = get_link("edit|upfile")."&amp;edit={$_fabu['id']}&amp;upfile=".$_value['field'];
				$_i++;
			}
			//全站搜索
			$fulltext = $__post['title'];
			$fulltext.= $__post['keywords']?$__post['keywords']:strip_tags($__post_data['content']);
			install_search($_fabu['id'], $lanmudb['id'], $lanmudb['modelid'], $__post['title'], $fulltext, $__post['title'], $__post['description'], $__post['inputtime'], $_CFG['site'], $__post['status']);
			if ($_GET['go_url']){
				$links[] = array(
					'title'=>'返回来源地址',
					'href'=>goto_url($_GET['go_url']).'&amp;sid='.$_GET['sid']
				);
			}
			$links[] = array(
				'title'=>'返回网站首页',
				'href'=>kf_url('index')
			);
			$__post_data['content'].= $contentaddition?$contentaddition:"";
			!empty($__post_data)? updatetable(table("diy_".$lanmudb['module']."_data"), $__post_data, $wheresql):"";
			if ($is_gly == 1){
				$dataarr = array();
				$dataarr['type'] = 'bzrz-xgnr';
				$dataarr['userid'] = $huiyuan_val['userid'];
				$dataarr['dataid'] = $neirongdb['id'];
				$dataarr['dataid2'] = $neirongdb['catid'];
				$dataarr['intime'] = SYS_TIME;
				$dataarr['setting']['title'] = $neirongdb['title'];
				$dataarr['setting']['username'] = $huiyuan_val['username'];
				$dataarr['setting']['txt'] = "修改内容";
				$dataarr['setting'] = array2string($dataarr['setting']);
				inserttable(table("neirong_data"), $dataarr);
			}
			//移动内容 start
			if ($_POST['yidonglanmuid'] > 0 && $_POST['yidonglanmuid'] != $neirongdb['catid']){
				if (in_array($huiyuan_val['userid'], $bbsbz)){
					//
					$newcatid = intval($_POST['yidonglanmuid']);
					$oldfj = $lanmudb['module']."_".$lanmudb['modelid']."_".$neirongdb['catid']."_".$neirongdb['id'];
					$newfj = $lanmudb['module']."_".$lanmudb['modelid']."_".$newcatid."_".$neirongdb['id'];
					$oldpl = "neirong_".$neirongdb['catid']."_".$neirongdb['id']."_".$_CFG['site'];
					$newpl = "neirong_".$newcatid."_".$neirongdb['id']."_".$_CFG['site'];
					//
					$newlanmu = $db->getone("select * from ".table('neirong_lanmu')." WHERE `id`='{$newcatid}'");
					if (empty($newlanmu)) showmsg("系统提醒", "移至的栏目不存在！");
					if ($newlanmu['module'] != $lanmudb['module']) showmsg("系统提醒", "移至的栏目跟现在的栏目不是用一种模型禁止移动！");
					//
					$db -> query("update ".table("diy_".$lanmudb['module'])." set `catid`='".$newcatid."' WHERE `id`=".$neirongdb['id']."");
					$db -> query("update ".table("neirong_fabu")." set `catid`='".$newcatid."' WHERE `checkid`='".$_GET['checkid']."'");
					$db -> query("update ".table("neirong_fujian")." set `commentid`='".$newfj."' WHERE `commentid`='".$oldfj."'");
					$db -> query("update ".table("pinglun")." set `commentid`='".$newpl."' WHERE `commentid`='".$oldpl."'");
					$db -> query("update ".table("pinglun_data_".$_CFG['site'])." set `commentid`='".$newpl."' WHERE `commentid`='".$oldpl."'");
					$db -> query("update ".table("sousuo")." set `catid`=".$newcatid." WHERE `catid`=".$neirongdb['catid']." AND `contentid`=".$neirongdb['id']."");
					$db -> query("update ".table("xinqing")." set `catid`=".$newcatid." WHERE `catid`=".$neirongdb['catid']." AND `contentid`=".$neirongdb['id']."");
					//
					kf_class::run_sys_func('admin');
					//统计栏目(旧)
					$total_sql="SELECT COUNT(*) AS num FROM ".table("diy_".$lanmudb['module'])." WHERE catid = ".$neirongdb['catid']."";
					$total_count=$db->get_total($total_sql);
					$db -> query("update ".table('neirong_lanmu')." set items='".$total_count."' WHERE id=".$neirongdb['catid']."");
					//统计栏目(新)
					$total_sql="SELECT COUNT(*) AS num FROM ".table("diy_".$lanmudb['module'])." WHERE catid = ".$newcatid."";
					$total_count=$db->get_total($total_sql);
					$db -> query("update ".table('neirong_lanmu')." set items='".$total_count."' WHERE id=".$newcatid."");
					refresh_cache_column($lanmudb['site']);
					//
					$dataarr = array();
					$dataarr['type'] = 'bzrz-ydnr';
					$dataarr['userid'] = $huiyuan_val['userid'];
					$dataarr['dataid'] = $neirongdb['id'];
					$dataarr['dataid2'] = $neirongdb['catid'];
					$dataarr['intime'] = SYS_TIME;
					$dataarr['setting']['title'] = $neirongdb['title'];
					$dataarr['setting']['username'] = $huiyuan_val['username'];
					$dataarr['setting']['txt'] = "移动内容(出)";
					$dataarr['setting'] = array2string($dataarr['setting']);
					inserttable(table("neirong_data"), $dataarr);
					//
					$dataarr = array();
					$dataarr['type'] = 'bzrz-ydnr';
					$dataarr['userid'] = $huiyuan_val['userid'];
					$dataarr['dataid'] = $neirongdb['id'];
					$dataarr['dataid2'] = $newcatid;
					$dataarr['intime'] = SYS_TIME;
					$dataarr['setting']['title'] = $neirongdb['title'];
					$dataarr['setting']['username'] = $huiyuan_val['username'];
					$dataarr['setting']['txt'] = "移动内容(进)";
					$dataarr['setting'] = array2string($dataarr['setting']);
					inserttable(table("neirong_data"), $dataarr);
					//
					$neirongdb['catid'] = $newcatid;
				}
			}
			//移动内容 end
			$_urlarr = array(
				'm'=>'neirong',
				'c'=>'show',
				'catid'=>$neirongdb['catid'],
				'id'=>$neirongdb['id'],
				'sid'=>$_GET['sid'],
				'vs'=>$_GET['vs'],
			);
			$_nrurl = url_rewrite('KF_neirongshow', $_urlarr);
			if ($is_gly == 1){
				unset($links[-1]);
				unset($links[1]);
				showmsg("系统提醒", "修改成功！<a href='{$_nrurl}'>(查看内容)</a>", $links);
			}
			if ($__post['status'] == 98){
				showmsg("系统提醒", "成功修改稿件保存到草稿箱成功！", $links);
			}elseif ($__post['status'] == 99){
				showmsg("系统提醒", "修改稿件成功，等待管理员审核中！", $links);
			}else{
				//投稿通过审核积分奖罚
				set_shenhe("diy_".$lanmudb['module'], 1, $_fabu['id']);
				showmsg("系统提醒", "修改稿件成功并且成功投稿！<a href='{$_nrurl}'>(查看内容)</a>", $links);
			}
		}else{
			if (in_array($huiyuan_val['userid'], $bbsbz)){
				//
				kf_class::run_sys_class('tree','',0);
				$tree= new tree();
				$tree->icon = array('　│ ','　├─ ','　└─ ');
				$tree->nbsp = '　';
				$categorys = array();
				$__n = 0;
				//栏目
				$lanmudata = getcache(KF_ROOT_PATH.'caches/column/cache.'.$_CFG['site'].'.php');
				$row = $db->getall("select catid from ".table('neirong_lanmu_quanxian')." WHERE `action`='add' AND `is`=1 AND `site` = {$_CFG['site']} AND `roleid`={$huiyuan_val['groupid']}");
				$quanxianarr = array(); foreach($row as $r) {$quanxianarr[] = $r['catid'];}
				foreach($lanmudata as $r) {
					//$option = ($_fabu['catid'] == $r['id'])?' selected=\'selected\'':'';
					if (!empty($r['arrchildid'])){
						$r['option'] = "<option value='0'{$option}>x";
					}else{
						if (!in_array($r['id'], $quanxianarr) || $r['module'] != $lanmudb['module']){
							continue;
						}
						$r['option'] = "<option value='{$r['id']}'{$option}>√";
						$__n++;
					}
					$categorys[$r['id']] = $r;
				}
				if (empty($__n)){
					showmsg("提示信息", "网站尚未开通在线投稿！");
				}
				$str  = "\$option\$spacer\$title</option><br/>";
				$tree->init($categorys);
				$_categorys = $tree->get_tree(0, $str);
				$smarty->assign('categorys', $_categorys);
			}
		}
		break;
	case 'shanchu': //删除
		$_fabu = $db->getone("select * from ".table('neirong_fabu')." WHERE `checkid`='{$_GET['checkid']}' AND `site` = {$_CFG['site']}");
		if (empty($_fabu)) showmsg("提示信息", "你要修改的内容不存在！");
		$lanmudb = $db->getone("select * from ".table('neirong_lanmu')." WHERE `id`='{$_fabu['catid']}' AND `site` = {$_CFG['site']}");
		$lanmudbsetting = string2array($lanmudb['setting']);
		$bbsbz = explode('|', $lanmudbsetting['bbs_banzhu']); 
		if ($_fabu['username'] != $huiyuan_val['username']){
			if (in_array($huiyuan_val['userid'], $bbsbz)){
				$is_gly = 1;
			}else{
				showmsg("提示信息", "你没有这个权限！");
			}
		}
		if ($_fabu['status'] == '1' && empty($lanmudbsetting['shenheshan'])) showmsg("提示信息", "你要修改的内容已通过审核无法删除！");
		$arr_checkid = explode('-',$_fabu['checkid']);
		$_fabu['id'] = $arr_checkid[1];
		$_fabu['modelid'] = $arr_checkid[2];
		$wheresql = "`id`='{$_fabu['id']}' AND `site` = {$_CFG['site']}";
		$neirongdb1 = $db -> getone("select * from ".table("diy_".$lanmudb['module'])." WHERE {$wheresql} AND `catid`='{$_fabu['catid']}'");
		if (!$neirongdb1) { showmsg("系统提醒", "要删除的内容ID".intval($_fabu['id'])."不存在！"); }
			
		if ($_GET['dosubmit']){
			if ($_GET['go_url']){
				$links[] = array(
					'title'=>'返回论坛列表',
					'href'=>get_link("sid|vs|m",'',1)."&amp;c=list&amp;catid=".$_fabu['catid']
				);
			}else{
				$links[] = array(
					'title'=>'返回稿件列表',
					'href'=>get_link("a|checkid|dosubmit")
				);
			}
			if (!$db->query("Delete from ".table("diy_".$lanmudb['module'])." WHERE {$wheresql}")){
				showmsg("系统提醒", "删除失败,请稍后再试！", $links);
			}else{
				kf_class::run_sys_func('admin');
				//统计栏目
				$total_sql="SELECT COUNT(*) AS num FROM ".table("diy_".$lanmudb['module'])." WHERE catid = ".intval($_fabu['catid'])."";
				$total_count=$db->get_total($total_sql);
				$db -> query("update ".table('neirong_lanmu')." set items='".$total_count."' WHERE id=".intval($_fabu['catid'])."");
				refresh_cache_column($lanmudb['site']);
				//统计模型
				$total_sql="SELECT COUNT(*) AS num FROM ".table("diy_".$lanmudb['module'])."";
				$total_count=$db->get_total($total_sql);
				$db -> query("update ".table('neirong_moxing')." set num='".$total_count."' WHERE id=".$lanmudb['modelid']."");
				cache_field($lanmudb['modelid']);
				//删除附件
				$_wheresql = "commentid='".$lanmudb['module']."_".$lanmudb['modelid']."_".$neirongdb1['catid']."_".$neirongdb1['id']."'";
				$sql = "SELECT * FROM ".table('neirong_fujian')." WHERE {$_wheresql} ORDER BY id asc";
				$arr = $db->getall($sql);
				foreach($arr as $_value) { fujian_del($_value['url']); }
				$db->query("Delete from ".table("neirong_fujian")." WHERE {$_wheresql}");
				//删除评论
				$db->query("Delete from ".table('pinglun')." WHERE {$_wheresql}");
				$db->query("Delete from ".table('pinglun_data_'.$neirongdb1['site'])." WHERE {$_wheresql}");
				//删除数据
				$db->query("Delete from ".table("diy_".$lanmudb['module'])." WHERE {$wheresql}");
				$db->query("Delete from ".table("diy_".$lanmudb['module']."_data")." WHERE {$wheresql}");
				$db->query("Delete from ".table("neirong_fabu")." WHERE checkid='c-{$_fabu['id']}-{$lanmudb['modelid']}' AND username='{$neirongdb1['username']}'");
				$db->query("Delete from ".table("sousuo")." WHERE catid={$neirongdb1['catid']} AND contentid=".intval($_fabu['id'])."");
				showmsg("系统提醒", "删除成功！", $links);
			}
		}
		$links[0]['title'] = '确定删除';
		$links[0]['href'] = get_link()."&amp;dosubmit=1";
		$links[1]['title'] = '返回修改页面';
		$links[1]['href'] = get_link("a")."&amp;a=xiugai";
		if ($_GET['go_url']){
			$links[] = array(
				'title'=>'返回来源地址',
				'href'=>goto_url($_GET['go_url']).'&amp;sid='.$_GET['sid']
			);
		}else{
			$links[] = array(
				'title'=>'返回稿件列表',
				'href'=>get_link("a|checkid|dosubmit")
			);
		}
		showmsg("系统提醒", "确定删除【".$neirongdb1['title']."】吗？", $links);
		break;
	case 'shoucang': //收藏
		$templatefile.= '/shoucang';
		$smarty->assign('userid', $huiyuan_val['userid']);
		
		if ($_GET['del'] == 'all'){
			if ($_GET['dosubmit']){
				$db->query("Delete from ".table('shoucang')." WHERE userid=".$huiyuan_val['userid']);
				$links[0]['title'] = '返回收藏列表';
				$links[0]['href'] = get_link("del|dosubmit");
				$links[1]['title'] = '返回会员中心';
				$links[1]['href'] = get_link("vs|sid",'','1').'&amp;m=huiyuan&amp;c=index';
				showmsg("系统提醒", "删除所有收藏成功！", $links);
			}
			$links[0]['title'] = '确定删除';
			$links[0]['href'] = get_link()."&amp;dosubmit=1";
			$links[1]['title'] = '返回收藏列表';
			$links[1]['href'] = get_link("del");
			showmsg("系统提醒", "确定删除所有收藏并且不可恢复吗？", $links);
		}elseif (!empty($_GET['del'])){
			$db->query("Delete from ".table('shoucang')." WHERE id in ({$_GET['del']}) AND userid=".$huiyuan_val['userid']);
			$links[0]['title'] = '返回收藏列表';
			$links[0]['href'] = get_link("del|dosubmit");
			$links[1]['title'] = '返回会员中心';
			$links[1]['href'] = get_link("vs|sid",'','1').'&amp;m=huiyuan&amp;c=index';
			showmsg("系统提醒", "删除收藏成功！", $links);
		}
		break;
	default : //管理
		$templatefile.= '/index';
		$smarty->assign('username', $huiyuan_val['username']);
		
		if (intval($_POST['catid'])) $_GET['catid'] = $_POST['catid'];
		kf_class::run_sys_class('tree','',0);
		$tree= new tree();
		$tree->icon = array('　│ ','　├─ ','　└─ ');
		$tree->nbsp = '　';
		$categorys = array();
		$__n = 0;
		//栏目
		$lanmudata = getcache(KF_ROOT_PATH.'caches/column/cache.'.$_CFG['site'].'.php');
		$row = $db->getall("select catid from ".table('neirong_lanmu_quanxian')." WHERE `action`='add' AND `is`=1 AND `site` = {$_CFG['site']} AND `roleid`={$huiyuan_val['groupid']}");
		$quanxianarr = array(); foreach($row as $r) {$quanxianarr[] = $r['catid'];}
		foreach($lanmudata as $r) {
			$option = ($_GET['catid'] == $r['id'] && $_GET['vs'] != 1)?' selected=\'selected\'':'';
			if (!empty($r['arrchildid'])){
				$r['option'] = "<option value='0'{$option}>x";
			}else{
				if (!in_array($r['id'], $quanxianarr)){
					continue;
				}
				if (!intval($_GET['catid'])){
					//$_GET['catid'] = $r['id'];
					//$option = ' selected=\'selected\'';
				}
				$r['option'] = "<option value='{$r['id']}'{$option}>√";
				$__n++;
			}
			$categorys[$r['id']] = $r;
		}
		if (!empty($__n)){
			$str  = "\$option\$spacer\$title</option><br/>";
			$tree->init($categorys);
			$_categorys = $tree->get_tree(0, $str);
			$smarty->assign('categorys', $_categorys);
		}

}

/*
//采集函数(开源自用)
function callback2($match){
    return urlencode($match[0]);
}
function urlencode_ch($str){     //直接用这个函数就可以了
	return preg_replace_callback('/[^\0-\127]+/','callback2',$str);
	//正则表达式匹配非单字节字符（含中文）
}
*/
?>