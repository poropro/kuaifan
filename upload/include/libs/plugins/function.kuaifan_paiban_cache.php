<?php
function smarty_function_kuaifan_paiban_cache($params, &$smarty)
{
	global $_CFG;
	$arr=explode(',',$params['set']);
	foreach($arr as $str)
	{
		$a=explode(':',$str);
		switch ($a[0])
		{
			case "列表名":
				$aset['listname'] = $a[1];
				break;
			case "页面ID":
				$aset['pageid'] = ($a[1]>0)?intval($a[1]):0;
				break;
		}
	}
	if (is_array($aset)) $aset=array_map("get_smarty_request",$aset);
	$aset['listname']=isset($aset['listname'])?$aset['listname']:"list";
	//
	$_data = getcache(KF_ROOT_PATH."caches/caches_paiban/cache.{$_CFG['site']}.php");
	$list = array(); $__seo_head = '';
	foreach ($_data as $_v){
		if (empty($_v)) continue ;
		//版本要求
		if (!empty($_v['wap'])){
			if (!in_array($_GET['vs'],explode(',',$_v['wap']))) continue ;
		}
		//隐藏显示
		if ($_v['hide']=='1') continue ;
		//页面要求(页面ID)
		if ($_v['parentid']!=$aset['pageid']) continue ;
		//要求登录 但未登录
		if ($_v['islogin']=='1' && US_USERID < 1) continue ;
		//要求未登录 但已登录
		if ($_v['islogin']=='2' && US_USERID > 0) continue ;
		
		$_v['body'] = string2array($_v['body']);
		if ($_v['type_en'] == 'head') {
			$__seo_head.= ubb($_v['qianmian']);
			$__seo_head.= wml($_v['body']['body']);
			$__seo_head.= ubb($_v['houmian']);
		}else{
			$list[] = array_merge(array('order' => $_v['order']), $_v);
		}
	}
	//排序
	arsort($list);
	//
	if ($__seo_head) $smarty->assign('__seo_head', $__seo_head);
	$smarty->assign($aset['listname'], $list);
}
?>