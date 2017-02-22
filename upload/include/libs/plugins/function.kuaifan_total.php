<?php
function smarty_function_kuaifan_total($params, &$smarty)
{
	global $db;
	$arr=explode(',',$params['set']);
	foreach($arr as $str)
	{
		$a=explode(':',$str);
		switch ($a[0])
		{
			case "数据名":
				$aset['dbname'] = $a[1];
				break;
			case "数据表":
				$aset['tabledb'] = $a[1];
				break;
		}
	}
	if (is_array($aset)) $aset=array_map("get_smarty_request",$aset);
	$aset['dbname']=isset($aset['dbname'])?$aset['dbname']:"dbname";
	$wheresql = "";
	if ($params['where']) $wheresql.= " AND ".$params['where'];
	if (!empty($wheresql)){
		$wheresql= " WHERE ".ltrim(ltrim($wheresql),'AND');
	}
	$total_sql="SELECT COUNT(*) AS num FROM ".table($aset['tabledb']).$wheresql;
	$total_count=$db->get_total($total_sql);
	$smarty->assign($aset['dbname'],$total_count);
}
?>