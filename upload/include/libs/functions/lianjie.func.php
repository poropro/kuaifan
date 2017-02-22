<?php
/**
 * 调用友情链接
 */
function ubb_lianjie($set) {
	global $db;
	if (strpos($set,"||") !== false){
		$setarr = explode('||', $set);
	}else{
		$setarr = explode('|', $set);
	}
	$_catid = intval($setarr[0]); 	//分类ID
	$_num = $setarr[1]; 			//显示数量
	$_name = $setarr[2]; 			//名称
	$_order = $setarr[3]; 			//排序
	$_br = intval($setarr[4]); 		//换行
	$_join = $setarr[5]; 		//分隔符
	
	$_where = " WHERE `type`=1";
	$_where.= ($_catid>0)?" AND `catid`=".$_catid:"";
	
	if (strpos($_num,"-") !== false){
		$_numarr = explode('-', $_num);
		$_limit = " LIMIT ".abs($_numarr[0]).",".$_numarr[1];
	}else{
		$_limit = " LIMIT 0,".intval($_num);
	}
	
	switch ($_order) {
		case 'read':
			$_order = "`read` desc";
			break;
		case 'readtime':
			$_order = "`readtime` desc";
			break;
		case 'from':
			$_order = "`from` desc";
			break;
		case 'fromtime':
			$_order = "`fromtime` desc";
			break;
		case 'zhichi':
			$_order = "`zhichi` desc";
			break;
		case 'buzhichi':
			$_order = "`buzhichi` desc";
			break;
		case '1':
			$_order = "rand()";
			break;
		default:
			$_order = "`listorder` desc,`inputtime` desc";
	}
	$_result = $db->query("select * from ".table('lianjie')." {$_where} ORDER BY {$_order} {$_limit}");
	$_reture = ''; $_n= 1; $_url = get_link("vs|sid", "", 1);
	while($_row = $db->fetch_array($_result)){
		if ($_br > 0){
			if ($_n % $_br == 0) {
        $_reture.= '<br/>';
      }elseif (!empty($_join)){
        $_reture.= $_join;
      }
		}elseif (!empty($_join)){
      $_reture.= $_join;
		}
		if ($_name == '1'){ //全称
			$_row['titlej'] = $_row['title'];
		}
		$_row['url'] = $_url."&amp;m=lianjie&amp;c=xiangqing&amp;id={$_row['id']}";
		$_reture.= "<a href=\"{$_row['url']}\">{$_row['titlej']}</a>";
		$_n ++;
	}
	return $_reture;
}

?>