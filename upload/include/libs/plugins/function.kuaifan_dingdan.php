<?php
function smarty_function_kuaifan_dingdan($params, &$smarty)
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
			case "会员ID":
				$aset['userid'] = $a[1];
				break;
			case "状态":
				$aset['status'] = $a[1];
				break;
			case "币种":
				$aset['price_type'] = $a[1];
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
	$aset['price_type']=($aset['price_type']=='积分' || $aset['price_type']=='point')?'point':'amount';
	$orderbysql=" ORDER BY inputtime DESC";
	$wheresql = '';
	$wheresql.=isset($aset['userid'])?' AND userid=\''.$aset['userid'].'\'':'';
	$wheresql.=isset($aset['status'])?' AND status=\''.$aset['status'].'\'':'';
	$wheresql.=isset($aset['price_type'])?' AND price_type=\''.$aset['price_type'].'\'':'';
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
		$total_sql="SELECT COUNT(*) AS num FROM ".table('dingdan').$wheresql;
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
	$result = $db->query("SELECT * FROM ".table('dingdan')." ".$wheresql.$orderbysql.$limit);
	$list= array();
	$__n= 1;
	while($row = $db->fetch_array($result))
	{
		$row['_n']=$__n+($currenpage*$aset['row'])-$aset['row'];
		$row['price_type_cn']=($row['price_type']=='point')?'积分':$_CFG['amountname'];
		$row['title']=cut_str($row['title'],$aset['titlelen'],0,$aset['dot']);
		if ($row['status']=='0'){ //0正常(下单未付款)，1已付款，2已发货，10已收货(交易成功)，99关闭交易
			$row['status_cn'] = '未付款,等待付款';
		}elseif ($row['status']=='1'){
			$row['status_cn'] = '已付款,等待发货';
		}elseif ($row['status']=='2'){
			$row['status_cn'] = '已发货,等待收货';
		}elseif ($row['status']=='10'){
			$row['status_cn'] = '交易成功';
		}elseif ($row['status']=='99'){
			$row['status_cn'] = '交易关闭('.cut_str($row['status_close'],20,0,'...').')';
		}else{
			$row['status_cn'] = '交易失败';
		}
		$__n ++;
		$list[] = $row;
	}
	$smarty->assign($aset['listname'],$list);
}
?>