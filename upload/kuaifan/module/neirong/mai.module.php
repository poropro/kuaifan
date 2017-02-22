<?php
/*
 * 论坛卖贴购买
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
kf_class::run_sys_func('dingdan');
		

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


//论坛模型
if ($moxingdata['type'] == "bbs"){
	$maiarr = to_content($neirongdb['content'], "type", 1);
	//购买收费贴
	if ($maiarr[0] == 'mai'){
		$_arr = $maiarr[1];
		$_uarr = explode(",", $_arr[3]);
		if (in_array(US_USERID, $_uarr)){
			showmsg("提示信息", "你已经购买过无须重复购买！");
		}else{
			$titlearr = array("title" => "购买收费贴-{$neirongdb['title']}", "titleurl" => get_link("c")."&amp;c=show",);
			$_fun = "add_mai(\'diy_".$lanmudata['module']."_data\', ".$neirongdb['id'].", ".US_USERID.");";
			$paysqlarr = array("payfun" => "kf_class::run_sys_func(\'neirong\'); {$_fun}");
			$db->query("Delete from ".table("dingdan")." WHERE `payfun`='".$paysqlarr['payfun']."'");
			$dingdanid = tianjia_dingdan($titlearr, $_arr[1], 1, US_USERID, id_name($neirongdb['username'],1), 1, $paysqlarr, '', '购买收费贴', 0, $_arr[0]);
			$links[0]['title'] = '现在去付款';
			$links[0]['href'] = get_link("vs|sid",'','1').'&amp;m=dingdan&amp;c=xiangqing&amp;id='.$dingdanid;
			$links[1]['title'] = '返回继续阅读';
			$links[1]['href'] = get_link("c").'&amp;c=show';
			showmsg("提示信息", "已经成功下好订单！", $links);
		}
	}
}else{
	showmsg("提示信息", "参数错误！");
}
?>