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

$_urlarr = array(
	'm'=>'neirong',
	'c'=>'show',
	'catid'=>$neirongdb['catid'],
	'id'=>$neirongdb['id'],
	'sid'=>$_GET['sid'],
	'vs'=>$_GET['vs'],
);
$neirongdb['url'] = url_rewrite('KF_neirongshow', $_urlarr);

//评论权限相关
$pl_arr = getcache('caches/caches_peizhi_mokuai/cache.pinglun.php');
$pinglun_guest_del = $pl_arr['pinglun_guest_del'];
$pinglun_format = $pl_arr['pinglun_format'];
$pinglun_format_num = $pl_arr['pinglun_format_num'];
if ($lanmudata['setting']['pinglun_guest_del'] == '1') $pinglun_guest_del = 1; 
if ($lanmudata['setting']['pinglun_guest_del'] == '2') $pinglun_guest_del = 0; 
if ($lanmudata['setting']['pinglun_format_num'] == '0') $pinglun_format_num = 0; 
if ($lanmudata['setting']['pinglun_format_num'] > 0) $pinglun_format_num = intval($lanmudata['setting']['pinglun_format_num']); 
if (!empty($lanmudata['setting']['pinglun_format'])) $pinglun_format = $lanmudata['setting']['pinglun_format']; 
$lanmudata['setting']['pinglun_guest_del'] = $pinglun_guest_del;
$lanmudata['setting']['pinglun_format_num'] = $pinglun_format_num;
$lanmudata['setting']['pinglun_format'] = $pinglun_format;
$bbs_bzarr = explode('|',$lanmudata['setting']['bbs_banzhu']);
if (empty($lanmudata['setting']['bbs_banzhu'])) $bbs_bzarr = array();

//评论
if ($_POST['dosubmit']){
	$plfile = '';
	//文件上传
	if ($_FILES){
		if (empty($_POST['pl']) || get_strlen($_POST['pl']) < 2)  showmsg('系统提醒','评论内容不得小于2个字符！');
		if (get_strlen($_POST['pl']) > 300)  showmsg('系统提醒','评论内容不得大于300个字符！');
		//
		kf_class::run_sys_func('upload');
		$i = 1; 
		foreach($_FILES as $_k=>$_v) {
			//跳过
			if (!$_FILES[$_k]['name'] || $i > $pinglun_format_num) continue;
			//上传格式
			$_file_allowext = $pinglun_format;
			if (!$_file_allowext) showmsg("系统提醒", "评论未设置允许上传的文件类型！");
			$_file_allowext = str_replace('|', '/', $_file_allowext);
			//上传大小
			$_file_size = intval(ini_get('upload_max_filesize')*1024);
			//目录构造
			$up_dir_0 = "uploadfiles/content/review/";
			make_dir('./'.$up_dir_0.date("Y/m/d/"));
			//开始上传
			$tmp_file = _asUpFiles('./'.$up_dir_0.date("Y/m/d/"), $_k, $_file_size, $_file_allowext, true);
			if ($tmp_file){
				$tmp_size = formatBytes(filesize($up_dir_0.date("Y/m/d/").$tmp_file));
				$tmp_size = str_replace(' ', '', $tmp_size);
				$plfile.= '<br/><a href="'.$up_dir_0.date("Y/m/d/").$tmp_file.'">附件'.$i.':'.htmlspecialchars($_FILES[$_k]['name']).'('.$tmp_size.')</a>';
				$i++;
			}
		}
	}
	$pl = add_pinglun('neirong', $neirongdb['catid'], $neirongdb['id'], $neirongdb['title'], $_POST['pl'], 0, $plfile);
	if (empty($pl)){
		$links[0]['title'] = '返回内容页面';
		$links[0]['href'] = $neirongdb['url'];
		showmsg("提示信息", "评论失败！", $links);
	}else{
		to_paifa("diy_".$lanmudata['module']."_data", $neirongdb['id'], $neirongdb['title'], $neirongdb['content'], US_USERID);
		$links[0]['title'] = '回评论列表';
		$links[0]['href'] = kf_url('neirongreply');
		$links[1]['title'] = '返回内容页面';
		$links[1]['href'] = $neirongdb['url'];
		if ($_POST['go_url']) $go_url = urldecode($_POST['go_url']);
		if ($pl == 2){
			showmsg("提示信息", "评论成功，等待审核后显示！", $links, $go_url);
		}else{
			showmsg("提示信息", "评论成功！", $links, $go_url);
		}
	}
}
//支持评论
if ($_GET['upport']){
	$links[0]['title'] = '返回内容页面';
	$links[0]['href'] = $neirongdb['url'];
	$param = kf_class::run_sys_class('param');
	if ($param->get_cookie('comment_'.$_GET['upport'])) {
		showmsg("提示信息", "<a href='#to:'></a>支持过了！", $links);
	}
	if (!$db -> query("update ".table('pinglun_data_'.$_CFG['site'])." set support=support+1 WHERE id='{$_GET['upport']}'")){
		showmsg("提示信息", "<a href='#no:'></a>支持失败！", $links);
	}else{
		$param->set_cookie('comment_'.$_GET['upport'], $_GET['upport'], SYS_TIME+3600);
		showmsg("提示信息", "<a href='#ok:'></a>支持成功！", $links);
	}
}

//内容GET组
$smarty->assign('getarr', array('m'=>'neirong','c'=>'show','catid'=>$neirongdb['catid'],'id'=>$neirongdb['id']));

//论坛模型
if ($moxingdata['type'] == "bbs"){
	$smarty->assign('type', to_content($neirongdb['content'], "type", 1));
	$neirongdb['content'] = to_content_bbs($neirongdb['content']);
}

//栏目SEO
$_SEO['title'] = $neirongdb['title'].' - '.$lanmudata['title'];
$_SEO['keywords'] = ltrim($neirongdb['keywords'].','.$neirongdb['title'], ',');
$_SEO['description'] = $neirongdb['description'];

$smarty->assign('bbs_bzarr', $bbs_bzarr); //版主组
$smarty->assign('M', $lanmudata);
$smarty->assign('V', $neirongdb);
$smarty->assign('K', explode(',', $_SEO['keywords']));

//文件回复贴
if ($_GET['a'] == 'upfile'){
	if ($pinglun_format_num < 1){
		showmsg("提示信息", "禁止文件回复贴！");
	}
	require(KF_INC_PATH.'denglu.php');
	$_POST['upi'] = (intval($_POST['upi'])>0)?intval($_POST['upi']):'1';
	$_POST['upi'] = (intval($_POST['upi'])>$pinglun_format_num)?$pinglun_format_num:intval($_POST['upi']);
	$__input = "";
	for ($i=1; $i<=$_POST['upi']; $i++) {
		$__input.= '文件'.$i.':<input type="file" name="'.$_GET['upfile'].'_'.$i.'" size="10" /><br/>'.chr(13);
	}
	$smarty->assign('__input', $__input);
	
	kf_class::run_sys_func('upload');
	$upconfig = getcache(KF_ROOT_PATH. "caches/caches_peizhi_mokuai/cache.fujian.php");
	$max_size = intval(ini_get('upload_max_filesize')*1024);
	if ($upconfig['upload_maxsize'] > 0 && $upconfig['upload_maxsize'] < $max_size) $max_size = $upconfig['upload_maxsize'];

	$fudatasetting['upload_allowext'] = $pinglun_format;
	$fudatasetting['upload_number'] = $pinglun_format_num;
	$smarty->assign('fudatasettingone', _formatSize($max_size*1024)); 
	$smarty->assign('fudatasetting', $fudatasetting);

	$templatefile = "upfilepl";
}
?>