<?php
function smarty_function_kuaifan_pc($params, &$smarty)
{
	global $db;
	$params['set'] = str_replace("\,", "\u002c", $params['set']);
	$arr=explode(',',$params['set']);
	foreach($arr as $str)
	{
		$str = str_replace("\u002c", ",", $str);
		$a=explode(':',$str);
		switch ($a[0])
		{
			case "列表名":
				$aset['listname'] = $a[1];
				break;
			case "ID列表":
				$aset['listname_id'] = $a[1];
				break;
			case "ID列名":
				$aset['listid'] = $a[1];
				break;
			case "列名":
				$aset['fieldname'] = $a[1];
				break;
			case "显示数目":
				$aset['row'] = $a[1];
				break;
			case "开始位置":
				$aset['start'] = $a[1];
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
			case "数据表":
				$aset['tabledb'] = $a[1];
				break;
			case "排序":
				$aset['order'] = $a[1];
				break;
		}
	}
	if (is_array($aset)) $aset=array_map("get_smarty_request",$aset);
	$aset['listname']=isset($aset['listname'])?$aset['listname']:"list";
	$aset['row']=isset($aset['row'])?intval($aset['row']):10;
	$aset['start']=isset($aset['start'])?intval($aset['start']):0;
	$aset['titlelen']=isset($aset['titlelen'])?intval($aset['titlelen']):15;
	$aset['pagename']=isset($aset['pagename'])?$aset['pagename']:'page';
	$aset['listid']=$params['listid']?$params['listid']:"id";
	$orderbysql = isset($aset['order'])?' ORDER BY '.str_replace(">", ",", $aset['order']):'';
	$wheresql = "";
	if ($params['where']) $wheresql.= " AND ".$params['where'];
	if (!empty($wheresql)){
		$wheresql= " WHERE ".ltrim(ltrim($wheresql),'AND');
	}
	if (isset($aset['paged']))
	{
		kf_class::run_sys_class('page','',0);
		$total_sql="SELECT COUNT(*) AS num FROM ".table($aset['tabledb']).$wheresql;
		$total_count=$db->get_total($total_sql);
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
	if (!empty($aset['fieldname'])){
		$aset['fieldname'] = str_replace("|", ",", $aset['fieldname']);
		$result = $db->query("SELECT {$aset['fieldname']} FROM ".table($aset['tabledb'])." ".$wheresql.$orderbysql.$limit);
	}else{
		$result = $db->query("SELECT * FROM ".table($aset['tabledb'])." ".$wheresql.$orderbysql.$limit);
	}
	$list= array();
	$__n= 1;$_timearrid = '';
	while($row = $db->fetch_array($result))
	{
		$_timearrid.= $row[$aset['listid']].',';
		$row['_n']=(isset($aset['paged']))?$__n+($currenpage*$aset['row'])-$aset['row']:$__n;
		$__n ++;
		$list[] = $row;
	}
	if ($aset['listname_id']) $smarty->assign($aset['listname_id'],rtrim($_timearrid,','));
	$smarty->assign($aset['listname'],$list);
}
?>