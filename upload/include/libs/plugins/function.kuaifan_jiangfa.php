<?php
function smarty_function_kuaifan_jiangfa($params, &$smarty)
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
			case "ID列表":
				$aset['listname_id'] = $a[1];
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
			case "会员ID":
				$aset['userid'] = $a[1];
				break;
			case "币种":
				$aset['type'] = $a[1];
				break;
			case "类型":
				$aset['add'] = $a[1];
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
	$orderbysql=" ORDER BY time DESC";
	$wheresql = '`del`=0';
	$wheresql.=isset($aset['userid'])?' AND `userid`=\''.$aset['userid'].'\'':'';
	$wheresql.=isset($aset['type'])?' AND `type`=\''.$aset['type'].'\'':'';
	$wheresql.=isset($aset['add'])?' AND `add`=\''.$aset['add'].'\'':'';
	if (!empty($aset['keyname'])){
		$keyname=trim($aset['keyname']);
		$wheresql.=" AND (`title` like '%{$keyname}%')";
		$_GET[$aset['key_name']] = $keyname;
	}
	if (!empty($wheresql)){
		$wheresql=" WHERE ".ltrim(ltrim($wheresql),'AND');
	}
	if (isset($aset['paged']))
	{
		kf_class::run_sys_class('page','',0);
		$total_sql="SELECT COUNT(*) AS num FROM ".table('jiangfa').$wheresql;
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
	$result = $db->query("SELECT * FROM ".table('jiangfa')." ".$wheresql.$orderbysql.$limit);
	$list= array();
	$__n= 1;$_timearrid = '';
	while($row = $db->fetch_array($result))
	{
		$_timearrid.= $row['id'].',';
		$row['_n']=$__n+($currenpage*$aset['row'])-$aset['row'];
		$row['title_']=$row['title'];
		$row['title']=cut_str($row['title'],$aset['titlelen'],0,$aset['dot']);
		$row['type_cn']=$row['type']?'元':'分';
		$row['type_cn2']=$row['type']?$_CFG['amountname']:'积分';
		$row['add_cn']=($row['add']=='cut')?'支出':'收入';
		$row['add_cn2']=($row['add']=='cut')?'-':'+';
		$__n ++;
		$list[] = $row;
	}
	if ($aset['listname_id']) $smarty->assign($aset['listname_id'],rtrim($_timearrid,','));
	$smarty->assign($aset['listname'],$list);
}
?>