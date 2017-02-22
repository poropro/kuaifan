<?php
function smarty_function_kuaifan_neirong($params, &$smarty)
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
			case "ID列表":
				$aset['listname_id'] = $a[1];
				break;
			case "显示数目":
				$aset['row'] = $a[1];
				break;
			case "分类":
				$aset['catid'] = $a[1];
				break;
			case "模型":
				$aset['module'] = $a[1];
				break;
			case "论坛模型":
				$aset['isbbs'] = $a[1];
				break;
			case "状态":
				$aset['status'] = $a[1];
				break;
			case "标题长度":
				$aset['titlelen'] = $a[1];
				break;
			case "摘要长度":
				$aset['infolen'] = $a[1];
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
			case "页面":
				$aset['showname'] = $a[1];
				break;
			case "列表页":
				$aset['listpage'] = $a[1];
				break;
			case "排序":
				$aset['listorder'] = $a[1];
				break;
			case "论坛排序":
				$aset['bbsorder'] = $a[1];
				break;
			case "管理":
				$aset['isadmin'] = $a[1];
				break;
		}
	}
	if (is_array($aset)) $aset=array_map("get_smarty_request",$aset);
	$aset['listname']=isset($aset['listname'])?$aset['listname']:"list";
	$aset['row']=isset($aset['row'])?intval($aset['row']):10;
	$aset['start']=isset($aset['start'])?intval($aset['start']):0;
	$aset['titlelen']=isset($aset['titlelen'])?intval($aset['titlelen']):15;
	$aset['infolen']=isset($aset['infolen'])?intval($aset['infolen']):0;
	$aset['showname']=isset($aset['showname'])?$aset['showname']:'KF_neirongshow';
	$aset['listpage']=isset($aset['listpage'])?$aset['listpage']:'KF_neironglist';
	$aset['pagename']=isset($aset['pagename'])?$aset['pagename']:'page';
	$aset['key_name']=isset($aset['key_name'])?$aset['key_name']:'key';
	$orderbysql = " ORDER BY `listorder` DESC ,inputtime DESC";
	if ($aset['listorder']) $orderbysql = ' ORDER BY '.$aset['listorder'];
	if ($aset['bbsorder'] || $aset['isbbs']) $orderbysql = __bbsorder($aset['bbsorder']);;
	isset($aset['catid'])?$wheresql.=" AND catid=".intval($aset['catid'])." ":'';
	isset($aset['status'])?$wheresql.=" AND status=".intval($aset['status'])." ":'';
	if (!empty($aset['keyname'])){
		$keyname = safe_replace(trim($aset['keyname']));
		$keyname = htmlspecialchars(strip_tags($keyname));
		$wheresql.=" AND title like '%{$keyname}%'";
		$_GET[$aset['key_name']] = $keyname;
	}
	if ($params['where']) $wheresql.= " AND ".$params['where'];
	if ($params['catid']) $wheresql.= " AND catid in (".$params['catid'].")";
	if (!empty($wheresql)){
		$wheresql=" WHERE ".ltrim(ltrim($wheresql),'AND');
	}
	if (isset($aset['paged']))
	{
		kf_class::run_sys_class('page','',0);
		$total_sql="SELECT COUNT(*) AS num FROM ".table('diy_'.$aset['module']).$wheresql;
		$total_count=$db->get_total($total_sql);
		if ($aset['isadmin']){
			$pagelist = new page(array('total'=>$total_count,'perpage'=>$aset['row'],'getarray'=>$_GET,'page_name'=>$aset['page_name']));
		}else{
			$pagelist = new page(array('total'=>$total_count,'perpage'=>$aset['row'],'alias'=>$aset['listpage'],'getarray'=>$_GET,'page_name'=>$aset['page_name']));
		}
		$currenpage=$pagelist->nowindex;
		$aset['start']=($currenpage-1)*$aset['row'];
		$smarty->assign($aset['pagename'],$pagelist->show('wap'));
		$pagearr = array(
			'total'=>$total_count, //总数
			'perpage'=>$aset['row'], //每页显示
			'nowpage'=>$currenpage, //当前页
			'totalpage'=>$pagelist->totalpage, //总页数
			'url'=>$pagelist->page_url, //总页数
		);
		$smarty->assign($aset['pagename'].'_info',$pagearr);
	}
	$limit=" LIMIT ".abs($aset['start']).','.$aset['row'];
	$result = $db->query("SELECT * FROM ".table('diy_'.$aset['module'])." ".$wheresql.$orderbysql.$limit);
	$list= array();
	$__n= 1;$_timearrid = '';
	while($row = $db->fetch_array($result))
	{
		$_timearrid.= $row['id'].',';
		$row['_n']=$__n+($currenpage*$aset['row'])-$aset['row'];
		$row['title_']=$row['title'];
		$row['title']=cut_str($row['title'],$aset['titlelen'],0,$aset['dot']);
		$_urlarr = array(
			'm'=>'neirong',
			'c'=>'show',
			'catid'=>$row['catid'],
			'id'=>$row['id'],
			'sid'=>$_GET['sid'],
			'vs'=>$_GET['vs'],
		);
		$row['url'] = url_rewrite($aset['showname'], $_urlarr);
		$row['description']=str_replace('&nbsp;','',$row['description']);
		$row['briefly_']=strip_tags($row['description']);
		if ($row['thumb']){
			$row['thumb'] = string2array($row['thumb']);
		}
		if ($aset['infolen']>0){
			$row['briefly']=cut_str(strip_tags($row['description']),$aset['infolen'],0,$aset['dot']);
		}
		switch ($row['status']){
			case '99':
				$row['status_cn'] = '[待审]';
				break;
			case '98':
				$row['status_cn'] = '[草稿]';
				break;
			case '0':
				$row['status_cn'] = '[退稿]';
				break;
			default:
				$row['status_cn'] = '';
		}
		$__n ++;
		$list[] = $row;
	}
	if ($aset['listname_id']) $smarty->assign($aset['listname_id'],rtrim($_timearrid,','));
	$smarty->assign($aset['listname'],$list);
}

function __bbsorder($_order) {
	switch ($_order) {
		case 're':
			$orderbysql = " ORDER BY `dingzhi`DESC ,`read` DESC ,`inputtime` DESC";
			break;
		case 'jing':
			$orderbysql = " ORDER BY `jinghua` DESC ,`dingzhi`DESC ,`inputtime` DESC";
			break;
		case 'xin':
			$orderbysql = " ORDER BY `inputtime` DESC";
			break;
		case 'tu':
			$orderbysql = " ORDER BY (case when (`subtitle` like '%图%' OR `subtitle` like '%组%') then 0 else 1 end),`inputtime` DESC";
			break;
		default:
			$orderbysql = " ORDER BY `dingzhi`DESC ,`replytime` DESC ,`inputtime` DESC";
			break;
	}
	return $orderbysql;
}
?>