<?php
function smarty_function_kuaifan_paiban_show($params, &$smarty)
{
	global $db;
	$arr=explode(',',$params['set']);
	foreach($arr as $str)
	{
	$a=explode(':',$str);
		switch ($a[0])
		{
		case "ID":
			$aset['id'] = $a[1];
			break;
		case "列表名":
			$aset['listname'] = $a[1];
			break;
		case "回收站":
			$aset['delid'] = $a[1];
			break;
		case "父级":
			$aset['fuji'] = $a[1];
			break;
		}
	}
	$aset=array_map("get_smarty_request",$aset);
	$aset['listname']=$aset['listname']?$aset['listname']:"list";
	$wheresql=" WHERE id=".intval($aset['id'])."";
	if (!$aset['delid']) $wheresql.=" AND del=0";
	$sql = "select * from ".table('paiban')." {$wheresql} LIMIT  1";
	$val = $db->getone($sql);
	if (empty($val)){	
    $smarty->assign($aset['listname'],array());
	}else{
    $val['title__'] = $val['title'];
    $val['title'] = htmlspecialchars($val['title__']);
    $val['body'] = string2array($val['body']);
    $parentid = $val['parentid'];
    $val['parent'] = array();
    if (!empty($aset['fuji']) && $val['parentid']){
      for ($i=1; $i<=10; $i++) {
      $wheresql=" WHERE id=".intval($parentid)."";
      if (!$aset['delid']) $wheresql.=" AND del=0";
        $sql2 = "select * from ".table('paiban')." {$wheresql} LIMIT  1";
        $val2 = $db->getone($sql2);
        $val2['n'] = $i;
        $val2['body'] = string2array($val2['body']);
        $parentid = $val2['parentid'];
        $val['parent'][$i] = $val2;
        if (!$parentid) break;
      }
      $val['parent'] = array_reverse($val['parent']);
    }
    $smarty->assign($aset['listname'],$val);
	}
}
?>