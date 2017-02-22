<?php
/*
 * 评论页
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

//栏目
$columnarr = getcache('caches/column/cache.'.$_CFG['site'].'.php');
$lanmudata = $columnarr[$_GET['catid']];
if (empty($lanmudata)) {
  $links[0]['title'] = '返回网站首页';
  $links[0]['href'] = kf_url('index');
  showmsg("提示信息", "栏目不存在！", $links);
}
$lanmudata['setting'] = string2array($lanmudata['setting']);
if (empty($lanmudata['arrchildid'])) $lanmudata['arrchildid'] = $lanmudata['id'];
//栏目查看权限
switch (category_priv()) {
	case '-1':
		require(KF_INC_PATH.'denglu.php');
		break;
	case '-2':
		showmsg("提示信息", "您没有访问该信息的权限！");
		break;
	case '0':
		showmsg("提示信息", "权限错误！");
		break;
}

//模型
$moxingdata = getcache(KF_ROOT_PATH.'caches/cache_neirong_moxing.php');
$moxingdata = $moxingdata[$lanmudata['modelid']];

//加载内容
$wheresql = "id=".intval($_GET['id']);
$neirongdb1 = $db -> getone("select * from ".table("diy_".$lanmudata['module'])." WHERE status=1 AND {$wheresql} LIMIT 1");
if (empty($neirongdb1)) showmsg("提示信息", "内容不存在！");
//updatetable(table("diy_".$lanmudata['module']), array('read'=>$neirongdb1[read]+1,'readtime'=>SYS_TIME), "id=".$neirongdb1['id']);
$neirongdb2 = $db -> getone("select * from ".table("diy_".$lanmudata['module']."_data")." WHERE {$wheresql} LIMIT 1");
$neirongdb = empty($neirongdb2)?$neirongdb1:array_merge($neirongdb1,$neirongdb2);
//$neirongdb = $neirongdb1;
//$neirongdb = array_map('htmlspecialchars_decode',$neirongdb);

//论坛模型
if ($moxingdata['type'] == "bbs"){
	//$smarty->assign('type', to_content($neirongdb['content'], "type", 1));
	//$neirongdb['content'] = to_content_bbs($neirongdb['content']);
}else{
	showmsg("提示信息", "参数错误：并非论坛帖子！");
}

switch ($_GET['a']) {
	case 'xiugai':
		if ($neirongdb1['username'] != $huiyuan_val['username']){
			showmsg("提示信息", "你没有这个权限！");
		}
		tiaozhuan(get_link('sid|vs|m','',1)."&c=guanli&a=xiugai&checkid=c-".$neirongdb['id']."-".$lanmudata['modelid']."&go_url=".urlencode(get_link('a|sid|vs','&','','',1)));
		break;
	case 'bzxiugai':
		$bbsbz = explode('|', $lanmudata['setting']['bbs_banzhu']); 
		if (!in_array($huiyuan_val['userid'],$bbsbz)){
			showmsg("提示信息", "你没有这个权限！");
		}
		tiaozhuan(get_link('sid|vs|m','',1)."&c=guanli&a=xiugai&checkid=c-".$neirongdb['id']."-".$lanmudata['modelid']."&go_url=".urlencode(get_link('a|sid|vs','&','','',1)));
		break;
	case 'dingzhi':
		$bbsbz = explode('|', $lanmudata['setting']['bbs_banzhu']); 
		if (!in_array($huiyuan_val['userid'],$bbsbz)){
			showmsg("提示信息", "你没有这个权限！");
		}
		if ($neirongdb['dingzhi']){
			$db -> query("update ".table("diy_".$lanmudata['module'])." set dingzhi=0 WHERE {$wheresql}");
			$dataarr = array();
			$dataarr['type'] = 'bzrz-qxdz';
			$dataarr['userid'] = $huiyuan_val['userid'];
			$dataarr['dataid'] = $_GET['id'];
			$dataarr['dataid2'] = $neirongdb['catid'];
			$dataarr['intime'] = SYS_TIME;
			$dataarr['setting']['title'] = $neirongdb['title'];
			$dataarr['setting']['username'] = $huiyuan_val['username'];
			$dataarr['setting']['txt'] = "取消置顶";
			$dataarr['setting'] = array2string($dataarr['setting']);
			inserttable(table("neirong_data"), $dataarr);
			showmsg("提示信息", "取消置顶成功！");
		}else{
			$db -> query("update ".table("diy_".$lanmudata['module'])." set dingzhi=1 WHERE {$wheresql}");
			//顶置奖励
			if ($lanmudata['setting']['bbs_dingzhi'] > 0){
				$row = $db -> getone("select * from ".table("neirong_data")." WHERE `type`='bzrz-dz' AND `dataid`=".intval($_GET['id'])." LIMIT 1");
				if (empty($row)){
					kf_class::run_sys_func('huiyuan');
					set_jiangfa(id_name($neirongdb1['username'],1), $lanmudata['setting']['bbs_dingzhi'], 0, '顶置奖励-'.$neirongdb['title']);
				}
			}
			$dataarr = array();
			$dataarr['type'] = 'bzrz-dz';
			$dataarr['userid'] = $huiyuan_val['userid'];
			$dataarr['dataid'] = $_GET['id'];
			$dataarr['dataid2'] = $neirongdb['catid'];
			$dataarr['intime'] = SYS_TIME;
			$dataarr['setting']['title'] = $neirongdb['title'];
			$dataarr['setting']['username'] = $huiyuan_val['username'];
			$dataarr['setting']['txt'] = "置顶";
			$dataarr['setting'] = array2string($dataarr['setting']);
			inserttable(table("neirong_data"), $dataarr);
			showmsg("提示信息", "置顶成功！");
		}
		break;
	case 'jinghua':
		$bbsbz = explode('|', $lanmudata['setting']['bbs_banzhu']); 
		if (!in_array($huiyuan_val['userid'],$bbsbz)){
			showmsg("提示信息", "你没有这个权限！");
		}
		if ($neirongdb['jinghua']){
			$db -> query("update ".table("diy_".$lanmudata['module'])." set jinghua=0 WHERE {$wheresql}");
			$dataarr = array();
			$dataarr['type'] = 'bzrz-qcjh';
			$dataarr['userid'] = $huiyuan_val['userid'];
			$dataarr['dataid'] = $_GET['id'];
			$dataarr['dataid2'] = $neirongdb['catid'];
			$dataarr['intime'] = SYS_TIME;
			$dataarr['setting']['title'] = $neirongdb['title'];
			$dataarr['setting']['username'] = $huiyuan_val['username'];
			$dataarr['setting']['txt'] = "去除精华";
			$dataarr['setting'] = array2string($dataarr['setting']);
			inserttable(table("neirong_data"), $dataarr);
			showmsg("提示信息", "去除精华成功！");
		}else{
			$db -> query("update ".table("diy_".$lanmudata['module'])." set jinghua=1 WHERE {$wheresql}");
			//精华奖励
			if ($lanmudata['setting']['bbs_jinghua'] > 0){
				$row = $db -> getone("select * from ".table("neirong_data")." WHERE `type`='bzrz-jrjh' AND `dataid`=".intval($_GET['id'])." LIMIT 1");
				if (empty($row)){
					kf_class::run_sys_func('huiyuan');
					set_jiangfa(id_name($neirongdb1['username'],1), $lanmudata['setting']['bbs_jinghua'], 0, '精华奖励-'.$neirongdb['title']);
				}
			}
			$dataarr = array();
			$dataarr['type'] = 'bzrz-jrjh';
			$dataarr['userid'] = $huiyuan_val['userid'];
			$dataarr['dataid'] = $_GET['id'];
			$dataarr['dataid2'] = $neirongdb['catid'];
			$dataarr['intime'] = SYS_TIME;
			$dataarr['setting']['title'] = $neirongdb['title'];
			$dataarr['setting']['username'] = $huiyuan_val['username'];
			$dataarr['setting']['txt'] = "加入精华";
			$dataarr['setting'] = array2string($dataarr['setting']);
			inserttable(table("neirong_data"), $dataarr);
			showmsg("提示信息", "加入精华成功！");
		}
		break;
	case 'shenhe':
		$bbsbz = explode('|', $lanmudata['setting']['bbs_banzhu']); 
		if (!in_array($huiyuan_val['userid'],$bbsbz)){
			showmsg("提示信息", "你没有这个权限！");
		}
		if ($neirongdb['status'] == 99){
			if ($_GET['dosubmit']){
				set_shenhe("diy_".$lanmudata['module'], 1, $neirongdb['id']);
				$links[0]['title'] = '返回管理页面';
				$links[0]['href'] = get_link("a");
				$dataarr = array();
				$dataarr['type'] = 'bzrz-shnr';
				$dataarr['userid'] = $huiyuan_val['userid'];
				$dataarr['dataid'] = $_GET['id'];
				$dataarr['dataid2'] = $neirongdb['catid'];
				$dataarr['intime'] = SYS_TIME;
				$dataarr['setting']['title'] = $neirongdb['title'];
				$dataarr['setting']['username'] = $huiyuan_val['username'];
				$dataarr['setting']['txt'] = "审核";
				$dataarr['setting'] = array2string($dataarr['setting']);
				inserttable(table("neirong_data"), $dataarr);
				showmsg("提示信息", "审核成功！", $links);
			}
			$links[0]['title'] = '确定审核';
			$links[0]['href'] = get_link("dosubmit").'&amp;dosubmit=1';
			$links[1]['title'] = '返回管理页面';
			$links[1]['href'] = get_link("a");
			showmsg("系统提醒", "确定将此内容通过审核？", $links);
		}else{
			showmsg("提示信息", "该内容不再待审核状态！");
		}
		break;
}


//栏目SEO
$_SEO['title'] = $neirongdb['title'].' - '.$lanmudata['title'];
$_SEO['keywords'] = ltrim($neirongdb['keywords'].','.$neirongdb['title'], ',');
$_SEO['description'] = $neirongdb['description'];

$_urlarr = array(
	'm'=>'neirong',
	'c'=>'show',
	'catid'=>$neirongdb['catid'],
	'id'=>$neirongdb['id'],
	'sid'=>$_GET['sid'],
	'vs'=>$_GET['vs'],
);

$neirongdb['url'] = url_rewrite('KF_neirongshow', $_urlarr);
$smarty->assign('M', $lanmudata);
$smarty->assign('V', $neirongdb);
$smarty->assign('URL', get_link('a'));
?>