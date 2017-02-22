<?php
/*
 * 论坛悬赏贴设为最佳回复
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');
kf_class::run_sys_func('huiyuan');

//栏目
$columnarr = getcache('caches/column/cache.'.$_CFG['site'].'.php');
$lanmudata = $columnarr[$_GET['catid']];
if (empty($lanmudata)) {
  $links[0]['title'] = '返回网站首页';
  $links[0]['href'] = kf_url('index');
  showmsg("提示信息", "栏目不存在！", $links);
}

//模型
$moxingdata = getcache(KF_ROOT_PATH.'caches/cache_neirong_moxing.php');
$moxingdata = $moxingdata[$lanmudata['modelid']];

//加载内容
$wheresql = "`id`=".intval($_GET['id']);
$neirongdb1 = $db -> getone("select * from ".table("diy_".$lanmudata['module'])." WHERE `status`=1 AND {$wheresql} LIMIT 1");
if (empty($neirongdb1)) showmsg("提示信息", "内容不存在！");
$neirongdb2 = $db -> getone("select * from ".table("diy_".$lanmudata['module']."_data")." WHERE {$wheresql} LIMIT 1");
$neirongdb = empty($neirongdb2)?$neirongdb1:array_merge($neirongdb1,$neirongdb2);
//$neirongdb = array_map('htmlspecialchars_decode',$neirongdb);

//加载评论
$row = $db -> getone("select * from ".table("pinglun_data_".$_CFG['site'])." WHERE id='{$_GET['rid']}' LIMIT 1");
if (empty($row)) showmsg("提示信息", "此评论不存在！");

//论坛模型
if ($moxingdata['type'] == "bbs"){
	$xuanarr = to_content($neirongdb['content'], "type", 1);
	//悬赏贴设置为最佳
	if ($xuanarr[0] == 'xuan'){
		$_arr = $xuanarr[1];
		$_tian = (SYS_TIME - $row['creat_at'])/86400;
		if ($_tian > $_arr[2]){
			showmsg("提示信息", "设置失败，此回复不在有效期“{$_arr[2]}天”内回复！");
		}
		if (!empty($_arr[3])){
			showmsg("提示信息", "设置失败，你已经设置过了！");
		}else{
			$oldcontent = "[bbs=xuan]".$_arr[0]."|".$_arr[1]."|".$_arr[2]."[/bbs]";
			$newcontent = "[bbs=xuan]".$_arr[0]."|".$_arr[1]."|".$_arr[2]."|".$_GET['rid']."[/bbs]";
			$newcontent = str_replace($oldcontent, $newcontent, $neirongdb['content']);
			$_arr[1] = abs($_arr[1]);
			if ($_arr[0] == "point"){
				if ($row['userid'] > 0) set_jiangfa($row['userid'], $_arr[1], 0, '评论悬赏被设为最佳');
				updatetable(table("diy_".$lanmudata['module']."_data"), array('content' => $newcontent), $wheresql);
				showmsg("提示信息", "设置成功！");
			}elseif ($_arr[0] == "amount"){
				if ($row['userid'] > 0) set_jiangfa($row['userid'], $_arr[1], 1, '评论悬赏被设为最佳');
				updatetable(table("diy_".$lanmudata['module']."_data"), array('content' => $newcontent), $wheresql);
				showmsg("提示信息", "设置成功！");
			}else{
				showmsg("提示信息", "网络繁忙，请稍后再试！");
			}
		}
	}
}else{
	showmsg("提示信息", "参数错误！");
}
?>