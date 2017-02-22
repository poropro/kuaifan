<?php
function smarty_function_kuaifan_pinglun($params, &$smarty)
{
	global $db;
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
			case "最多数目":
				$aset['arow'] = $a[1];
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
			case "排序":
				$aset['orderby'] = $a[1];
				break;
		}
	}
	if (is_array($aset)) $aset=array_map("get_smarty_request",$aset);
	$aset['listname']=isset($aset['listname'])?$aset['listname']:"list";
	$aset['row']=isset($aset['row'])?intval($aset['row']):10;
	$aset['start']=isset($aset['start'])?intval($aset['start']):0;
	$aset['titlelen']=isset($aset['titlelen'])?intval($aset['titlelen']):15;
	$aset['pagename']=isset($aset['pagename'])?$aset['pagename']:'page';
	$orderbysql=" ORDER BY lastupdate DESC";
	if (!empty($aset['orderby'])) $orderbysql=" ORDER BY ".$aset['orderby'];
	if ($params['where']) $wheresql.=" AND ".$params['where'];
	if (!empty($wheresql)){
		$wheresql=" WHERE ".ltrim(ltrim($wheresql),'AND');
	}
	if (isset($aset['paged']))
	{
		kf_class::run_sys_class('page','',0);
		$total_sql="SELECT COUNT(*) AS num FROM ".table('pinglun').$wheresql;
		$total_count=$db->get_total($total_sql);
		if ($aset['arow'] > 0 && $total_count > $aset['arow']) $total_count = $aset['arow'];
		$pagelist = new page(array('total'=>$total_count,'perpage'=>$aset['row'],'getarray'=>$_GET,'page_name'=>$aset['page_name']));
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
	}
	$limit=" LIMIT ".abs($aset['start']).','.$aset['row'];
	$result = $db->query("SELECT * FROM ".table('pinglun')." ".$wheresql.$orderbysql.$limit);
	$list= array();
	$__n= 1;
	while($row = $db->fetch_array($result))
	{
		$row['_n']=$__n+($currenpage*$aset['row'])-$aset['row'];
		$row['arr'] = explode('_', $row['commentid']);
		$_urlarr = array(
			'm'=>'neirong',
			'c'=>'show',
			'catid'=>$row['arr'][1],
			'id'=>$row['arr'][2],
			'sid'=>$_GET['sid'],
			'vs'=>$_GET['vs'],
		);
		$row['url'] = url_rewrite('KF_neirongshow', $_urlarr);
		$_urlarr = array(
			'm'=>'neirong',
			'c'=>'pinglun',
			'catid'=>$row['arr'][1],
			'id'=>$row['arr'][2],
			'sid'=>$_GET['sid'],
			'vs'=>$_GET['vs'],
		);
		$row['plurl'] = get_link('', '&amp;', '', $_urlarr);
		$__n ++;
		$list[] = $row;
	}
	$smarty->assign($aset['listname'],$list);
}
?>