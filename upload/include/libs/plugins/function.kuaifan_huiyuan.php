<?php
function smarty_function_kuaifan_huiyuan($params, &$smarty)
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
			case "详情":
				$aset['detail'] = $a[1];
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
			case "深度搜索":
				$aset['isdepth'] = $a[1];
				break;
			case "模型ID":
				$aset['modelid'] = $a[1];
				break;
			case "分类":
				$aset['groupid'] = $a[1];
				break;
			case "VIP":
				$aset['vip'] = $a[1];
				break;
			case "会员ID":
				$aset['userid'] = $a[1];
				break;
			case "会员名":
				$aset['username'] = $a[1];
				break;
			case "会员SID":
				$aset['usersid'] = $a[1];
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
	$orderbysql=" ORDER BY userid DESC";
	$wheresql = '';
	$wheresql.=isset($aset['modelid'])?' AND modelid=\''.$aset['modelid'].'\'':'';
	$wheresql.=isset($aset['groupid'])?' AND groupid=\''.$aset['groupid'].'\'':'';
	$wheresql.=isset($aset['vip'])?' AND vip=\''.$aset['vip'].'\'':'';
	$wheresql.=isset($aset['userid'])?' AND userid=\''.$aset['userid'].'\'':'';
	$wheresql.=isset($aset['usersid'])?' AND usersid=\''.$aset['usersid'].'\'':'';
	$wheresql.=isset($aset['username'])?' AND username=\''.$aset['username'].'\'':'';
	if (!empty($aset['keyname'])){
		$keyname=trim($aset['keyname']);
		if ($aset['isdepth']){
			$wheresql.=" AND (`username` like '%{$keyname}%' or `nickname` like '%{$keyname}%' or `userid`='{$keyname}' or `mobile`='{$keyname}')";
		}else{
			$wheresql.=" AND (`username` like '%{$keyname}%' or `nickname` like '%{$keyname}%')";
		}
		$_GET[$aset['key_name']] = $keyname;
	}
	if (!empty($wheresql)){
		$wheresql=" WHERE ".ltrim(ltrim($wheresql),'AND');
	}
	if (isset($aset['paged']))
	{
		kf_class::run_sys_class('page','',0);
		$total_sql="SELECT COUNT(*) AS num FROM ".table('huiyuan').$wheresql;
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
	$result = $db->query("SELECT * FROM ".table('huiyuan')." ".$wheresql.$orderbysql.$limit);
	$list= array();
	$__n= 1;$_timearrid = '';
	//
	$modellistarr = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_moxing.php');
	//
	while($row = $db->fetch_array($result))
	{
		$_timearrid.= $row['id'].',';
		$row['_n']=$__n+($currenpage*$aset['row'])-$aset['row'];
		if ($row['vip']){
			$row['vip_img'] = IMG_PATH.'icon/vip.gif';
			$row['vip_cn'] = 'vip会员';
		}elseif ($row['overduedate']){
			$row['vip_img'] = IMG_PATH.'icon/vip-expired.gif';
			$row['vip_cn'] = '过期vip';
		}
		$row['username_']=$row['username'];
		$row['username']=cut_str($row['username'],$aset['titlelen'],0,$aset['dot']);
		$row['nickname_']=$row['nickname'];
		$row['nickname']=cut_str($row['nickname'],$aset['titlelen'],0,$aset['dot']);
		if ($aset['detail']){
			$row['field'] = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'model'.DIRECTORY_SEPARATOR.'model_field_'.$row['modelid'].'.huiyuan.cache.php');
			$row['detail'] = $db -> getone("select * from ".table('huiyuan_diy_'.$modellistarr[$row['modelid']]['tablename'])." WHERE userid = {$row['userid']} LIMIT 1");
		}
		$__n ++;
		$list[] = $row;
	}
	if ($aset['listname_id']) $smarty->assign($aset['listname_id'],rtrim($_timearrid,','));
	$smarty->assign($aset['listname'],$list);
}
?>