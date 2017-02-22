<?php
function smarty_function_kuaifan_sousuo($params, &$smarty)
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
			case "搜索变量名":
				$aset['keyname'] = "PGEST[".$a[1]."]";
				$aset['key_name'] = $a[1];
				break;
			case "状态":
				$aset['status'] = $a[1];
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
	$aset['key_name']=isset($aset['key_name'])?$aset['key_name']:'key';
	$aset['status']=isset($aset['status'])?intval($aset['status']):1;
	$orderbysql=" ORDER BY searchnums DESC";
	if (!empty($aset['orderby'])) $orderbysql=" ORDER BY ".$aset['orderby'];
	if ($aset['status']) $wheresql.=" AND `status`=".intval($aset['status']);
	if ($params['where']) $wheresql.=" AND ".$params['where'];
	if (!empty($aset['keyname'])){
		$keyname = safe_replace(trim($aset['keyname']));
		$keyname = htmlspecialchars(strip_tags($keyname));
		$keyname = str_replace('%', '', $keyname);	//过滤'%'，用户全文搜索
		kf_class::run_sys_class('segment', '', 0);
		$segment = new segment();
		$segment_q = $segment->get_keyword($segment->split_result($keyname));
		//如果分词结果为空
		if(!empty($segment_q)) {
			$wheresql.= " AND (MATCH (`data`) AGAINST ('$segment_q' IN BOOLEAN MODE) OR `description` like '%{$keyname}%')";
		} else {
			if (preg_match("/[\x7f-\xff]/", $keyname)) {  //判断字符串中是否有中文
				$wheresql.= " AND (`data` like '%{$keyname}%' OR `description` like '%{$keyname}%')";
				if (!empty($aset['orderby'])) {
					$orderbysql=" ORDER BY case when `data` like '%{$keyname}%' then 0 else 1 end,".$aset['orderby'];
				}else{
					$orderbysql=" ORDER BY case when `data` like '%{$keyname}%' then 0 else 1 end,searchnums DESC";
				}
			}else{
				$wheresql.= " AND (`data_pinyin` like '%{$keyname}%')";
			}
		}
		$_GET[$aset['key_name']] = $keyname;
	}
	if (!empty($wheresql)){
		$wheresql=" WHERE ".ltrim(ltrim($wheresql),'AND');
	}
	if (isset($aset['paged']))
	{
		kf_class::run_sys_class('page','',0);
		$total_sql="SELECT COUNT(*) AS num FROM ".table('sousuo').$wheresql;
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
	$result = $db->query("SELECT * FROM ".table('sousuo')." ".$wheresql.$orderbysql.$limit);
	$list= array();
	$__n= 1;
	$_urlarr = array(
		'm'=>'sousuo',
		'c'=>'go',
		'sid'=>$_GET['sid'],
		'vs'=>$_GET['vs'],
	);
	$_urlval = get_link('', '&amp;', '', $_urlarr);
	kf_class::run_sys_func('sousuo');
	while($row = $db->fetch_array($result))
	{
		$row['_n']=$__n+($currenpage*$aset['row'])-$aset['row'];
		$row['url']=$_urlval.'&amp;id='.$row['id'];
		$row['title_']=$row['title'];
		$row['title']=htmlneirong($row['title'], $aset['titlelen'], $aset['keyname'], 0, $aset['dot']);
		$__n ++;
		$list[] = $row;
	}
	$smarty->assign($aset['listname'],$list);
}
?>