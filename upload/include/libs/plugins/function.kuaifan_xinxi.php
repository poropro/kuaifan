<?php
function smarty_function_kuaifan_xinxi($params, &$smarty)
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
			case "类型":
				$aset['status'] = $a[1];
				break;
			case "最大短消息数":
				$aset['allowmessage'] = explode("|", $a[1]);
				$aset['allowname'] = $aset['allowmessage'][0];
				$aset['allowmessage'] = $aset['allowmessage'][1];
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
	$orderbysql=" ORDER BY message_time DESC";
	$wheresql = ""; 
	$wheresql.= isset($aset['username'])?' AND send_to_id=\''.$aset['username'].'\'':'';
	$wheresql.= isset($aset['status'])?' AND status=\''.$aset['status'].'\'':'';
	if ($params['where']) $wheresql.=" AND ".$params['where'];
	if (!empty($aset['keyname'])){
		$keyname=trim($aset['keyname']);
		$wheresql.=" AND (`subject` like '%{$keyname}%')";
		$_GET[$aset['key_name']] = $keyname;
	}
	if (!empty($wheresql)){
		$wheresql=" WHERE ".ltrim(ltrim($wheresql),'AND');
	}
	if (isset($aset['paged']))
	{
		kf_class::run_sys_class('page','',0);
		$total_sql="SELECT COUNT(*) AS num FROM ".table('xinxi').$wheresql;
		$total_count=$db->get_total($total_sql);
		//最大短信息
		if ($aset['allowmessage'] > 0){
			if ($total_count > $aset['allowmessage']){
				$smarty->assign($aset['allowname'], "收信箱已满有(".intval($total_count-$aset['allowmessage']).")条新信息无法查收！<br/>");
				$total_count = $aset['allowmessage'];
				$row = $db->getone("SELECT * FROM ".table('xinxi')." ".$wheresql." ORDER BY message_time ASC LIMIT ".$total_count.",1");
				if (!empty($wheresql)){
					$wheresql.= " AND `messageid`<".$row['messageid'];
				}else{
					$wheresql = " WHERE `messageid`<".$row['messageid'];
				}
			}
		}
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
	$result = $db->query("SELECT * FROM ".table('xinxi')." ".$wheresql.$orderbysql.$limit);
	$list= array();
	$__n= 1;$_timearrid = '';
	while($row = $db->fetch_array($result))
	{
		$_timearrid.= $row['messageid'].',';
		$row['_n']=$__n+($currenpage*$aset['row'])-$aset['row'];
		$reply_num = $db->get_total("SELECT COUNT(*) AS num FROM ".table('xinxi')." WHERE replyid=".$row['messageid']);
		$row['reply_num'] = $reply_num;
		$row['reply_ok'] = $row['status']?"<b>未读</b>":"";
		$row['send_from_id'] = $row['send_from_id']?$row['send_from_id']:'系统';
		$__n ++;
		$list[] = $row;
	}
	if ($aset['listname_id']) $smarty->assign($aset['listname_id'],rtrim($_timearrid,','));
	$smarty->assign($aset['listname'],$list);
}
?>