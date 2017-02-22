<?php
/*
 * cms 内容模块相关函数
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
 * 模型类型
 * @param $type
 */
function moxing_type($type = ''){
	$_array = array(
		'new'=>'默认类型',
		'bbs'=>'论坛类型',
	);
	if (!empty($type)){
		return $_array[$type];
	}else{
		$_arr = array();
		foreach ($_array as $_k => $_v) {
			$_arr[$_v] = $_k;
		}
		return $_arr;
	}
}
/**
 * 当前路径 
 * 返回指定分类路径层级
 * @param 栏目ID $catid
 * @param 分类间隔符 $symbol
 * @param 包含首页 $index
 * @param class元素 $clas
 */
function get_pos($catid, $symbol='-&gt;', $index = 1, $clas = '') {
	global $_CFG;
	$type_arr = getcache('caches/column/cache.'.$_CFG['site'].'.php');
	$clas = !empty($clas)?' class="'.$clas.'"':'';
	if(!isset($type_arr[$catid])) return '';
	$pos = '';
	if($type_arr[$catid]['parentid']!=0) {
		$_urlarr = array(
			'm'=>'neirong',
			'c'=>'list',
			'catid'=>$type_arr[$catid]['parentid'],
			'sid'=>$_GET['sid'],
			'vs'=>$_GET['vs'],
		);
		$pos = '<a href="'.url_rewrite('KF_neironglist', $_urlarr).$clas.'">'.$type_arr[$type_arr[$catid]['parentid']]['title'].'</a>'.$symbol;
	}
	$_urlarr = array(
		'm'=>'neirong',
		'c'=>'list',
		'catid'=>$catid,
		'sid'=>$_GET['sid'],
		'vs'=>$_GET['vs'],
	);
	$pos .= '<a href="'.url_rewrite('KF_neironglist', $_urlarr).$clas.'">'.$type_arr[$catid]['title'].'</a>';
	if ($index){
		$_urlarr = array(
			'm'=>'index',
			'sid'=>$_GET['sid'],
			'vs'=>$_GET['vs'],
		);
		$pos = '<a href="'.url_rewrite('KF_index', $_urlarr).$clas.'">首页</a>'.$symbol.$pos;
	}
	return $pos;
}
/**
 * 返回栏目链接地址
 * @param 栏目ID $catid
 */
function get_catid_url($catid) {
	$_urlarr = array(
		'm'=>'neirong',
		'c'=>'list',
		'catid'=>$catid,
		'sid'=>$_GET['sid'],
		'vs'=>$_GET['vs'],
	);
	return url_rewrite('KF_neironglist', $_urlarr);
}
/**
 * 返回上级栏目链接地址,如果没有上级就返回首页
 * @param 栏目ID $catid
 */
function get_pos_url($catid) {
	global $_CFG;
	$type_arr = getcache('caches/column/cache.'.$_CFG['site'].'.php');
	if($type_arr[$catid]['parentid']!=0) {
		$_urlarr = array(
			'm'=>'neirong',
			'c'=>'list',
			'catid'=>$type_arr[$catid]['parentid'],
			'sid'=>$_GET['sid'],
			'vs'=>$_GET['vs'],
		);
		return url_rewrite('KF_neironglist', $_urlarr);
	}else{
		return kf_url('index');
	}
}

/**
 * 添加评论
 * @param $cname 
 * @param $catid 栏目ID
 * @param $contentid 内容ID
 * @param $title 内容标题
 * @param $content 评论内容
 * @param $yid 原评论ID
 * @param $htmlcontent 评论内容(不处理)
 * @return 0评论失败、1评论成功、2评论成功需要审核
 */
function add_pinglun($cname, $catid, $contentid, $title, $content, $yid = 0, $htmlcontent = ''){
	global $db,$_CFG,$online_ip,$huiyuan_val;
	$commentid = $cname.'_'.$catid.'_'.$contentid.'_'.$_CFG['site'];
	if (empty($content) || get_strlen($content) < 2)  showmsg('系统提醒','评论内容不得小于2个字符！');
	if (get_strlen($content) > 300)  showmsg('系统提醒','评论内容不得大于300个字符！');
	//验证码
	$yzmpeizhi = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_peizhi_mokuai'.DIRECTORY_SEPARATOR.'cache.yanzhengma.php');
	if ($yzmpeizhi['pinglun']) {
		$_yzmhtml = "";
		if (!$_POST['yanzhengma']) {
			$_yzmhtml.= "请输入“验证码”！";
		}else{
			$_POST['ip'] = $_POST['ip']?$_POST['ip']:yanzhengmaip();
			$row = $db->getone("select * from ".table('yanzhengma')." where captcha = '{$_POST['ip']}' AND code = '{$_POST['yanzhengma']}' LIMIT 1");
			if (empty($row)){
				$_yzmhtml.= "请输入正确的“验证码”！";
			}elseif (intval($timestamp - $row['time']) > 3*60){ //验证码3分钟过期
				$_yzmhtml.= "“验证码”已过期，请返回换一张刷新！";
			}
		}
		if (!empty($_yzmhtml)) {
			$_yzmhtml.= "<br/>";
			if ($_GET['vs'] > 1){
				$_yzmhtml.= format_form(array("set" => "头"));
			}
			$_yzmhtml.= '<img src="'.get_link("m|c").'&amp;m=api&amp;c=yanzhengma" /><br/>';
			$_yzmhtml.= format_form(array("set" => "输入框|名称:'yanzhengma'")).'<br/>';
			unset($_POST['dosubmit']);
			unset($_POST['yanzhengma']);
			unset($_POST['hidden-csrfpage']);
			unset($_POST['hidden-csrftoken']);
			if ($_GET['vs'] > 1){
				foreach ($_POST as $_k => $_v) {
					if ($_CFG['open_post']=='addslashes' || get_magic_quotes_gpc()){
						$_v = stripslashes($_v);
					}
					if ($_CFG['open_post']!='htmlspecialchars'){
						$_v = htmlspecialchars($_v);
					}
					$_yzmhtml.= format_form(array("set" => "隐藏|名称:'".$_k."'","data_value" => $_v));
				}
				$_yzmhtml.= format_form(array("set" => "按钮|名称:'dosubmit',值:提交"));
				$_yzmhtml.= format_form(array("set" => "尾"));
			}else{
				$_yzmhtml.= "<anchor>提交";
				$_yzmhtml.= "<go href=\"".get_link()."\" method=\"post\" accept-charset=\"utf-8\">";
				foreach ($_POST as $_k => $_v) {
					if ($_CFG['open_post']=='addslashes' || get_magic_quotes_gpc()){
						$_v = stripslashes($_v);
					}
					if ($_CFG['open_post']!='htmlspecialchars'){
						$_v = htmlspecialchars($_v);
					}
					$_yzmhtml.= "<postfield name=\"{$_k}\" value=\"{$_v}\"/>";
				}
				$_yzmhtml.= "<postfield name=\"yanzhengma\" value=\"$(yanzhengma)\"/>";
				$_yzmhtml.= "<postfield name=\"dosubmit\" value=\"1\"/>";
				$_yzmhtml.= "</go> </anchor>";
			}
			showmsg('输入验证码', $_yzmhtml);
		}
		unset($_POST['yanzhengma']);
	}
	//
	$pl_arr = getcache('caches/caches_peizhi_mokuai/cache.pinglun.php');
	$pinglun_guest = $pl_arr['pinglun_guest'];
	$pinglun_check = $pl_arr['pinglun_check'];
	$pinglun_add_point = $pl_arr['pinglun_add_point'];
	$pinglun_auser = $pl_arr['pinglun_auser'];
	$pinglun_ubb = $pl_arr['pinglun_ubb'];
	$pinglun_browser = $pl_arr['pinglun_browser'];
	/*获取栏目评论设置*/
	$lanmudata = getcache('caches/column/cache.'.$_CFG['site'].'.php');
	$lanmudata = $lanmudata[$catid];
	$lanmudataset = string2array($lanmudata['setting']);
	if ($lanmudataset['pinglun_guest'] == '1') $pinglun_guest = 1; 
	if ($lanmudataset['pinglun_guest'] == '2') $pinglun_guest = 0; 
	if ($lanmudataset['pinglun_check'] == '1') $pinglun_check = 1; 
	if ($lanmudataset['pinglun_check'] == '2') $pinglun_check = 0; 
	if ($lanmudataset['pinglun_add_point'] == '0') $pinglun_add_point = 0; 
	if ($lanmudataset['pinglun_add_point'] > 0) $pinglun_add_point = intval($lanmudataset['pinglun_add_point']); 
	if ($lanmudataset['pinglun_auser'] == '1') $pinglun_auser = 1; 
	if ($lanmudataset['pinglun_auser'] == '2') $pinglun_auser = 0; 
	if ($lanmudataset['pinglun_ubb'] == '1') $pinglun_ubb = 1; 
	if ($lanmudataset['pinglun_ubb'] == '2') $pinglun_ubb = 0; 
	//
	if (empty($pinglun_guest)){
		require(KF_INC_PATH.'denglu.php');
	}
	//限时评论间隔
	$xianshi_hf = intval($lanmudataset['xianshi_hf']);
	if ($xianshi_hf > 0){
		$lastrow = $db->getone("select * from ".table("pinglun_data_".$_CFG['site'])." WHERE `userid`=".intval(US_USERID)." AND `creat_at`>".SYS_TIME - $xianshi_hf);
		if (!empty($lastrow)){
			showmsg("提示信息", "限制评论间隔时间为{$xianshi_hf}秒！");
		}
	}
	//注册多久限制评论
	$xianshi_zcpl = intval($lanmudataset['xianshi_zcpl']);
	if ($xianshi_zcpl > 0){
		if ($huiyuan_val['regdate'] > SYS_TIME - intval($xianshi_zcpl * 60)){
			showmsg("提示信息", "限制注册时间未达到{$xianshi_zcpl}分钟的会员评论！");
		}
	}
	$content = htmlspecialchars($content);
	if ($pinglun_ubb){
		kf_class::run_sys_func('ubb');
		$content = ubb($content);
	}
	$messagearr = (empty($pinglun_auser))?array():set_mention($content, $catid, $contentid, $title);
	if ($htmlcontent) $content.= $htmlcontent;
	//附加浏览器信息
	if ($pinglun_browser) {
		$browser_AGENT = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';
		if ($browser_AGENT) {
			$browser_AGENT_cn = '';
			if (strpos($browser_AGENT, "qqbrowser") !== false) {
				$browser_AGENT_cn = "QQ浏览器";
			}elseif (strpos($browser_AGENT, "ucbrowser")) {
				$browser_AGENT_cn = "UC浏览器";
			}elseif (strpos($browser_AGENT, "opera") !== false) {
				$browser_AGENT_cn = "欧朋浏览器";
			}elseif (strpos($browser_AGENT, "sogou") !== false) {
				$browser_AGENT_cn = "搜狗浏览器";
			}elseif (strpos($browser_AGENT, "baidu") !== false) {
				$browser_AGENT_cn = "百度浏览器";
			}elseif (strpos($browser_AGENT, "chrome") !== false) {
				$browser_AGENT_cn = "谷歌浏览器";
			}elseif (strpos($browser_AGENT, "firefox") !== false) {
				$browser_AGENT_cn = "火狐浏览器";
			}elseif (strpos($browser_AGENT, "360browser") !== false) {
				$browser_AGENT_cn = "360浏览器";
			}elseif (strpos($browser_AGENT, "liebao") !== false) {
				$browser_AGENT_cn = "猎豹浏览器";
			}elseif (strpos($browser_AGENT, "android") !== false) {
				$browser_AGENT_cn = "Android";
			}elseif (strpos($browser_AGENT, "iphone") !== false) {
				$browser_AGENT_cn = "iPhone";
			}elseif (strpos($browser_AGENT, "ipad") !== false) {
				$browser_AGENT_cn = "iPad";
			}elseif (strpos($browser_AGENT, "blackberry") !== false) {
				$browser_AGENT_cn = "BlackBerry";
			}elseif (strpos($browser_AGENT, "windows phone") !== false) {
				$browser_AGENT_cn = "Windows Phone";
			}
			if (!empty($browser_AGENT_cn)) {
				$content.= ' <span class="cfrom-browser">(来自'.$browser_AGENT_cn.')</span>';
			}
		}
	}
	//回复原评论
	$is_huifu = 0;
	if ($yid > 0){
		$_yrow = $db -> getone("select * from ".table("pinglun_data_".$_CFG['site'])." WHERE id='{$yid}' LIMIT 1");
		if (!empty($_yrow)) {
			$_nickname = "原评论";
			if ($_yrow['userid'] > 0){
				$_urow = $db -> getone("select * from ".table("huiyuan")." WHERE `userid`='{$_yrow['userid']}' LIMIT 1");
				if ($_urow) $_nickname = "@".$_urow['nickname']." ";
			}
			$content.= '<br/><div class="yuanpinglun">--'.$_nickname.':'.set_yuanpinglun($_yrow['content']).'</div>';
			$is_huifu = $_yrow['id'];
		}
	}
	$_row = $db -> getone("select * from ".table('pinglun')." WHERE commentid='{$commentid}' LIMIT 1");	
	if (empty($_row)){
		$_arr = array();
		$_arr['commentid'] = $commentid;
		$_arr['title'] = $title;
		$_arr['total'] = 0; //评论总数
		$_arr['square'] = 0; //支持正
		$_arr['anti'] = 0; //支持反
		$_arr['neutral'] = 0; //支持中
		$_arr['lastupdate'] = 0; //最后评论时间
		$_arr['site'] = $_CFG['site'];
		inserttable(table('pinglun'), $_arr);
	}
	$_arr = array();
	$_arr['commentid'] = $commentid;
	$_arr['userid'] = intval(US_USERID);
	if (US_USERID < 1){
		$ip_area = kf_class::run_sys_class('ip_area');
		$ip_city = $ip_area->get($online_ip);
		if ($ip_city == 'Unknown' || $ip_city == 'IANA' || $ip_city == 'RIPE'){
			$ip_city_arr = $ip_area->getcitybyapi($online_ip);
			$ip_city = $ip_city_arr['city'];
		}
		$_arr['username'] = $ip_city?$ip_city.' '.US_USERNAME:US_USERNAME;
	}else{
		$_arr['username'] = US_USERNAME;
	}
	$_arr['creat_at'] = SYS_TIME;
	$_arr['ip'] = $online_ip;
	$_arr['status'] = $pinglun_check?0:1; //0需要审核 1审核通过
	$_arr['content'] = $content;
	$_arr['direction'] = 1; //1正 2反 3中
	$_arr['support'] = 0; //支持
	$_arr['reply'] = 0; //回复
	$_arr['is_huifu'] = $is_huifu; //回复ID
	$_arr['site'] = $_CFG['site'];
	if (!inserttable(table('pinglun_data_'.$_CFG['site']), $_arr)){
		return 0;
	}else{
		if (!empty($_yrow)) $db -> query("update ".table("pinglun_data_".$_CFG['site'])." set reply=reply+1 WHERE id='{$_yrow['id']}'");
		if ($_arr['status'] == 1){
			//通知原评论
			if ($is_huifu > 0 && $_yrow['userid'] > 0 && $_yrow['userid'] != $huiyuan_val['userid']){
				if ($huiyuan_val['userid'] > 0){
					$_zuozhe = '<a href="index.php?m=huiyuan&amp;c=ziliao&amp;userid='.$huiyuan_val['userid'].'&amp;sid={sid}">'.$huiyuan_val['nickname'].'</a>';
				}else{
					$_zuozhe = '游客';
				}
				if (!in_array($_yrow['username'], $messagearr)){
					kf_class::run_sys_func('xinxi');
					add_message($_yrow['username'], 0, '回复您的评论', $_zuozhe.'在<a href="index.php?m=neirong&amp;c=show&amp;catid='.$catid.'&amp;id='.$contentid.'&amp;sid={sid}">'.$title.'</a>回复了您的评论。');
					$is_ypl = 1;
				}
			}
			//通知作者
			if ($is_ypl != 1){
				$_rowcon = $db -> getone("select * from ".table('diy_'.$lanmudata['module'])." WHERE `id`='{$contentid}' AND `catid`='{$catid}' LIMIT 1");	
				if ($_rowcon['tongzhiwo']>0 && $_rowcon['sysadd']==0 && $_rowcon['username']!=$huiyuan_val['username']){
					if ($huiyuan_val['userid'] > 0){
						$_zuozhe = '<a href="index.php?m=huiyuan&amp;c=ziliao&amp;userid='.$huiyuan_val['userid'].'&amp;sid={sid}">'.$huiyuan_val['nickname'].'</a>';
					}else{
						$_zuozhe = '游客';
					}
					if (!in_array($_rowcon['username'], $messagearr)){
						kf_class::run_sys_func('xinxi');
						add_message($_rowcon['username'], 0, '回复您的内容-'.$title, $_zuozhe.'回复了您发布的内容<a href="index.php?m=neirong&amp;c=show&amp;catid='.$catid.'&amp;id='.$contentid.'&amp;sid={sid}">'.$title.'</a>。');
					}
				}
			}
			//统计内容评论数、最后评论时间、最后评论会员信息
			$lupdata = "reply=reply+1";
			$lupdata.= ",replytime='".SYS_TIME."'";
			$lupdata.= ",replyuid='".US_USERID."'";
			$lupdata.= ",replyname='".US_USERNAME."'";
			$db -> query("update ".table("diy_".$lanmudata['module'])." set {$lupdata} WHERE id='{$contentid}' AND catid='{$catid}'");
			//统计评论数
			$db -> query("update ".table('pinglun')." set total=total+1,square=square+1,lastupdate='".SYS_TIME."' WHERE commentid='{$commentid}'");
			//评论成功奖励
			if ($pinglun_add_point > 0 && US_USERID > 0){
				kf_class::run_sys_func('huiyuan');
				set_jiangfa(US_USERID, $pinglun_add_point, 0, '评论-'.$title);
			}
			return 1;
		}else{
			return 2;
		}
	}
	
}

/**
 * 回帖派发
 * @param $_tab 数据表
 * @param $_id 内容ID
 * @param $_tit 内容标题
 * @param $_con 内容
 * @param $_uid 回帖会员ID
 */
function to_paifa($_tab, $_id, $_tit, $_con = '', $_uid){
	global $db;
	if (empty($_con)){
		$neirongdb = $db -> getone("select * from ".table($_tab)." WHERE `id`='{$_id}'");
		$_con = $neirongdb['content'];
	}
	$_arr = to_content($_con, "type", 1);
	if ($_arr[0] != 'pai') return ;
	$_parr = $_arr[1];
	$_uarr = explode(',', $_parr[4]);
	if (in_array($_uid, $_uarr)) return ;
	if ($_parr[1] - $_parr[3] <= 0) return ;
	$_uarr[] = $_uid;
	$_painum = $_parr[1] - $_parr[3];
	$_painum = ($_painum < $_parr[2])?$_painum:$_parr[2];
	$_painums = $_parr[3] + $_painum;
	
	$_uarr = array_filter($_uarr);
	$oldcontent = "[bbs=pai]".implode("|", $_parr)."[/bbs]";
	$newcontent = "[bbs=pai]".$_parr[0]."|".$_parr[1]."|".$_parr[2]."|".$_painums."|".implode(",", $_uarr)."[/bbs]";
	$newcontent = str_replace($oldcontent, $newcontent, $_con);
	kf_class::run_sys_func('huiyuan');
	if ($_parr[0] == "point"){
		set_jiangfa($_uid, $_painum, 0, '评论-“派币奖励”'.$_tit);
		updatetable(table($_tab), array('content' => $newcontent), "id='{$_id}'");
	}elseif ($_parr[0] == "amount"){
		set_jiangfa($_uid, $_painum, 1, '评论-“派币奖励”'.$_tit);
		updatetable(table($_tab), array('content' => $newcontent), "id='{$_id}'");
	}
			
}
/**
 * 处理并返回字符串(自加入)
 *
 * @param string $content 待处理的字符串
 * @param intval $listlen 每页最大字符数。去除HTML标记后字符数
 * @return 处理后的字符串
 */
function get_neirong_page($content = '', $listlen = 1000) {
	if (!$content) return '';
	$conlen=strlen($content);   //内容长度
	$j=0;
	$listat[0]=0;
	$lookat=0;
	for ($i=1;$i<$conlen/$listlen+1;$i++){
		//echo $conlen/$listlen;
		if($lookat<$listlen)
		$lookat=$listlen;
		if($lookat>$conlen){
			$j++;
			$listat[$j]=$conlen;
			break;
		}
		$endat=strpos($content,"\n",$lookat);
		if($endat>$conlen-$listlen/5 or intval($endat)<1){
			$j++;
			$listat[$j]=$conlen;
			break;
		}else {
			$j++;
			$listat[$j]=$endat;
			$lookat=$endat+$listlen;
		}
	}
	//print page
	$pagenum=$j;  //总页数
	$page=1;
	if(empty($page) or $page<1 or $page>$pagenum) $page=1;
	$cont="";
	if($pagenum >1){
		for ($i=1;$i<$pagenum+1;$i++){
			$stag=$i-1;
			$startb=$listat[$stag];
			if($startb>0) //去除首个换行
			$startb=$startb+1;
			if ($i>1) $cont.="[page]";
			$co_s=substr($content,$startb,$listat[$i]-$startb);
			$cont.=$co_s;
		}
	}else{
		$cont=$content;
	}
	return $cont;
}
/**
 * 
 * 内容分页代码
 * @param $num 总页数
 * @param $curr_page 当前页
 * @param $pageurls 链接组
 * @param $getname 分页变量名
 * @param $showremain 显示剩余全文
 */
function get_content_pages($num, $curr_page, $pageurls, $getname = 'p', $showremain = '剩余全文') {
	if($curr_page > 1) {
		$perpage = $curr_page == 1 ? 1 : $curr_page-1;
		$multipage .= '<a href="'.$pageurls[$perpage].'">[上页]</a>';
	}
	
	if($curr_page < $num) {
		$multipage .= ' <a href="'.$pageurls[$curr_page+1].'">[下页]</a>';
	}
	if($curr_page < $num - 1) {
		if($showremain) {
			if ($pageurls['now']){
				$multipage .=" <a href='".$pageurls['now']."&amp;l=s'>{$showremain}</a>";
			}else{
				$multipage .=" <a href='".$pageurls[$curr_page]."&amp;l=s'>{$showremain}</a>";
			}
		}
	}
	$multipage .='<br/>第'.$curr_page.'/'.$num.'页<br/>';
	
	if ($_GET['vs']=='1'){
		$multipage.= '<input name="'.$getname.fenmiao().'" format="*N" maxlength="10" size="5" value="'.intval($curr_page+1).'" emptyok="true" />
		<anchor title="跳到该页">跳到该页
			<go href="'.$pageurls[$curr_page].'" method="post">
				<postfield name="'.$getname.'" value="$('.$getname.fenmiao().')" />
			</go>
		</anchor>';
	}else{
		$multipage.= '<form name="frm_'.$getname.fenmiao().'" method="post" action="'.$pageurls[$curr_page].'">
			<input name="'.$getname.'" type="text" value="'.intval($curr_page+1).'" style="width: 45px;" />
			<input name="submit_jump" type="submit" value="跳到该页" class="but" emptyok="true" />
		</form>';
	}
	return $multipage;
}
/**
 * 内容处理
 * @param $content
 */
function ubb_neirong($content) {
	global $lanmudata;
	$pre_i = 0;
	$pre_arr = array();
	if (strpos($content,"[/pre]") !== false){
		preg_match_all("/\[pre\](.+?)\[\/pre\]/uis", $content, $contentmatch);
		$contentmatch = $contentmatch[1];
		if (!empty($contentmatch)){
			foreach ($contentmatch as $_contentv) {
				$tmpcontent = str_replace("<br />", "{:KF:br /}", $_contentv);
				$tmpcontent = htmlspecialchars($tmpcontent);
				if ($_GET['vs'] > 1){
					$tmpcontent = "<div class=\"html-pre\">".$tmpcontent."</div>";
				}
				$tmpcontent = str_replace("{:KF:br /}", "<br />", $tmpcontent);
				$pre_i++;
				$pre_arr[$pre_i] = $tmpcontent;
				$content = str_replace("[pre]".$_contentv."[/pre]", "{:KF:pre-{$pre_i}}", $content);
			}
		}
	}
	if (strpos($content,"[/code]") !== false){
		preg_match_all("/\[code\](.+?)\[\/code\]/uis", $content, $contentmatch);
		$contentmatch = $contentmatch[1];
		if (!empty($contentmatch)){
			foreach ($contentmatch as $_contentv) {
				$tmpcontent = str_replace("<br />", "{:KF:br /}", $_contentv);
				$tmpcontent = htmlspecialchars($tmpcontent);
				if ($_GET['vs'] > 1){
					$tmpcontent = "<div class=\"html-pre\">".$tmpcontent."</div>";
				}
				$tmpcontent = str_replace("{:KF:br /}", "<br />", $tmpcontent);
				$pre_i++;
				$pre_arr[$pre_i] = $tmpcontent;
				$content = str_replace("[code]".$_contentv."[/code]", "{:KF:pre-{$pre_i}}", $content);
			}
		}
	}
	//
	$content = str_replace("<br>","[br]",$content);
	$content = preg_replace("/<br(.+?)>/is","[br]",$content);
	$content = wml_strip($content);
	//
	if ($lanmudata['modelid'] > 0){
		$modelarr = getcache('caches/model/model_field_'.$lanmudata['modelid'].'.cache.php');
		$ziduanarr = $modelarr['content'];
		if ($ziduanarr['nrbiaoqian'] == 'ubb') {
			kf_class::run_sys_func('ubb');
			$content = ubb($content);
		}elseif ($ziduanarr['nrbiaoqian'] == 'wml') {
			kf_class::run_sys_func('ubb');
			$content = wml($content);
		}elseif ($ziduanarr['nrbiaoqian'] == 'htmlspecialchars') {
			$content = htmlspecialchars($content);
		}elseif ($ziduanarr['nrbiaoqian'] == 'strip_tags') {
			$content = strip_tags($content);
		}elseif ($ziduanarr['tu_kuan'] > 0 || $ziduanarr['tu_gao'] > 0) {
			$content = content_strip($content, '浏览大图', $ziduanarr['tu_kuan'], $ziduanarr['tu_gao']);
		}
	}
	$content = str_replace("[br]","<br/>",$content);
	$content = preg_replace("/&amp;(\w+);/is","&\\1;",$content);
	if ($_GET['vs'] == 1) $content = str_replace("$","$$",$content);
	$content = trim($content);  //去除首尾空
	$content = mb_ereg_replace('^(<br\/>)+', '', $content); //去首换行
	$content = mb_ereg_replace('(<br\/>)+$', '', $content); //去尾换行
	if ($pre_i > 0) {
		for ($i = 1; $i <= $pre_i; $i++) {
			$content = str_replace("{:KF:pre-{$i}}", $pre_arr[$i], $content);
		}
	}
	return $content;
}
/**
 * 过滤内容为wml格式
 */
function wml_strip($string) {
    $string = str_replace(array('&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;', '&'), array(' ', '&', '"', "'", '“', '”', '—', '{<}', '{>}', '·', '…', '&amp;'), $string);
	return str_replace(array('{<}', '{>}'), array('&lt;', '&gt;'), $string);
}
/**
 * 内容中图片替换
 */
function content_strip($content, $ishow='浏览大图', $width = 100, $height = 100) {
   $content = preg_replace('/<img[^>]*src=[\'"]?([^>\'"\s]*)[\'"]?[^>]*>/ies', "wap_img('$1', $width, $height, $ishow)", $content);
   //匹配替换过的图片
   //$content = strip_tags($content,'<br/><img><a>');
   return $content;
}
/**
 * 图片过滤替换
 */
function wap_img($url, $width = 100, $height = 100, $ishow = '浏览大图') {
	if($ishow) $show_tips = '<br/><a href="'.$url.'" target="_blank">'.$ishow.'</a>';
	return '<img src="'.img_thumb($url, $width, $height, 1).'" />'.$show_tips;
}
/**
 * 生成缩略图函数
 * @param  $imgurl 图片路径
 * @param  $width  缩略图宽度
 * @param  $height 缩略图高度
 * @param  $autocut
 * @param  $smallpic 无图片是默认图片路径
 */
function thumb($imgurl, $width = 100, $height = 100 ,$autocut = 1, $smallpic = 'nopic.gif') {
	return img_thumb($imgurl, $width, $height, $autocut, $smallpic);
}
/**
 * 内容处理(图片分页)
 * 获取文章指定页，如果指定页等于最后页且最后页小于文章总页数则返回指定页内容及之后的文章内容。
 * @param $_content 内容
 * @param $_page 指定页
 * @param $_allpage 最后页
 */
function page_neirong($_content, $_page = 0, $_allpage = 0){
	if (is_array($_content)) {
		$_page = intval($_page)?$_page:1;
		$_text = $_content[$_page - 1];
		//最后一页列出全部文章内容
		if ($_page == $_allpage && $_allpage){
			foreach ($_content as $_k => $_v) {
				if ($_k < $_page) continue;
				$_text.= $_v;
			}
		}
	}else{
		$_text = $_content;
	}
	//
	if (empty($_text)) return "暂无介绍";
	$_text = ubb_neirong($_text); //内容处理
	return $_text;
}
/**
 * 获取附件列表
 * @param $type 附件表名
 * @param $is_page 分页显示：0不分页; 大于0时每页显示值。默认0
 */
function wenjian($type, $is_page = 0){
	global $db,$neirongdb,$lanmudata,$_CFG;
	if (empty($lanmudata) || empty($neirongdb)) return array();
	$modelfield = getcache(KF_ROOT_PATH.'caches/model/model_field_'.$lanmudata['modelid'].'.cache.php');
	$modelfield = $modelfield[$type];
	$modelfieldset = string2array($modelfield['setting']);
	$page_name = "p-".$modelfield['id'];
	if ($modelfieldset['pathlist']){
		$_file_allowext = $modelfieldset['upload_allowext'];
		if ($modelfield['type'] == 'image') $_file_allowext = "gif|jpg|jpeg|png|bmp";
		if ($modelfield['type'] == 'downfile' && empty($_file_allowext)) $_file_allowext = "rar|zip|jar|apk|7z";
		$_file_allowext = str_replace('|', ',', $_file_allowext);
		$_url = "uploadfiles/content/{$neirongdb['catid']}/{$neirongdb['id']}/";
		$_path = KF_ROOT_PATH.str_replace('/', DIRECTORY_SEPARATOR, $_url);
		$_row = glob($_path . '*.{'.$_file_allowext.'}',GLOB_BRACE);
		
		$total_count = count($_row);
		$all_page = 1; $_start = 0;
		if ($is_page){
			$now_page = $_GET[$page_name];
			if ($now_page < 1) $now_page = 1;
			$all_page = $total_count/$is_page;
			if ($all_page > intval($all_page)){
				$all_page = intval($all_page) + 1;
			}else{
				$all_page = intval($all_page);
			}
			$_start = abs(($now_page-1)*$is_page);
		}
		$_rowarr = array(); $_n = $_j = 0;
		foreach ($_row as $_val) {
			if ($_j >= $is_page && $is_page) break;
			if ($_n >= $_start){
				$_arr = array();
				$_arr['name'] = basename($_val);
				$_arr['name'] = iconv('gb2312', 'utf-8', $_arr['name']);
				$_arr['size'] = formatBytes_neirong(filesize($_val));
				$_arr['format'] = pathinfo($_val);
				$_arr['format'] = $_arr['format']['extension'];
				$_arr['url'] = './'.$_CFG['site_dir'].$_url.urlencode($_arr['name']);
				$_arr['downurl'] = $_arr['allurl'] = $_CFG['site_domain'].$_CFG['site_dir'].$_url.urlencode($_arr['name']);
				$_rowarr[$_j] = $_arr;
				$_j++;
			}
			$_n++;
		}
	}else{
		if (empty($neirongdb[$type])) return array();
		$downloadtype = $modelfieldset['downloadtype'];
		$_wheresql = " commentid='".$lanmudata['module']."_".$lanmudata['modelid']."_".$neirongdb['catid']."_".$neirongdb['id']."' AND field='{$type}'";
		$total_sql = "SELECT COUNT(*) AS num FROM ".table('neirong_fujian')." WHERE ".$_wheresql;
		$total_count = $db -> get_total($total_sql);
		$limit = "";
		$all_page = 1;
		if ($is_page){
			$now_page = $_GET[$page_name];
			if ($now_page < 1) $now_page = 1;
			$limit = " LIMIT ".abs(($now_page-1)*$is_page).','.$is_page;
			$all_page = $total_count/$is_page;
			if ($all_page > intval($all_page)){
				$all_page = intval($all_page) + 1;
			}else{
				$all_page = intval($all_page);
			}
		}
		$result = $db -> query("select * from ".table('neirong_fujian')." WHERE {$_wheresql} ORDER BY addtime ASC{$limit}");
		$_rowarr = array();
		while($_row = $db->fetch_array($result)){
			if ($downloadtype == '0'){
				$_row['downurl'] = $_row['allurl'];
			}else{
				$_row['downurl'] = get_link('c').'&amp;c=xiazai&amp;xid='.$_row['id'];
			}
			$_row['size'] = formatBytes_neirong($_row['size']);
			$_rowarr[$_row['id']] = $_row;
		}
	}
	
	if (empty($_rowarr)){
		return array();
	}else{
		$__pagelist = '';
		if ($total_count > $is_page && $is_page){
			kf_class::run_sys_class('page','',0);
			$_pagelist = new page(array('total'=>$total_count,'perpage'=>$is_page,'getarray'=>$_GET,'page_name'=>$page_name));
			$__pagelist = $_pagelist -> show('fujian');
		}
		return array(
			'count'=>$total_count, //总数
			'page'=>$now_page, //当前页
			'cutpage'=>$is_page, //每页显示
			'allpage'=>$all_page, //总页数
			'pagelist'=>$__pagelist, //分页
			'pagename'=>$page_name, //分页变量名
			'list'=>$_rowarr, //附件列表
		);
	}
}
// 文件大小单位转换GB MB KB
function formatBytes_neirong($size, $type = 0) {
	if ($type) return _formatSize_neirong($size);
	$units = array(' B', ' KB', ' MB', ' GB', ' TB');
	for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
	return round($size, 2).$units[$i];
}
function _formatSize_neirong($filesize) {
	if($filesize >= 1073741824) {
		$filesize = round($filesize / 1073741824 * 100) / 100 . ' GB';
	} elseif($filesize >= 1048576) {
		$filesize = round($filesize / 1048576 * 100) / 100 . ' MB';
	} elseif($filesize >= 1024) {
		$filesize = round($filesize / 1024 * 100) / 100 . ' KB';
	} else {
		$filesize = $filesize . ' Bytes';
	}
	return $filesize;
}
/**
 * 检查支付状态
 * @param 标识 $commentid
 * @param 重复收费 $repeatchargedays
 */
function check_payment($commentid, $repeatchargedays) {
	global $db;
	if(US_USERID < 1) return false;
	if($repeatchargedays < 1) $repeatchargedays = 1;
	$fromtime = SYS_TIME - 86400 * $repeatchargedays; 
	$_row = $db -> getone("select * from ".table("neirong_zhifu")." WHERE `commentid`='{$commentid}' AND userid='".US_USERID."'  AND `time`>{$fromtime} LIMIT 1");
	if (!empty($_row)){
		return true;
	}else{
		return false;
	}
}
/**
 * 添加阅读支付记录
 * @param 标识 $commentid
 * @return 支付记录ID，0标识添加失败
 */
function yueduzhifu($commentid) {
	global $db;
	if(US_USERID < 1) return 0;
	$_row = $db -> getone("select * from ".table("neirong_zhifu")." WHERE `commentid`='{$commentid}' AND userid='".US_USERID."' LIMIT 1");
	if (!empty($_row)){
		return $_row['id'];
	}else{
		$zhifuid = inserttable(table('neirong_zhifu'), array('userid'=>US_USERID, 'commentid'=>$commentid,), true);
		if ($zhifuid >0){
			return $zhifuid;
		}else{
			return 0;
		}
	}
}
/**
 * 检查阅读权限
 * @return -1未登录无权限，-2登录无权限 ，1权限通过 ，0未知错误
 */
function category_priv() {
	global $lanmudata,$huiyuan_val;
	if (empty($lanmudata)) return 0;
	$visitarr = string2array($lanmudata['visit']);
	$_groupid = intval($huiyuan_val['groupid']);
	if ($_groupid==0) $_groupid = 8;
	if ($visitarr[$_groupid]){
		if ($_groupid == 8){
			return -1; //未登录无权限
		}else{
			return -2; //登录无权限
		}
	}else{
		return 1;
	}
}

/**
 * 获取上一遍内容url
 * Enter description here ...
 */
function shangpian($amp = ''){
	global $db,$neirongdb,$lanmudata;
	if (empty($lanmudata) || empty($neirongdb)) return "";
	$_wheresql = "catid='{$neirongdb['catid']}' AND id < {$neirongdb['id']}";
	$_row = $db -> getone("select * from ".table("diy_".$lanmudata['module'])." WHERE status=1 AND {$_wheresql} ORDER BY id DESC LIMIT 1");
	if (empty($_row)) return "";
	$_urlarr = array(
			'm'=>'neirong',
			'c'=>'show',
			'catid'=>$_row['catid'],
			'id'=>$_row['id'],
			'sid'=>$_GET['sid'],
			'vs'=>$_GET['vs'],
	);
	$_url = url_rewrite("KF_neirongshow", $_urlarr);
	if ($amp){
		$_url = str_replace("&amp;", "&", $_url);
		$_url = str_replace("&", $amp, $_url);
	}
	return $_url;
	}
/**
 * 获取下一篇内容url
 * Enter description here ...
 */
function xiapian($amp = ''){
	global $db,$neirongdb,$lanmudata;
	if (empty($lanmudata) || empty($neirongdb)) return "";
	$_wheresql = "catid='{$neirongdb['catid']}' AND id > {$neirongdb['id']}";
	$_row = $db -> getone("select * from ".table("diy_".$lanmudata['module'])." WHERE status=1 AND {$_wheresql} ORDER BY id ASC LIMIT 1");
	if (empty($_row)) return "";
	$_urlarr = array(
			'm'=>'neirong',
			'c'=>'show',
			'catid'=>$_row['catid'],
			'id'=>$_row['id'],
			'sid'=>$_GET['sid'],
			'vs'=>$_GET['vs'],
	);
	$_url = url_rewrite("KF_neirongshow", $_urlarr);
	if ($amp){
		$_url = str_replace("&amp;", "&", $_url);
		$_url = str_replace("&", $amp, $_url);
	}
	return $_url;
}
/**
 * 处理、提取 内容
 * @param $_str
 * @param $_type 
 */
function to_content($_str, $_type = '', $_type2 = '', $newline2br = 0){
	if (empty($_str)) return ;
	if (!empty($_type)){
		if ($_type == 'type'){
			preg_match("/\[bbs=(.+?)\](.+?)\[\/bbs\]/is", $_str, $_match);
			if (!empty($_type2)){
				return array('0'=>$_match[1], '1'=>explode("|", $_match[2]));
			}
		}else{
			preg_match("/\[bbs={$_type}\](.+?)\[\/bbs\]/is", $_str, $_match);
		}
		return !empty($_match)?$_match[1]:"";
	}
	return empty($newline2br)?br2nl(to_content_bbs($_str)):to_content_bbs($_str);
}
function to_content_bbs($_str){
	if (empty($_str)) return ;
	if (strpos($_str,"[/bbs]")){
		$_str = preg_replace("/\[bbs=(.+?)\](.+?)\[\/bbs\]/is", "", $_str);
	}
	return $_str;
}
/**
 * 反nl2br
 * @param $text
 */
function br2nl($text) {
	return preg_replace('/<br\\s*?\/??>/i', '', $text);
}
/** 
 * 重置副标题
 * @param $id 数据ID
 * @param $table 数据表名称
 */
function re_subtitle($id, $table){
	global $db,$_CFG;
	if (empty($id) || empty($table)) return;
	$neirongdb = $db -> getone("select * from ".table("diy_".$table)." WHERE `id`={$id}");
	$subtitle = trim(str_replace('组', '', $neirongdb['subtitle']));
	$subtitle = trim(str_replace('附', '', $subtitle));
	$subtitle = trim(str_replace('图', '', $subtitle));
	if (!empty($neirongdb['thumb'])){
		$subtitle.= ' 图 ';
		$subtitle = trim($subtitle);
	}
	$columnarr = getcache('caches/column/cache.'.$_CFG['site'].'.php');
	$lanmudata = $columnarr[$neirongdb['catid']];
	$commentid = "commentid='".$lanmudata['module']."_".$lanmudata['modelid']."_".$neirongdb['catid']."_".$neirongdb['id']."'";
	$arr = $db->getall("SELECT * FROM ".table('neirong_fujian')." WHERE {$commentid}");
	foreach($arr as $_val){
		$subtitle = to_subtitle($_val['allurl'], $subtitle, 1);
	}
	$db -> query("update ".table("diy_".$table)." set `subtitle`='".$subtitle."' WHERE id=".$neirongdb['id']."");
}
/**
 * 副标题处理
 * @param $str 要添加的字
 * @param $former 原本的字
 * @param $former 1自动判断$str图片还是附件($str为文件地址)，2自动判断$str是否包含图片或附件($str为内容文字)
 */
function to_subtitle($str, $former, $strauto = 0){
	//自动判断
	if ($strauto == 1){
		kf_class::run_sys_func('upload');
		$str = get_extension($str);
		if ($str == "gif" || $str == "jpg" || $str == "bmp" || $str == "png" || $str == "jpeg"){
			$str = "图";
		}else{
			$str = "附";
		}
	}elseif ($strauto == 2){
		$str = stripcslashes($str);
		preg_match('/<img.+src=[\"|\']?(.+\.(jpg|gif|bmp|bnp|png))[\"|\']?.+>/i', $str, $_match);
		if(!empty($_match)){
			$former = to_subtitle("图", $former);
		}
		preg_match('/<a.+href=[\"|\']?(.+\.(chm|pdf|zip|rar|tar|gz|bzip2|apk|txt|doc|xls|ppt))[\"|\']?.+>/i', $str, $_match);
		if(!empty($_match)){
			$former = to_subtitle("附", $former);
		}
		return $former;
	}
	$formerarr = explode(" ", $former);
	$_array = array();
	foreach ($formerarr as $_v){
		$_array[$_v] = $_v;
	}
	if ($str == "组"){
		unset($_array['图']);
		unset($_array['附']);
		$_array[$str] = $str;
	}elseif ($str == "图"){
		if ($_array['附']){
			unset($_array['图']);
			unset($_array['附']);
			$_array['组'] = '组';
		}elseif ($_array['组']){
			unset($_array['图']);
		}else{
			$_array[$str] = $str;
		}
	}elseif ($str == "附"){
		if ($_array['图']){
			unset($_array['附']);
			unset($_array['图']);
			$_array['组'] = '组';
		}elseif ($_array['组']){
			unset($_array['附']);
		}else{
			$_array[$str] = $str;
		}
	}else{
		$_array[$str] = $str;
	}
	$_array = array_filter($_array);
	return implode(" ",$_array);
}

/**
 * 添加到全站搜索、修改已有内容
 * @param $contentid 内容ID
 * @param $catid 栏目ID
 * @param $modelid 模型ID
 * @param $title 标题
 * @param $data 添加分词
 * @param $text 不分词的文本
 * @param $description 介绍文本
 * @param $adddate 添加时间
 * @param $site 站点
 */
function install_search($contentid ,$catid , $modelid, $title, $data = '', $text = '', $description = '',$adddate = 0, $site = 1, $status = 1) {
	global $db;
	kf_class::run_sys_class('segment','',0);
	$segment = new segment();
	//分词结果
	$fulltext_data = $segment->get_keyword($segment->split_result($data));
	$fulltext_data = $text.' '.$fulltext_data;
	kf_class::run_sys_func('pinyin');
	$fulltext_data_pinyin = cn2pinyin($fulltext_data, CHARSET, 1);
	$_data = array();
	$_data['title'] = $title;
	$_data['data'] = $fulltext_data;
	$_data['data_pinyin'] = $fulltext_data_pinyin;
	$_data['description'] = $description;
	if ($adddate) $_data['adddate'] = $adddate;
	$_data['status'] = $status;
	$where = '1=1';
	$where.= $contentid?" AND contentid='$contentid'":"";
	$where.= $catid?" AND catid='$catid'":"";
	$where.= $modelid?" AND modelid='$modelid'":"";
	$where.= $site?" AND site='$site'":"";
	$sql = "select * from ".table('sousuo')." where {$where} LIMIT 1";
	$r = $db->getone($sql);
	if($r) {
		$_id = $r['id'];
		updatetable(table('sousuo'), $_data, $where);
	} else {
		if ($contentid) $_data['contentid'] = $contentid;
		if ($catid) $_data['catid'] = $catid;
		if ($modelid) $_data['modelid'] = $modelid;
		if ($site) $_data['site'] = $site;
		$_id = inserttable(table('sousuo'), $_data, true);
	}
	return $_id;
}

/**
 * 
 * 修改内容审核状态
 * @param $_table 		数据表名称
 * @param $_status 		修改为的状态
 * @param $_id 			内容ID
 * @param $_admin 		后台修改
 */
function set_shenhe($_table, $_status, $_id, $_admin=''){
	global $db;
	$row = $db -> getone("select * from ".table($_table)." WHERE `id`={$_id} LIMIT 1");
	//判断是否会员添加的
	if ($row['sysadd']) return '';
	//读取栏目信息
	$_data = getcache(KF_ROOT_PATH.'caches/column/cache.'.$row['site'].'.php');
	$_data = $_data[$row['catid']];
	$_setting = string2array($_data['setting']);
	//读取发布信息
	$_fabu = $db -> getone("select * from ".table('neirong_fabu')." WHERE `checkid`='c-{$row['id']}-{$_data['modelid']}' AND `catid`={$row['catid']} LIMIT 1");
	//第一次设置通过审核
	$updataval = "`checktime`='".SYS_TIME."',`status`='".$_status."'";
	if (empty($_fabu['yitongguo']) && $_status == 1){
		kf_class::run_sys_func('huiyuan');
		$updataval.= ",`yitongguo`=1";
		$_huiyuan = $db -> getone("select * from ".table('huiyuan')." WHERE `username`='{$row['username']}' LIMIT 1");
		//帖子处理
		$rowdata = $db -> getone("select `id`,`content` from ".table($_table."_data")." WHERE `id`={$_id} LIMIT 1");
		set_shenhe_bbs($row['title'], $rowdata['content'], $_huiyuan);
		//奖励积分
		if (empty($_setting['presentpoint']) && $_setting['presentpoint']!='0') $_setting['presentpoint'] = 1;
		if(intval($_setting['presentpoint'])) {
			set_jiangfa($_huiyuan['userid'], $_setting['presentpoint'], 0, "投稿“{$_fabu['title']}”通过审核");
		}
	}
	//更新内容状态
	$db -> query("update ".table($_table)." set `status`='".$_status."' WHERE `id`={$_id}");
	//更新搜索状态
	$db -> query("update ".table('sousuo')." set `status`='".$_status."' WHERE `catid`={$row['catid']} AND `contentid`={$_id}");
	//更新审核信息
	$db -> query("update ".table('neirong_fabu')." set {$updataval} WHERE `checkid`='{$_fabu['checkid']}'");
	if ($_admin){
		admin_log("修改了审核状态。{$_fabu['checkid']} , {$updataval}", $_admin);
	}
}
/**
 * 帖子类型处理 派币贴、悬赏贴
 * @param $_title  内容标题
 * @param $_content  内容
 * @param $_huiyuan  会员信息组
 */
function set_shenhe_bbs($_title, $_content, $_huiyuan){
	global $_CFG;
	if (empty($_huiyuan)) return ; //会员不存在
	//派币贴处理
	$_paiarr =  explode("|", to_content($_content, "pai"));
	$_paiarr[1] = abs($_paiarr[1]);
	if ($_paiarr[0] == "point"){
		$_paiarr[1] = intval($_paiarr[1]);
		if ($_paiarr[1] > 0){
			if ($_huiyuan['point'] < $_paiarr[1]){
				showmsg('系统提醒','会员积分不足此内容的派币总额！');
			}else{
				set_jiangfa($_huiyuan['userid'], $_paiarr[1]*-1, 0, "投稿“[派币贴]{$_title}”通过审核");
			}
		}
	}elseif ($_paiarr[0] == "amount"){
		if ($_paiarr[1] >= 0.01){
			if ($_huiyuan['amount'] < $_paiarr[1]){
				showmsg('系统提醒','会员'.$_CFG['amountname'].'不足此内容的派币总额！');
			}else{
				set_jiangfa($_huiyuan['userid'], $_paiarr[1]*-1, 1, "投稿“[派币贴]{$_title}”通过审核");
			}
		}
	}
	//悬赏贴处理
	$_xuanarr =  explode("|", to_content($_content, "xuan"));
	$_xuanarr[1] = abs($_xuanarr[1]);
	if ($_xuanarr[0] == "point"){
		$_xuanarr[1] = intval($_xuanarr[1]);
		if ($_xuanarr[1] > 0){
			if ($_huiyuan['point'] < $_xuanarr[1]){
				showmsg('系统提醒','会员积分不足此内容的悬赏金额！');
			}else{
				set_jiangfa($_huiyuan['userid'], $_xuanarr[1]*-1, 0, "投稿“[悬赏贴]{$_title}”通过审核");
			}
		}
	}elseif ($_xuanarr[0] == "amount"){
		if ($_xuanarr[1] >= 0.01){
			if ($_huiyuan['amount'] < $_xuanarr[1]){
				showmsg('系统提醒','会员'.$_CFG['amountname'].'不足此内容的悬赏金额！');
			}else{
				set_jiangfa($_huiyuan['userid'], $_xuanarr[1]*-1, 1, "投稿“[悬赏贴]{$_title}”通过审核");
			}
		}
	}
}
/**
 * 
 * 添加收藏
 * @param $_title 收藏标题
 * @param $_url 收藏链接
 */
function set_shoucang($_title, $_url){
	global $db;
	require(KF_INC_PATH.'denglu.php');
	if (is_array($_url)){
		ksort($_url);
		$url = get_link('vs|sid', '&', '', $_url);
	}else{
		$url = goto_url($_url, 'vs|sid', 1, '&');
	}
	$_url_arr = array(
		'userid'=>US_USERID,
		'title'=>$_title,
		'url'=>$url,
		'adddate'=>SYS_TIME,
	);
	$_row = $db -> getone("select * from ".table('shoucang')." WHERE url='{$url}' AND userid='".US_USERID."' LIMIT 1");	
	if (empty($_row)){
		inserttable(table('shoucang'), $_url_arr);
	}else{
		updatetable(table('shoucang'), $_url_arr, "id='{$_row['id']}'");
	}
}
/**
 * 返回楼层
 * @param $_i
 */
function lou($_i){
	$_t = "";
	switch ($_i) {
		case 0:
			$_t = "沙发";
			break;
		case 1:
			$_t = "椅子";
			break;
		case 2:
			$_t = "板凳";
			break;
		case 3:
			$_t = "地板";
			break;
		default:
			$_t = intval(($_i + 1))."楼";
			break;
	}
	return $_t;
}
/**
 * 论坛副标题分割
 * @param $_str 副标题
 * @param $_A 前字符，默认“【”
 * @param $_B 后字符，默认“】”
 */
function subt($_str, $_A = '【', $_B = '】'){
	$_t = "";
	if (!empty($_str)){
		$_strarr = explode(" ", $_str);
		foreach ($_strarr as $_val) {
			$_t.= $_val?$_A.$_val.$_B:"";
		}
	}
	return $_t;
}

/**
 * 获取评论内容
 * @param $_id
 */
function get_pl($_id, $_ziduan = 'content'){
	global $db,$_CFG;
	$row = $db -> getone("select * from ".table("pinglun_data_".$_CFG['site'])." WHERE id='{$_id}' LIMIT 1");
	if (!empty($row)){
		return $row[$_ziduan];
	}else{
		return "";
	}
}

/**
 * 获取隐藏内容
 * @param $_id 内容ID
 * @param $_catid 内容栏目ID
 * @param $_username 发布内容的会员帐号
 * @param $_yin 隐藏内容
 */
function get_yin($_id, $_catid, $_username, $_yin){
	global $db,$_CFG;
	if (US_USERID < 1) return "<u>查看隐藏内容请先登录。</u>";
	if ($_username == US_USERNAME) return "<u>隐藏内容内容不对作者隐藏</u><br/>".ubb_neirong($_yin);
	$_wheresql = " WHERE `commentid`='neirong_{$_catid}_{$_id}_{$_CFG['site']}' AND `userid`='".US_USERID."'";
	$_total_count = $db -> get_total("SELECT COUNT(*) AS num FROM ".table("pinglun_data_".$_CFG['site']).$_wheresql);
	if ($_total_count > 0){
		return ubb_neirong($_yin);
	}else{
		return "<u>回复后可查看隐藏内容。</u>";
	}
}

/**
 * 判断收费贴
 * @param $_arr
 * @param $_username 发布内容的会员帐号
 */
function get_mai($_arr, $_username){
	if (US_USERID < 1) return "<u>查看收费内容请先登录。</u>";
	if ($_username == US_USERNAME) return "<u>收费内容不对作者收费</u><br/>".ubb_neirong($_arr[2]);
	$_uarr = explode(",", $_arr[3]);
	if (in_array(US_USERID, $_uarr)){
		return ubb_neirong($_arr[2]);
	}else{
		return "收费:".$_arr[1].money_type($_arr[0])." <a href=\"".get_link('c')."&amp;c=mai\">点击购买</a>";
	}
}
/**
 * 支付后 收费贴
 * @param $_tab
 * @param $_id
 * @param $_userid
 */
function add_mai($_tab, $_id, $_userid){
	global $db;
	$_row = $db -> getone("select * from ".table($_tab)." WHERE `id`='{$_id}' LIMIT 1");
	if (empty($_row)) return;
	$_type = to_content($_row['content'], "mai");
	if (empty($_type)) return;
	$_arr = explode("|", $_type);
	$_uarr = explode(",", $_arr[3]);
	$_uarr[] = $_userid;
	$_uarr = array_filter($_uarr);
	
	$oldcontent = "[bbs=mai]".$_type."[/bbs]";
	$newcontent = "[bbs=mai]".$_arr[0]."|".$_arr[1]."|".$_arr[2]."|".implode(",", $_uarr)."[/bbs]";
	$newcontent = str_replace($oldcontent, $newcontent, $_row['content']);
	updatetable(table($_tab), array('content' => $newcontent), "id='{$_id}'");
}
/**
 * 栏目名称转英文(拼音)
 * @param $txt
 */
function pinyinlanmu($txt){
    if (is_numeric($txt) && $txt == 0){
        $txt_pinyin = generate_password(8,'21');
    }else{
        kf_class::run_sys_func('pinyin');
        $txt_pinyin = cn2pinyin($txt, CHARSET, 1);
        if (empty($txt_pinyin)) $txt_pinyin = generate_password(8,'21');
    }
    return strtolower($txt_pinyin);
}
/**
 * 删除评论附件
 * @param $str 评论内容
 */
function delplfile($str){
	if (empty($str)) return;
	$str = preg_replace("/\<div class=\"yuanpinglun\"\>(.+?)\<\/div\>/is","",$str);
	preg_match_all('/<a[^>]+href="uploadfiles\/content\/review\/?([^>"]+)"?\s*[^>]*>(.+?)<\/a>/i', $str, $strmatch);
	$strmatch = $strmatch[1];
	if (!empty($strmatch)){
		foreach ($strmatch as $_k => $_v) {
			@unlink('uploadfiles/content/review/'.$_v);
		}
	}
}
/**
 * 回复对话清除
 */
function set_yuanpinglun($str, $baoliu = 3, $count = 0){
	global $db;
	if (empty($str)) return "";
	if (strpos($str, '<div class="yuanpinglun">') !== false && $baoliu > 0){
		$_arr = explode('<div class="yuanpinglun">', $str);
		$_cou = count($_arr);
		if ($_cou > $baoliu){
			$_str = "";$_i = 1;
			for ($i=0;$i<$_cou;$i++) {
				if ($i+1 == $_cou){
					$_str.= $_arr[$i];
				}elseif ($i+2 == $_cou){
					$_str.= $_arr[$i].'<div class="yuanpinglun" data-num="last">';
				}else{
					$_str.= $_arr[$i].'<div class="yuanpinglun">';
				}
			}
			if ($_cou > $baoliu+1 && $count < 50){
				$str = preg_replace("/<div class=\"yuanpinglun\" data-num=\"last\">(.+?)<\/div>/is", "", $_str);
				return set_yuanpinglun($str, $baoliu, $count+1);
			}else{
				return preg_replace("/<div class=\"yuanpinglun\" data-num=\"last\">(.+?)<\/div>/is", "<div class=\"yuanpinglun\">......</div>", $_str);
			}
		}
	}
	if ($db) {
		$strescape = mysql_real_escape_string($str);
		if ($strescape) $str = $strescape;
	}
	return $str;
}
/**
 * 回复提醒
 */
function set_mention($str, $catid, $contentid, $title){
	global $db,$huiyuan_val;
	$_arr = array();
	if (empty($str)) return $_arr;
	if (strpos($str, '@') !== false){
		preg_match_all('/@(.*?) /', $str.' ', $rmatch);
		$rmatch0 = $rmatch[0];
		$rmatch1 = $rmatch[1];
		if (!empty($rmatch1)){
			foreach ($rmatch1 as $_k => $_user) {
				$row = $db -> getall("select * from ".table("huiyuan")." WHERE `nickname`='{$_user}'");
				foreach ($row as $val) {
					//提醒会员
					if (!in_array($val['username'], $_arr)){
						$_arr[$val['userid']] = $val['username'];
						if ($huiyuan_val['userid'] > 0){
							$_zuozhe = '<a href="index.php?m=huiyuan&amp;c=ziliao&amp;userid='.$huiyuan_val['userid'].'&amp;sid={sid}">'.$huiyuan_val['nickname'].'</a>';
						}else{
							$_zuozhe = '游客';
						}
						kf_class::run_sys_func('xinxi');
						add_message($val['username'], 0, '评论里提到你', $_zuozhe.'在<a href="index.php?m=neirong&amp;c=show&amp;catid='.$catid.'&amp;id='.$contentid.'&amp;sid={sid}">'.$title.'</a>的评论里提到你。');
					}
				}
			}
		}
	}
	return $_arr;
}
/**
 * 替换表情
 */
function set_em($strcon){
	if (strpos($strcon, '[/') !== false){
		$strcon = preg_replace("/\[\/(.+?)\]/es", "get_em('\\1',0,1)", $strcon);
	}
	return $strcon;
}
/**
 * 获取表情
 */
function get_em($str, $img = 0, $judge = 0){
	global $_CFG;
	if ($img){
		$_restr = $_CFG['site_dir'].'uploadfiles/content/em/'.md5($str).'.gif';
	}else{
		$_restr = '<img src="'.$_CFG['site_dir'].'uploadfiles/content/em/'.md5($str).'.gif" alt="'.$str.'"/>';
	}
	if ($judge){
		if (!file_exists(KF_ROOT_PATH.'uploadfiles/content/em/'.md5($str).'.gif')) {
			$_restr = "[/{$str}]";
		}
	}
	return $_restr;
}
?>