<?php
/**
 * 调用广告系统
 */
function ubb_guanggao($set) {
	global $db;
	if (strpos($set,"||") !== false){
		$setarr = explode('||', $set);
	}else{
		$setarr = explode('|', $set);
	}
	$_catid = intval($setarr[0]); 	//广告位ID
	$_num = $setarr[1]; 			//显示数量
	$_order = $setarr[2]; 			//排序
	$_br = intval($setarr[3]); 		//换行
	
	$_where = " WHERE `disabled`=0";
	$_where.= ($_catid>0)?" AND `catid`=".$_catid:"";
	
	if (strpos($_num,"-") !== false){
		$_numarr = explode('-', $_num);
		$_limit = " LIMIT ".abs($_numarr[0]).",".$_numarr[1];
	}else{
		$_limit = " LIMIT 0,".intval($_num);
	}
	
	switch ($_order) {
		case 'startdate':
			$_order = "`startdate` desc";
			break;
		case 'enddate':
			$_order = "`enddate` desc";
			break;
		case 'clicks':
			$_order = "`clicks` desc";
			break;
		case '1':
			$_order = "rand()";
			break;
		default:
			$_order = "`listorder` desc,`addtime` desc";
	}
	$_result = $db->query("select * from ".table('guanggao')." {$_where} ORDER BY {$_order} {$_limit}");
	$_reture = ''; $_n= 1; $_url = get_link("vs|sid", "", 1);
	while($_row = $db->fetch_array($_result)){
		if ($_br > 0){
			if ($_n % $_br == 0) $_reture.= '<br/>';
		}
		if ($_row['cattype']=='ubb'){
			$_row['setting'] = string2array($_row['setting']);
			$_reture.= ubb($_row['setting']['ubb']);
		}elseif ($_row['cattype']=='wml'){
			$_row['setting'] = string2array($_row['setting']);
			$_reture.= wml($_row['setting']['wml']);
		}elseif ($_row['cattype']=='images'){
			$_row['setting'] = string2array($_row['setting']);
			$_row['url'] = $_url."&amp;m=guanggao&amp;id={$_row['id']}";
			$_reture.= "<a href=\"{$_row['url']}\"><img src=\"{$_row['setting']['images']}\" alt=\"{$_row['title']}\"/></a>";
		}elseif ($_row['cattype']=='link'){
			$_row['url'] = $_url."&amp;m=guanggao&amp;id={$_row['id']}";
			$_reture.= "<a href=\"{$_row['url']}\">{$_row['title']}</a>";
		}
		$_n ++;
	}
	return $_reture;
}

?>