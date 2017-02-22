<?php
function smarty_function_kuaifan_neirong_fabu($params, &$smarty)
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
			case "会员名":
				$aset['username'] = $a[1];
				break;
			case "状态":
				$aset['status'] = $a[1];
				break;
			case "栏目":
				$aset['catid'] = $a[1];
				break;
		}
	}
	$lanmudata = getcache(KF_ROOT_PATH.'caches/column/cache.'.$_CFG['site'].'.php');
	foreach($lanmudata as $k => $r) {$lanmudata[$k]['setting'] = string2array($r['setting']);}
	if (is_array($aset)) $aset=array_map("get_smarty_request",$aset);
	$aset['listname']=isset($aset['listname'])?$aset['listname']:"list";
	$aset['row']=isset($aset['row'])?intval($aset['row']):10;
	$aset['start']=isset($aset['start'])?intval($aset['start']):0;
	$aset['titlelen']=isset($aset['titlelen'])?intval($aset['titlelen']):15;
	$aset['pagename']=isset($aset['pagename'])?$aset['pagename']:'page';
	$aset['key_name']=isset($aset['key_name'])?$aset['key_name']:'key';
	$orderbysql=" ORDER BY inputtime DESC";
	$wheresql = ($aset['catid'] > 0)?' AND catid='.intval($aset['catid']):"";
	$wheresql.=isset($aset['username'])?' AND username=\''.$aset['username'].'\'':'';
	$wheresql.=isset($aset['status'])?' AND status=\''.$aset['status'].'\'':'';
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
		$total_sql="SELECT COUNT(*) AS num FROM ".table('neirong_fabu').$wheresql;
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
	$catid_url = get_link('sid|vs','',1)."&amp;m=neirong&amp;c=list&amp;catid=";
	$limit=" LIMIT ".abs($aset['start']).','.$aset['row'];
	$result = $db->query("SELECT * FROM ".table('neirong_fabu')." ".$wheresql.$orderbysql.$limit);
	$list= array();
	$__n= 1;
	while($row = $db->fetch_array($result))
	{
		$row['_n']=$__n+($currenpage*$aset['row'])-$aset['row'];
		$arr_checkid = explode('-',$row['checkid']);
		$row['id'] = $arr_checkid[1];
		$row['modelid'] = $arr_checkid[2];
		$row['title']=cut_str($row['title'],$aset['titlelen'],0,$aset['dot']);
		$row['catid_cn']=$lanmudata[$row['catid']]['title'];
		$row['catid_url']=$catid_url.$row['catid'];
		if ($row['status'] == 1){
			$row['status_txt'] = '通过';
			$row['url'] = url_rewrite('KF_neirongshow',array('m'=>'neirong','c'=>'show','catid'=>$row['catid'],'id'=>$row['id'],'sid'=>$_GET['sid'],'vs'=>$_GET['vs']));
			$row['bjurl'] = empty($lanmudata[$row['catid']]['setting']['shenhehou'])?"":get_link("a|checkid").'&amp;a=xiugai&amp;checkid='.$row['checkid'];
		}else{
			if ($row['status'] == 0){
				$row['status_txt'] = '<b>退稿</b>';
			}elseif ($row['status'] == 98){
				$row['status_txt'] = '草稿';
			}else{
				$row['status_txt'] = '审核中';
			}
			$row['url'] = "";
			$row['bjurl'] = get_link("a|checkid").'&amp;a=xiugai&amp;checkid='.$row['checkid'];
		}
		$__n ++;
		$list[] = $row;
	}
	$smarty->assign($aset['listname'],$list);
}
?>