<?php
/*
 * 回复评论
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');



$row = $db -> getone("select * from ".table("pinglun_data_".$_CFG['site'])." WHERE id='{$_GET['rid']}' LIMIT 1");
if (empty($row)) showmsg("提示信息", "此评论不存在！");
$row2 = $db -> getone("select * from ".table("pinglun")." WHERE commentid='{$row['commentid']}' LIMIT 1");
$pinglundb = array_merge($row,$row2);
//$pinglundb = array_map('htmlspecialchars_decode',$pinglundb);

$neirongdb = explode('_', $row['commentid']);
$neirongdb['title'] = $row2['title'];
$neirongdb['catid'] = $neirongdb[1];
$neirongdb['id'] = $neirongdb[2];

//栏目
$columnarr = getcache('caches/column/cache.'.$_CFG['site'].'.php');
$lanmudata = $columnarr[$neirongdb['catid']];
$lanmudataset = string2array($lanmudata['setting']);

$_urlarr = array(
	'm'=>'neirong',
	'c'=>'show',
	'catid'=>$neirongdb['catid'],
	'id'=>$neirongdb['id'],
	'sid'=>$_GET['sid'],
	'vs'=>$_GET['vs'],
);
$neirongdb['url'] = url_rewrite('KF_neirongshow', $_urlarr);

//删除评论
if ($_GET['del']){
	require(KF_INC_PATH.'denglu.php');
	$pl_arr = getcache('caches/caches_peizhi_mokuai/cache.pinglun.php');
	$pinglun_add_point = $pl_arr['pinglun_add_point'];
	if ($lanmudataset['pinglun_add_point'] > 0) $pinglun_add_point = intval($lanmudataset['pinglun_add_point']); 
	$pinglun_del_point = $pl_arr['pinglun_del_point'];
	if ($lanmudataset['pinglun_del_point'] > 0) $pinglun_del_point = intval($lanmudataset['pinglun_del_point']); 

	if (!$pl_arr['pinglun_guest_del']){
		showmsg("提示信息", "网站禁止删除！");
	}
	if ($row['userid'] < 1){
		showmsg("提示信息", "此内容禁止删除！");
	}
	$bbs_bzarr = explode('|',$lanmudataset['bbs_banzhu']);
	if (empty($lanmudataset['bbs_banzhu'])) $bbs_bzarr = array();
	if ($row['userid'] != US_USERID && !in_array(US_USERID,$bbs_bzarr)){
		showmsg("提示信息", "你没有这个权限！");
	}
	if ($_GET['dosubmit']){
		$links[0]['title'] = '回评论列表';
		$links[0]['href'] = kf_url('neirongreply');
		$links[1]['title'] = '返回内容页面';
		$links[1]['href'] = $neirongdb['url'];
		if (!$db->query("Delete from ".table('pinglun_data_'.$_CFG['site'])." where `id`=".$row['id']."")){
			showmsg("系统提醒", "删除失败,请稍后再试！", $links);
		}else{
			delplfile($row['content']); //删除评论附件
			if ($row['status'] == 1){
				//统计内容评论数
				$db -> query("update ".table("diy_".$lanmudata['module'])." set reply=reply-1 WHERE `id`='{$neirongdb['id']}' AND `catid`='{$neirongdb['catid']}'");
				//统计评论数
				$pwhe = "total=total-1";
				if ($row['direction'] == '1'){
					$pwhe.= ",square=square-1";
				}elseif ($row['direction'] == '2'){
					$pwhe.= ",anti=anti-1";
				}elseif ($row['direction'] == '3'){
					$pwhe.= ",neutral=neutral-1";
				}
				$db -> query("update ".table('pinglun')." set {$pwhe} WHERE commentid='{$row['commentid']}'");
				//删除评论扣分
				kf_class::run_sys_func('huiyuan');
				if ($row['userid'] == US_USERID){
					if ($pinglun_add_point > 0){
						set_jiangfa($row['userid'], $pinglun_add_point*-1, 0, '删除自己的评论-'.$neirongdb['title']);
					}
				}elseif (in_array(US_USERID,$bbs_bzarr)){
					if ($pinglun_del_point > 0){
						set_jiangfa($row['userid'], $pinglun_del_point*-1, 0, '评论被版主'.$huiyuan_val['nickname'].'(ID:'.US_USERID.')删除-'.$neirongdb['title']);
						$dataarr = array();
						$dataarr['type'] = 'bzrz-scpl';
						$dataarr['userid'] = $huiyuan_val['userid'];
						$dataarr['dataid'] = $neirongdb['id'];
						$dataarr['dataid2'] = $neirongdb['catid'];
						$dataarr['intime'] = SYS_TIME;
						$dataarr['setting']['title'] = $neirongdb['title'];
						$dataarr['setting']['username'] = $huiyuan_val['username'];
						$dataarr['setting']['txt'] = "删除评论";
						$dataarr['setting']['plid'] = $row['id'];
						$dataarr['setting']['pluserid'] = $row['userid'];
						$dataarr['setting'] = array2string($dataarr['setting']);
						inserttable(table("neirong_data"), $dataarr);
					}
				}else{
					showmsg("提示信息", "你没有这个权限-2！");
				}
			}
			showmsg("提示信息", "删除成功。", $links);
		}
	}
	$links[0]['title'] = '确定删除';
	$links[0]['href'] = get_link("dosubmit").'&amp;dosubmit=1';
	$links[1]['title'] = '返回来源地址';
	$links[1]['href'] = -1;
	showmsg("提示信息", "确定删除此评论内容并且不可恢复吗！？<br/>注明:自己删除将扣除评论所得奖励{$pinglun_add_point}积分，管理员删除扣除{$pinglun_del_point}积分。", $links);
}

//评论
if ($_POST['dosubmit']){
	$pl = add_pinglun('neirong', $neirongdb['catid'], $neirongdb['id'], $neirongdb['title'], $_POST['pl'], $row['id']);
	if (empty($pl)){
		$links[0]['title'] = '回评论列表';
		$links[0]['href'] = kf_url('neirongreply');
		showmsg("提示信息", "评论失败！", $links);
	}else{
		to_paifa("diy_".$lanmudata['module']."_data", $neirongdb['id'], $neirongdb['title'], '', US_USERID);
		$links[0]['title'] = '回评论列表';
		$links[0]['href'] = kf_url('neirongreply');
		$links[1]['title'] = '返回内容页面';
		$links[1]['href'] = $neirongdb['url'];
		$links[2]['title'] = '返回继续评论';
		$links[2]['href'] = get_link("dosubmit");
		if ($_POST['go_url']) $go_url = urldecode($_POST['go_url']);
		if ($pl == 2){
			showmsg("提示信息", "回复评论成功，等待审核后显示！", $links, $go_url);
		}else{
			showmsg("提示信息", "回复评论成功！", $links, $go_url);
		}
	}
}


$smarty->assign('V', $neirongdb);
$smarty->assign('M', $lanmudata);
$smarty->assign('P', $pinglundb);
?>