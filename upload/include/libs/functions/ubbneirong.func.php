<?php
/**
 * ubb内容函数
 */
function ubb_lanmu($set) {
	global $_CFG;
	$setarr = explode('||', $set);
	$_setarr = array();
	$_setarr['title'] = $setarr[0];
	$_setarr['body'] = $setarr[1];
	$_setarr['cut'] = $setarr[2];
	$_setarr['dot'] = $setarr[3];
	$_setarr['template'] = $setarr[4];
	
	$_reture = "";
	$_data = getcache(KF_ROOT_PATH.'caches/column/cache.'.$_CFG['site'].'.php');
	$_body_arr = $_data[$_setarr['title']];
	if (!empty($_body_arr)){
		$_temp_arr = $_setarr['template'];
		$_time_arr = array();
		if (!$_setarr['body'] && $_body_arr['arrchildid']){
			$__arr = explode(',', $_body_arr['arrchildid']);sort($__arr);
			foreach ($__arr as $_k => $_val){
				$_time_arr[$_k] = $_data[$_val];
			}
		}else{
			$_time_arr[$_setarr['title']] = $_body_arr;

		}$_n = 1;
		foreach ($_time_arr as $_k => $_val){
			if (intval($_setarr['cut'])>0) $_val['title'] = cut_str($_val['title'],intval($_setarr['cut']),0,$_setarr['dot']);
			$_val['title'] = htmlspecialchars($_val['title']);
			//$_val['url'] = get_link('vs|sid|m', '', '1').'&amp;c=neirong&amp;listid='.$_val['id'];
			$_urlarr = array(
				'm'=>'neirong',
				'c'=>'list',
				'catid'=>$_val['id'],
				'sid'=>$_GET['sid'],
				'vs'=>$_GET['vs'],
			);
			$_val['url'] = url_rewrite('KF_neironglist', $_urlarr);
			if ($_temp_arr){
                //版本显示
                if (strpos($_temp_arr,"{/wap}")!==false){
                    $_temp_arr = preg_replace("/\{wap=(.+?)\}(.+?)\{\/wap\}/es","ubb_neirong_wap('\\2','\\1',1)",$_temp_arr);
                }
				$_temp_val = $_temp_arr;
                //自定义截取标题
                if (strpos($_temp_val,"{title,")!==false){
                    $_temp_val = preg_replace("/\{title\,(.+?)\}/es","cut_str2('{$_val['title']}','\\1','{$_setarr['dot']}')",$_temp_val);
                }
				$_temp_val = str_replace('{n}',$_n,$_temp_val);
				$_temp_val = str_replace('{id}',$_val['id'],$_temp_val);
				$_temp_val = str_replace('{url}',$_val['url'],$_temp_val);
				$_temp_val = str_replace('{title}',$_val['title'],$_temp_val);
				$_reture.= $_temp_val;
			}else{
				$_reture.= "<a href=\"{$_val['url']}\">".$_val['title']."</a><br/>";
			}
			$_n++;
		}
	}
	return $_reture;
}

function ubb_liebiao($set) {
	global $db,$_CFG;
	$setarr = explode('||', $set);
	$_setarr = array();
	$_setarr['title'] = $setarr[0];
	$_setarr['body'] = $setarr[1];
	$_setarr['cut'] = $setarr[2];
	$_setarr['dot'] = $setarr[3];
	$_setarr['order'] = $setarr[4];
	$_setarr['asc'] = $setarr[5];
	$_setarr['template'] = $setarr[6];
	$_setarr['select'] = $setarr[7];
	
	$_reture = "";
	$_data = getcache(KF_ROOT_PATH.'caches/column/cache.'.$_CFG['site'].'.php');
	$_body_arr = $_data[$_setarr['title']];
	if (!empty($_body_arr)){
		$_temp_arr = $_setarr['template'];
		if (!empty($_body_arr['arrchildid'])){
			$_where_arr = "SELECT * FROM ".table('diy_'.$_body_arr['module'])." WHERE `status`=1 AND `catid` in (".__neirong_arrchildid($_body_arr['arrchildid'], $_data).") ";
		}else{
			$_where_arr = "SELECT * FROM ".table('diy_'.$_body_arr['module'])." WHERE `status`=1 AND `catid`=".intval($_setarr['title'])." ";
		}
		if ($_setarr['select']=='suo'){
			$_where_arr.= " AND `thumb` LIKE 'array%' ";
		}elseif ($_setarr['select']=='tu'){
			$_where_arr.= " AND (`subtitle` like '%图%' OR `subtitle` like '%组%') ";
		}elseif ($_setarr['select']=='fu'){
			$_where_arr.= " AND (`subtitle` like '%附%' OR `subtitle` like '%组%') ";
		}elseif ($_setarr['select']=='zhai'){
			$_where_arr.= " AND `description`!='' ";
		}elseif ($_setarr['select']=='yin'){
			$_where_arr.= " AND `subtitle` like '%隐%' ";
		}elseif ($_setarr['select']=='pai'){
			$_where_arr.= " AND `subtitle` like '%派%' ";
		}elseif ($_setarr['select']=='shang'){
			$_where_arr.= " AND `subtitle` like '%赏%' ";
		}elseif ($_setarr['select']=='mai'){
			$_where_arr.= " AND `subtitle` like '%卖%' ";
		}elseif ($_setarr['select']=='jing'){
			$_where_arr.= " AND `jinghua`=1 ";
		}elseif ($_setarr['select']=='ding'){
			$_where_arr.= " AND `dingzhi`=1 ";
		}elseif ($_setarr['select']=='isuser'){
			$_where_arr.= " AND `sysadd`=0 ";
		}elseif ($_setarr['select']=='isadmin'){
			$_where_arr.= " AND `sysadd`=1 ";
		}
		if ($_setarr['order']=='rand'){
			$_where_arr.= "ORDER BY rand() ";
		}elseif ($_setarr['order']){
			$_where_arr.= "ORDER BY `".$_setarr['order']."` ";
		}
		$_where_arr.= ($_setarr['asc']=='asc')?"asc ":"desc ";
		$_where_arr.= (intval($_setarr['body']))?"LIMIT 0, ".intval($_setarr['body'])." ":"";
		$_time_arr = $db->getall($_where_arr); $_n = 1;
		foreach ($_time_arr as $_val){
			if (intval($_setarr['cut'])>0) {
				$_val['title'] = cut_str($_val['title'],intval($_setarr['cut']),0,$_setarr['dot']);
			}
			$_val['title'] = htmlspecialchars($_val['title']);
			//$_val['url'] = get_link('vs|sid|m', '', '1').'&amp;c=neirong&amp;showid='.$_val['id'];
			$_urlarr = array(
				'm'=>'neirong',
				'c'=>'show',
				'catid'=>$_val['catid'],
				'id'=>$_val['id'],
				'sid'=>$_GET['sid'],
				'vs'=>$_GET['vs'],
			);
			$_val['url'] = url_rewrite('KF_neirongshow', $_urlarr);
			if ($_temp_arr){
                //版本显示
                if (strpos($_temp_arr,"{/wap}")!==false){
                    $_temp_arr = preg_replace("/\{wap=(.+?)\}(.+?)\{\/wap\}/es","ubb_neirong_wap('\\2','\\1',1)",$_temp_arr);
                }
				//基本
				$_temp_val = str_replace(array('{n}','{id}','{url}','{title}','{reply}','{read}','{username}'), array($_n,$_val['id'],$_val['url'],$_val['title'],$_val['reply'],$_val['read'],$_val['username']), $_temp_arr);
                //用户信息
				if ($_val['sysadd'] == 0){
					if (strpos($_temp_val,"{userid}")!==false){
						if ($_val['username'] != $__userinfo['username']) {
							$__userinfo = get_to_username($_val['username']);
						}
						$_temp_val = str_replace("{userid}", $__userinfo['userid'], $_temp_val);
					}
					if (strpos($_temp_val,"{nickname}")!==false){
						if ($_val['username'] != $__userinfo['username']) {
							$__userinfo = get_to_username($_val['username']);
						}
						$_temp_val = str_replace("{nickname}", $__userinfo['nickname'], $_temp_val);
					}
					if (strpos($_temp_val,"{colorname}")!==false){
						if ($_val['username'] != $__userinfo['username']) {
							$__userinfo = get_to_username($_val['username']);
						}
						$_temp_val = str_replace("{colorname}", colorname($__userinfo), $_temp_val);
					}
					if (strpos($_temp_val,"{onlycolorname}")!==false){
						if ($_val['username'] != $__userinfo['username']) {
							$__userinfo = get_to_username($_val['username']);
						}
						$_temp_val = str_replace("{onlycolorname}", onlycolorname($__userinfo), $_temp_val);
					}
				}else{
					$_temp_val = str_replace('{userid}', '0', $_temp_val);
					$_temp_val = str_replace(array('{nickname}','{colorname}','{onlycolorname}'), '', $_temp_val);
				}
                //自定义截取标题
                if (strpos($_temp_val,"{title,")!==false){
                    $_temp_val = preg_replace("/\{title\,(.+?)\}/es","cut_str2('{$_val['title']}','\\1','{$_setarr['dot']}')",$_temp_val);
                }
                //自定义截取摘要
                if (strpos($_temp_val,"{description,")!==false){
                    $_temp_val = preg_replace("/\{description\,(.+?)\}/es","cut_str2('{$_val['description']}','\\1','{$_setarr['dot']}')",$_temp_val);
                }
                //栏目标题
                if (strpos($_temp_val,"{mtitle")!==false){
					$mtitle = $_data[$_val['catid']]['title'];
                    $_temp_val = str_replace("{mtitle}", $mtitle, $_temp_val);
					$_temp_val = preg_replace("/\{mtitle\,(.+?)\}/es","cut_str2('{$mtitle}','\\1')",$_temp_val);
                }
                //栏目地址
                if (strpos($_temp_val,"{murl}")!==false){
					$_murlarr = array(
						'm'=>'neirong',
						'c'=>'list',
						'catid'=>$_val['catid'],
						'sid'=>$_GET['sid'],
						'vs'=>$_GET['vs'],
					);
					$murl = url_rewrite('KF_neironglist', $_murlarr);
					$_temp_val = str_replace("{murl}", $murl, $_temp_val);
				}
				//缩略图
				$thumb_arr = string2array($_val['thumb']);
				if ($thumb_arr){
					$_temp_val = str_replace(array('{thumb}','{thumbsmall}','{thumbbig}'), array($thumb_arr[0],$thumb_arr[48],$thumb_arr[100]), $_temp_val);
				}else{
					$_temp_val = str_replace(array('{thumb}','{thumbsmall}','{thumbbig}'), "", $_temp_val);
				}
				//介绍词
				if ($_val['description']) {
					$_temp_val = str_replace('{description}',cut_str(strip_tags($_val['description']),200,0,$aset['dot']),$_temp_val);
				}else{
					$_temp_val = str_replace('{description}','',$_temp_val);
				}
				$_temp_val = preg_replace("/\{addtime\([\'|\"](.+?)[\'|\"]\)\}/es","date('\\1',".$_val['inputtime'].")",$_temp_val);
				$_temp_val = preg_replace("/\{addtime\((.+?)\)\}/es","date('\\1',".$_val['inputtime'].")",$_temp_val);
				$_temp_val = str_replace('{addtime}',date('Y-m-d H:i:s',$_val['inputtime']),$_temp_val);
				if ($_val['updatetime']){
					$_temp_val = preg_replace("/\{uptime\([\'|\"](.+?)[\'|\"]\)\}/es","date('\\1',".$_val['inputtime'].")",$_temp_val);
					$_temp_val = preg_replace("/\{uptime\((.+?)\)\}/es","date('\\1',".$_val['inputtime'].")",$_temp_val);
					$_temp_val = str_replace('{uptime}',date('Y-m-d H:i:s',$_val['updatetime']),$_temp_val);
				}else{
					$_temp_val = str_replace('{uptime}','',$_temp_val);
				}
				$_reture.= $_temp_val;
			}else{
				$_reture.= "<a href=\"{$_val['url']}\">".$_val['title']."</a><br/>";
			}
			$_n++;
		}
	}
	return $_reture;
}

function cut_str2($str, $cut, $dot='...') {
    if (empty($str) || empty($cut)) return $str;
    $cutr = explode(',', $cut);
    if (intval($cutr[0]) == 0) return $str;
    $dot = $cutr[1]?$cutr[1]:$dot;
    return cut_str($str,intval($cutr[0]),0,$dot);
}
function ubb_neirong_wap($_str, $_v, $isformdata = 0){
	if (!in_array($_GET['vs'],explode(',',$_v))) return '';
	if ($isformdata) $_str = stripslashes($_str);
	return $_str;
}
function __neirong_arrchildid($ids ,$data){
	if (strpos($ids,",") !== false){
		$_next = false;
		$_arrchildid = "";
		$_arr = explode(",", $ids);
		foreach ($_arr as $_val) {
			if (!empty($data[$_val]['arrchildid'])){
				$_arrchildid.= $data[$_val]['arrchildid'].",";
				$_next = true;
			}else{
				$_arrchildid.= $_val.",";
			}
		}
		if ($_next) {
			return __neirong_arrchildid(trim($_arrchildid, ","), $data);
		}else{
			return trim($_arrchildid, ",");
		}
	}else{
		return $ids;
	}
}
?>