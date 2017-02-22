<?php
function smarty_function_kuaifan_neirong_pinglun($params, &$smarty)
{
	global $db,$_CFG;
	$arr=explode(',',$params['set']);
	foreach($arr as $str)
	{
		$a=explode(':',$str);
		switch ($a[0])
		{
			case "列表名":
				$aset['listname'] = $a[1];
				break;
			case "显示数目":
				$aset['row'] = $a[1];
				break;
			case "标题长度":
				$aset['titlelen'] = $a[1];
				break;
			case "开始位置":
				$aset['start'] = $a[1];
				break;
			case "填补字符":
				$aset['dot'] = $a[1];
				break;
			case "分页显示":
				$aset['paged'] = $a[1];
				break;
			case "分页名":
				$aset['pagename'] = $a[1];
				break;
			case "分页变量名":
				$aset['page_name'] = $a[1];
				break;
			case "搜索变量名":
				$aset['keyname'] = "PGEST[".$a[1]."]";
				$aset['key_name'] = $a[1];
				break;
			case "原文标题":
				$aset['all_title'] = $a[1];
				break;
			case "状态":
				$aset['status'] = $a[1];
				break;
			case "排序":
				$aset['orderby'] = $a[1];
				break;
			case "排序方式":
				$aset['desc'] = $a[1];
				break;
			case "会员名":
				$aset['username'] = $a[1];
				break;
		}
	}
	if (is_array($aset)) $aset=array_map("get_smarty_request",$aset);
	$aset['listname']=isset($aset['listname'])?$aset['listname']:"list";
	$aset['listpage']=isset($aset['listpage'])?$aset['listpage']:'KF_neirongreply';
	$aset['row']=isset($aset['row'])?intval($aset['row']):10;
	$aset['start']=isset($aset['start'])?intval($aset['start']):0;
	$aset['titlelen']=isset($aset['titlelen'])?intval($aset['titlelen']):15;
	$aset['pagename']=isset($aset['pagename'])?$aset['pagename']:'page';
	$aset['key_name']=isset($aset['key_name'])?$aset['key_name']:'key';
	$aset['orderby']=isset($aset['orderby'])?$aset['orderby']:'creat_at';
	$aset['desc']=isset($aset['desc'])?$aset['desc']:'DESC';
	$orderbysql=" ORDER BY `{$aset['orderby']}` {$aset['desc']}";
	if (!empty($aset['keyname'])){
		$keyname=trim($aset['keyname']);
		$wheresql.=" AND (`username` LIKE '%{$keyname}%' or `content` LIKE '%{$keyname}%' or commentid in (SELECT `commentid` FROM ".table('pinglun')." WHERE `site` = '{$_CFG['site']}' AND `title` LIKE '%{$keyname}%'))";
		$_GET[$aset['key_name']] = $keyname;
	}
	if (!empty($aset['username'])){
		$wheresql.=" AND `username`='{$aset['username']}'";
	}
	$wheresql.=" AND `site` = '{$_CFG['site']}'";
	if (isset($aset['status'])) $wheresql.=" AND `status` = '{$aset['status']}'";
	if ($params['where']) $wheresql.= " AND ".$params['where'];
	if (!empty($wheresql)){
		$wheresql=" WHERE ".ltrim(ltrim($wheresql),'AND');
	}
	if (isset($aset['paged']))
	{
		kf_class::run_sys_class('page','',0);
		$total_sql="SELECT COUNT(*) AS num FROM ".table('pinglun_data_'.$_CFG['site']).$wheresql;
		$total_count=$db->get_total($total_sql);
		$pagelist = new page(array('total'=>$total_count,'perpage'=>$aset['row'],'alias'=>$aset['listpage'],'getarray'=>$_GET,'page_name'=>$aset['page_name']));
		$currenpage=$pagelist->nowindex;
		$aset['start']=($currenpage-1)*$aset['row'];
		$smarty->assign($aset['pagename'],$pagelist->show('wap'));
		$pagearr = array(
			'total'=>$total_count, //总数
			'perpage'=>$aset['row'], //每页显示
			'nowpage'=>$currenpage, //当前页
			'totalpage'=>$pagelist->totalpage, //总页数
		);
		$smarty->assign($aset['pagename'].'_info',$pagearr);
	}else{
		$currenpage = 1;
	}
	kf_class::run_sys_func('neirong');
	kf_class::run_sys_func('ubb');
	$limit=" LIMIT ".abs($aset['start']).','.$aset['row'];
	$result = $db->query("SELECT * FROM ".table('pinglun_data_'.$_CFG['site'])." ".$wheresql.$orderbysql.$limit);
	$list= array();
	$__n= 1;
	while($row = $db->fetch_array($result))
	{
		if (isset($aset['all_title'])){
			$_a = $db -> getone("select {$aset['all_title']} from ".table('pinglun')." WHERE `commentid`='{$row['commentid']}' LIMIT 1");
			$row['a'] = $_a;
		}
		$row['_n']=$__n+($currenpage*$aset['row'])-$aset['row'];
		$row['title_']=$row['title'];
		$row['commentid_']=explode('_', $row['commentid']);
		$row['title']=cut_str($row['title'],$aset['titlelen'],0,$aset['dot']);
		$row['content_']=$row['content'];
		$row['content']=cut_str($row['content'],$aset['titlelen'],0,$aset['dot']);
		if (strpos($row['content'], "@") !== false){
			$row['content']=preg_replace('/@(.*?) /es', "__function_kuaifan_neirong_pinglun_content('\\1')", $row['content'].' ');
		}
		$row['content'] = set_em($row['content']);
		$row['content'] = ubb_neirong_guanggao($row['content'], 1);
		$__n ++;
		$list[] = $row;
	}
	$smarty->assign($aset['listname'],$list);
}
function __function_kuaifan_neirong_pinglun_content($str, $strp = ''){
	global $_CFG;
	if (strpos($str, "<") !== false){
		$_str = '@'.$str.' ';
		return preg_replace('/@(.*?)</es', "__function_kuaifan_neirong_pinglun_content('\\1','<')", $_str);
	}else{
		$_str = "<a href='{$_CFG['site_dir']}index.php?m=huiyuan&amp;c=sousuo&amp;key=".urlencode($str)."'>@{$str}</a> ".$strp;
	}
	return $_str;
}
?>