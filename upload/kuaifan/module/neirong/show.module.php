<?php
/*
 * 内容页
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

//注册多久进入权限
$xianshi_zcsj = intval($lanmudata['setting']['xianshi_zcsj']);
if ($xianshi_zcsj > 0){
	if ($huiyuan_val['regdate'] > SYS_TIME - intval($xianshi_zcsj * 60)){
		showmsg("提示信息", "限制注册时间未达到{$xianshi_zcsj}会员进入！");
	}
}

//模型
$moxingdata = getcache(KF_ROOT_PATH.'caches/cache_neirong_moxing.php');
$moxingdata = $moxingdata[$lanmudata['modelid']];

//模板风格
$_CFG['template_dir'] = rtrim($lanmudata['setting']['default_style']?$lanmudata['setting']['default_style']:$moxingdata['default_style'],'/').'/';
//内容页面模板
$templatefile = rtrim($lanmudata['setting']['show_template']?$lanmudata['setting']['show_template']:$moxingdata['show_template'],'/');

//加载内容
$wheresql = "id=".intval($_GET['id']);
$neirongdb1 = $db -> getone("select * from ".table("diy_".$lanmudata['module'])." WHERE status=1 AND {$wheresql} LIMIT 1");
if (empty($neirongdb1)) showmsg("提示信息", "内容不存在！");
$neirongdb2 = $db -> getone("select * from ".table("diy_".$lanmudata['module']."_data")." WHERE {$wheresql} LIMIT 1");
$neirongdb = empty($neirongdb2)?$neirongdb1:array_merge($neirongdb1,$neirongdb2);
//$neirongdb = array_map('htmlspecialchars_decode',$neirongdb);

//跳转islink
if ($neirongdb['islink']) {
	tiaozhuan($neirongdb['islink']);
}

//标识(附件、收费)
$lanmudata['wenjian'] = $lanmudata['module']."_".$lanmudata['modelid']."_".$neirongdb['catid']."_".$neirongdb['id'];
//检测收费
if ($neirongdb['readpoint'] > 0){
	if ($_GET['zhifu']){
		require(KF_INC_PATH.'denglu.php');
		$paytype = $neirongdb['paytype']?"amount":"point";
		$zhifuid = yueduzhifu($lanmudata['wenjian']);
		if (empty($zhifuid)) showmsg("提示信息", "网络繁忙，请稍后再试！");
		$paysql = "update ".table('neirong_zhifu')." set `time`='{ubb}[miao]{/ubb}' WHERE id={$zhifuid};";
		$titlearr = array("title" => "阅读消费-{$neirongdb['title']}", "titleurl" => get_link("zhifu"),);
		kf_class::run_sys_func('dingdan');
		$dingdanid = tianjia_dingdan($titlearr, $neirongdb['readpoint'], 1, $huiyuan_val['userid'], 0, 1, $paysql, '', '阅读信息', 0, $paytype);
		$links[0]['title'] = '现在去付款';
		$links[0]['href'] = get_link("vs|sid",'','1').'&amp;m=dingdan&amp;c=xiangqing&amp;id='.$dingdanid;
		$links[1]['title'] = '进入会员中心';
		$links[1]['href'] = get_link("vs|sid",'','1').'&amp;m=huiyuan&amp;c=index';
		showmsg("提示信息", "已经成功下好订单！", $links);
	}
	if (!check_payment($lanmudata['wenjian'], $lanmudata['setting']['repeatchargedays'])){
		$paytype_cn = $neirongdb['paytype']?$_CFG['amountname']:"积分";
		$links[0]['title'] = '返回来源地址';
		$links[0]['href'] = '-1';
		$plink = '<a href="'.get_link("zhifu").'&amp;zhifu=1">确定支付</a>';
		showmsg("提示信息", "阅读此信息需要您支付 {$neirongdb['readpoint']} {$paytype_cn}！<br/>{$plink}");
	}
}
//增加点击数
$neirongdb['read']++;
updatetable(table("diy_".$lanmudata['module']), array('read'=>$neirongdb['read'],'readtime'=>SYS_TIME), "id=".$neirongdb['id']);

//评论心情
if ($_GET['xinqing']){
	$links[0]['title'] = '返回内容页面';
	$links[0]['href'] = get_link("xinqing");
	$param = kf_class::run_sys_class('param');
	$cookies = $param->get_cookie('xinqing_id');
	$cookie = explode(',', $cookies);
	$xinqing_id = $neirongdb['catid'].'-'.$neirongdb['id'].'-'.$neirongdb['site'];
	if (!in_array($xinqing_id, $cookie)) {
		$param->set_cookie('xinqing_id', $cookies.','.$xinqing_id);
		$_GET['xinqing'] = intval($_GET['xinqing']);
		if ($_GET['xinqing'] <= 10 && $_GET['xinqing'] >= 1){
			$wheresql = "catid=".$neirongdb['catid'];
			$wheresql.= " AND site=".$neirongdb['site'];
			$wheresql.= " AND contentid=".$neirongdb['id'];
			$xinqingdata = $db -> getone("select * from ".table("xinqing")." WHERE {$wheresql} LIMIT 1");
			if (empty($xinqingdata)){
				$qarr = array();
				$qarr['catid'] = $neirongdb['catid'];
				$qarr['contentid'] = $neirongdb['id'];
				$qarr['total'] = 1;
				$qarr['lastupdate'] = SYS_TIME;
				$qarr['n'.$_GET['xinqing']] = 1;
				$qarr['site'] = $neirongdb['site'];
				inserttable(table("xinqing"), $qarr);
			}else{
				$qarr = array();
				$qarr['total'] = $xinqingdata['total']+1;
				$qarr['lastupdate'] = SYS_TIME;
				$qarr['n'.$_GET['xinqing']] = $xinqingdata['n'.$_GET['xinqing']]+1;
				updatetable(table("xinqing"), $qarr, "id=".$xinqingdata['id']);
			}
		}
		showmsg("提示信息", "<a href='#ok:'></a>表达心情成功！", $links);
	}else{
		showmsg("提示信息", "<a href='#to:'></a>你已经表达过心情了，保持平常心有益身体健康！", $links);
	}
}

$pname = 'p'; //内容分页变量名
$_urlarr = array(
		'm'=>'neirong',
		'c'=>'show',
		'catid'=>$neirongdb['catid'],
		'id'=>$neirongdb['id'],
		'sid'=>$_GET['sid'],
		'vs'=>$_GET['vs'],
		$pname=>$_GET[$pname],
);
$NOWURL = url_rewrite("KF_neirongshow", $_urlarr);

//添加收藏
if ($_GET['shoucang']){
	$_urlarr = array(
			'm'=>'neirong',
			'c'=>'show',
			'catid'=>$neirongdb['catid'],
			'id'=>$neirongdb['id'],
			'sid'=>$_GET['sid'],
			'vs'=>$_GET['vs'],
	);
	$URL = url_rewrite("KF_neirongshow", $_urlarr, false);
	$links[0]['title'] = '返回内容页面';
	$links[0]['href'] = $NOWURL;
	set_shoucang("[{$lanmudata['title']}]".$neirongdb['title'], $URL);
	showmsg("提示信息", "收藏成功！", $links);
}

//评论
if ($_POST['dosubmit']){
	$pl = add_pinglun('neirong', $neirongdb['catid'], $neirongdb['id'], $neirongdb['title'], $_POST['pl']);
	if (empty($pl)){
		$links[0]['title'] = '返回内容页面';
		$links[0]['href'] = $NOWURL;
		showmsg("提示信息", "评论失败！", $links);
	}else{
		to_paifa("diy_".$lanmudata['module']."_data", $neirongdb['id'], $neirongdb['title'], $neirongdb['content'], US_USERID);
		$links[0]['title'] = '进评论列表';
		$links[0]['href'] = kf_url('neirongreply');
		$links[1]['title'] = '返回内容页面';
		$links[1]['href'] = $NOWURL;
		if ($_POST['go_url']) $go_url = urldecode($_POST['go_url']);
		if ($pl == 2){
			showmsg("提示信息", "<a href='#ok1:'></a>评论成功，等待审核后显示！", $links, $go_url);
		}else{
			showmsg("提示信息", "<a href='#ok:'></a>评论成功！", $links, $go_url);
		}
	}
}
//支持评论
if ($_GET['upport']){
	$links[0]['title'] = '返回内容页面';
	$links[0]['href'] = $NOWURL;
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

//分页数
$_GET[$pname] = $_POST[$pname]?$_POST[$pname]:$_GET[$pname];
if (empty($_GET[$pname])) unset($_GET[$pname]);
$p = max(intval($_GET[$pname]), 1);

//内容GET组
$smarty->assign('getarr', array('m'=>'neirong','c'=>'show','catid'=>$neirongdb['catid'],'id'=>$neirongdb['id']));

//论坛模型
if ($moxingdata['type'] == "bbs"){
	$smarty->assign('type', to_content($neirongdb['content'], "type", 1));
	$neirongdb['content'] = to_content_bbs($neirongdb['content']);
}

//内容处理
$content = $neirongdb['content'];
$neirongdb['_content'] = $content;
if ($neirongdb['thumb']){
	$neirongdb['thumb'] = string2array($neirongdb['thumb']);
}

//未设置手动分页根据设置字节数对文章加入分页标记
if(strpos($content, '[page]') === false) {
	$maxcharperpage = $neirongdb['pages'];
	if($maxcharperpage < 10) $maxcharperpage = 1000; //默认分页1000个字节
	$content = get_neirong_page($content, $maxcharperpage);
}
//进行分页处理
$content_pos = strpos($content, '[page]');
if($content_pos !== false) {
	$contents = array_filter(explode('[page]', $content));
	$pagenumber = count($contents);
	if (strpos($content, '[/page]')!==false && ($content_pos<7)) {
		$pagenumber--;
	}
	for($i=1; $i<=$pagenumber; $i++) {
		$_urlarr = array(
			'm'=>'neirong',
			'c'=>'show',
			'catid'=>$neirongdb['catid'],
			'id'=>$neirongdb['id'],
			$pname=>$i,
			'sid'=>$_GET['sid'],
			'vs'=>$_GET['vs'],
		);
		$pageurls[$i] = url_rewrite("KF_neirongshow", $_urlarr);
		if ($i == $p) $pageurls['now'] = url_rewrite("KF_neirongshow", $_urlarr, false);
	}
	$END_POS = strpos($content, '[/page]');
	if($END_POS !== false) {
		if(preg_match_all("|\[page\](.*)\[/page\]|U", $content, $m, PREG_PATTERN_ORDER)) {
			foreach($m[1] as $k=>$v) {
				$j = $k+1;
				$titles[$j]['title'] = strip_tags($v);
				$titles[$j]['url'] = $pageurls[$j][0];
			}
		}
	}
	//当不存在 [/page]时，则使用下面分页
	$pagelink = get_content_pages($pagenumber, $p, $pageurls);
	//判断[page]出现的位置是否在第一位
	if($content_pos < 7) {
		$content = $contents[$p];
	} else {
		if ($p==1 && !empty($titles)) {
			$content = $title.'[/page]'.$contents[$p-1];
		} else {
			$content = $contents[$p-1];
		}
	}
	//查看余下全文
	if($_GET['l']=='s') {
		$content = $pagelink ='';
		for($i=$p+1;$i<=$pagenumber;$i++) {
			$content .=$contents[$i-1];
		}
	}
	//查看全文
	if($_GET['l']=='a') {
		$content = $pagelink ='';
		for($i=0;$i<=$pagenumber;$i++) {
			$content .=$contents[$i-1];
		}
	}
}else{
	$contents = array($content);
}

$content = ubb_neirong($content); //过滤字符
$neirongdb['content'] = $content;
$neirongdb['pagelink'] = $pagelink; //分页链接
$neirongdb['page'] = $p; //当前页
$neirongdb['pagenumber'] = $pagenumber; //总页数

//心情
$wheresql = "catid=".$neirongdb['catid'];
$wheresql.= " AND site=".$neirongdb['site'];
$wheresql.= " AND contentid=".$neirongdb['id'];
$xinqing_db = get_cache('xinqing');
$xinqingdata = $db -> getone("select * from ".table("xinqing")." WHERE {$wheresql} LIMIT 1");
$xinqingdb = array(); $xqn = 0;
foreach ($xinqing_db as $k=>$v) {
	if ($v['use']){
		$v['n'] = $xqn++;
		$v['k'] = $k;
		$v['num'] = intval($xinqingdata['n'.$k]);
		$xinqingdb[$k] = $v;
	}
}


//栏目SEO
$_SEO['title'] = $neirongdb['title'].' - '.$lanmudata['title'];
$_SEO['keywords'] = ltrim($neirongdb['keywords'].','.$neirongdb['title'], ',');
$_SEO['description'] = $neirongdb['description'];

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

$smarty->assign('content', $content); //内容
$smarty->assign('contents', $contents); //内容组
$smarty->assign('pageurls', $pageurls); //分页组
$smarty->assign('bbs_bzarr', $bbs_bzarr); //版主组
$smarty->assign('M', $lanmudata);
$smarty->assign('V', $neirongdb);
$smarty->assign('Q', $xinqingdb);
$smarty->assign('K', explode(',', $_SEO['keywords']));
?>