<?php
function smarty_function_kuaifan_bangzhu($params, &$smarty)
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
			case "l":
				$aset['where_l'] = $a[1];
				break;
			case "a":
				$aset['where_a'] = $a[1];
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
	$orderbysql=" ORDER BY id DESC";
	$wheresql = '';
	$wheresql.=isset($aset['where_l'])?' AND l=\''.$aset['where_l'].'\'':'';
	$wheresql.=isset($aset['where_a'])?' AND a=\''.$aset['where_a'].'\'':'';
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
		$total_sql="SELECT COUNT(*) AS num FROM ".table('bangzhu').$wheresql;
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
	$result = $db->query("SELECT * FROM ".table('bangzhu')." ".$wheresql.$orderbysql.$limit);
	$list= array();
	$__n= 1;
	while($row = $db->fetch_array($result))
	{
		if (!$row['v']) $row['v'] = '所有版本';
    if ($total_count == 1){
      $row['body'] = nl2br(htmlspecialchars($row['body']));
      $row['body'] = preg_replace("/\{ubb set=(.+?)\}/e","ubb('\\1')",$row['body']); 
      $smarty -> assign('get_val', $row);
      return "";
    }
		$row['_n']=$__n+($currenpage*$aset['row'])-$aset['row'];
		$row['title_']=$row['title'];
		$row['title']=cut_str($row['title'],$aset['titlelen'],0,$aset['dot']);
		$__n ++;
		$list[] = $row;
	}
	$smarty->assign($aset['listname'],$list);
}
?>