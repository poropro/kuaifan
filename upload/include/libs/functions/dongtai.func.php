<?php
/**
 * 调用友情链接
 */
function ubb_dongtai($set) {
	global $db;
	$setarr = explode('||', $set);
	$_num = intval($setarr[0]); 	//显示数量
	$_cut = $setarr[1]; 			//截取标题
	$_dot = $setarr[2]; 			//填补字符
	$_asc = $setarr[3]; 			//调用类型
	$_template = $setarr[4]; 		//自写模板
	$_cut = ($_cut>0)?$_cut:10;
	if (empty($_template)){
		$_template = '<a href="{hyurl}">{colorname}</a>{time2}{type}<a href="{url}">{title}</a><br/>';
	}
	
	if (strpos($_num,"-") !== false){
		$_numarr = explode('-', $_num);
		$_limit = " LIMIT ".abs($_numarr[0]).",".$_numarr[1];
	}else{
		$_limit = " LIMIT 0,".intval($_num);
	}
	if ($_asc == '1'){
		$_where = " WHERE `userid`>0";
	}elseif ($_asc == '2'){
		$_where = " WHERE `userid`=0";
	}else{
		$_where = "";
	}
	
	$_order = "`time` desc";
	
	$_result = $db->query("select * from ".table('tongji')." {$_where} ORDER BY {$_order} {$_limit}");
	$_reture = ''; $_n= 1; $_url = get_link('sid|vs','',1);
	while($_row = $db->fetch_array($_result)){
		$_row['n']=$_n;
		$_row['get']=string2array($_row['get']);
		$_row['url']=get_link("vs|sid","","",$_row['get'])."&amp;sid={$_GET['sid']}&amp;vs={$_GET['vs']}";
		$_row['hyurl']=$_url.'&amp;m=huiyuan&amp;c=ziliao&amp;userid='.$_row['userid'];
		if ($_row['title']){      
			$_row['title']=cut_str($_row['title'],$_cut,0,$_dot);
		}else{
			$_row['title']="未命名页面";
		}
		if ($_row['userid']==0){
			$_row['username_'] = $_row['username'].$_row['session'];
		}else{
			$_row['username_'] = $_row['username'];
		}
		$_temp_val = $_template;
		//版本显示
		if (strpos($_temp_val,"{/wap}")!==false){
			$_temp_val = preg_replace("/\{wap=(.+?)\}(.+?)\{\/wap\}/es","ubb_dongtai_wap('\\2','\\1',1)",$_temp_val);
		}
		$_temp_val = preg_replace("/\{time\([\'|\"](.+?)[\'|\"]\)\}/es","date('\\1',".$_row['time'].")",$_temp_val);
		$_temp_val = preg_replace("/\{time\((.+?)\)\}/es","date('\\1',".$_row['time'].")",$_temp_val);
		$_temp_val = str_replace('{time}',date('Y-m-d H:i:s',$_row['time']),$_temp_val);
		$_temp_val = str_replace('{time2}',dongtai_date($_row['time']),$_temp_val);
		if (strpos($_temp_val,"{huiyuan}")!==false){
			if ($_row['userid'] > 0){
				$_temp_val = str_replace('{huiyuan}', '<a href="'.$hyurl.'">'.$_row['username_'].'</a>', $_temp_val);
			}else{
				$_temp_val = str_replace('{huiyuan}', $_row['username_'], $_temp_val);
			}
		}
		if (strpos($_temp_val,"{nickname}")!==false){
			if ($_row['userid'] > 0){
				$_temp_val = str_replace('{nickname}',colorname($_row['username'],'',0),$_temp_val);
			}else{
				$_temp_val = str_replace('{nickname}', $_row['username_'], $_temp_val);
			}
		}
		if (strpos($_temp_val,"{colorname}")!==false){
			if ($_row['userid'] > 0){
				$_temp_val = str_replace('{colorname}',colorname($_row['username']),$_temp_val);
			}else{
				$_temp_val = str_replace('{colorname}', $_row['username_'], $_temp_val);
			}
		}
		$_temp_val = str_replace('{ip}',$_row['ip'],$_temp_val);
		$_temp_val = str_replace('{ip2}',$_row['ip2'],$_temp_val);
		$_temp_val = str_replace('{userid}',$_row['userid'],$_temp_val);
		$_temp_val = str_replace('{username}',$_row['username'],$_temp_val);
		$_temp_val = str_replace('{type}',$_row['type'],$_temp_val);
		$_temp_val = str_replace('{url}',$_row['url'],$_temp_val);
		$_temp_val = str_replace('{hyurl}',$_row['hyurl'],$_temp_val);
		$_temp_val = str_replace('{title}',$_row['title'],$_temp_val);
		if (strpos($_temp_val,"{title,")!==false){
			$_temp_val = preg_replace("/\{title\,(.+?)\}/es","cut_dongtai_str2('{$_row['title']}','\\1','{$_dot}')",$_temp_val);
		}
		$_reture.= $_temp_val;
		$_n ++;
	}
	return $_reture;
}
function cut_dongtai_str2($str, $cut, $dot='...') {
    if (empty($str) || empty($cut)) return $str;
    $cutr = explode(',', $cut);
    if (intval($cutr[0]) == 0) return $str;
    $dot = $cutr[1]?$cutr[1]:$dot;
    return cut_str($str,intval($cutr[0]),0,$dot);
}
function dongtai_date($staday){
	$value = SYS_TIME - $staday;
	if($value < 0){
		return '';
	}elseif($value >= 0 && $value < 59){
		$return=($value+1)."秒前";
	}elseif($value >= 60 && $value < 3600){
		$min = intval($value / 60);
		$return=$min."分钟前";
	}elseif(date("Y-m-d", SYS_TIME) == date("Y-m-d", $staday)){
		$return="今天".date("H:i", $staday);
	}elseif(date("Y-m-d", mktime(0, 0, 0,date("m",SYS_TIME),date("d",SYS_TIME)-1,date("Y",SYS_TIME))) == date("Y-m-d", $staday)){
		$return="昨天".date("H:i", $staday);
	}elseif(date("Y-m-d", mktime(0, 0, 0,date("m",SYS_TIME),date("d",SYS_TIME)-2,date("Y",SYS_TIME))) == date("Y-m-d", $staday)){
		$return="前天".date("H:i", $staday);
	}elseif(date("Y", SYS_TIME) == date("Y", $staday)){
		$return=date("m-d H:i", $staday);
	}else{
		$return=date("Y-m-d H:i", $staday);
	}
	return $return;	 
}
function ubb_dongtai_wap($_str, $_v, $isformdata = 0){
	if (!in_array($_GET['vs'],explode(',',$_v))) return '';
	if ($isformdata) $_str = stripslashes($_str);
	return $_str;
}
?>