<?php
function smarty_function_kuaifan_paiban($params, &$smarty)
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
			case "分类":
				$aset['parentid'] = $a[1];
				break;
			case "回收站":
				$aset['delid'] = $a[1];
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
			case "管理":
				$aset['isadmin'] = $a[1];
				break;
			case "排版":
				$aset['index'] = $a[1];
				break;
		}
	}
	if (is_array($aset)) $aset=array_map("get_smarty_request",$aset);
	$aset['listname']=isset($aset['listname'])?$aset['listname']:"list";
	$aset['row']=isset($aset['row'])?intval($aset['row']):10;
	$aset['start']=isset($aset['start'])?intval($aset['start']):0;
	$aset['titlelen']=isset($aset['titlelen'])?intval($aset['titlelen']):15;
	$aset['infolen']=isset($aset['infolen'])?intval($aset['infolen']):0;
	$aset['showname']=isset($aset['showname'])?$aset['showname']:'KF_paibanshow';
	$aset['listpage']=isset($aset['listpage'])?$aset['listpage']:'KF_paibanlist';
	$aset['pagename']=isset($aset['pagename'])?$aset['pagename']:'page';
	$aset['key_name']=isset($aset['key_name'])?$aset['key_name']:'key';
	$orderbysql=" ORDER BY `order` DESC ,id DESC";
	isset($aset['parentid'])?$wheresql.=" AND parentid=".intval($aset['parentid'])." ":'';
	if (!$aset['delid']) $wheresql.=" AND del=0";
	if (!empty($aset['keyname'])){
		$keyname=trim($aset['keyname']);
		$wheresql.=" AND title like '%{$keyname}%'";
		$_GET[$aset['key_name']] = $keyname;
	}
	if (!empty($wheresql)){
		$wheresql=" WHERE ".ltrim(ltrim($wheresql),'AND');
	}
	if (isset($aset['paged']))
	{
		kf_class::run_sys_class('page','',0);
		$total_sql="SELECT COUNT(*) AS num FROM ".table('paiban').$wheresql;
		$total_count=$db->get_total($total_sql);
		if ($aset['isadmin']){
			$pagelist = new page(array('total'=>$total_count, 'perpage'=>$aset['row'],'getarray'=>$_GET,'page_name'=>$aset['page_name']));
		}else{
			$pagelist = new page(array('total'=>$total_count, 'perpage'=>$aset['row'],'alias'=>$aset['listpage'],'getarray'=>$_GET,'page_name'=>$aset['page_name']));
		}
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
	$result = $db->query("SELECT * FROM ".table('paiban')." ".$wheresql.$orderbysql.$limit);
	$list= array();
	$__n = 1; $__seo_head = '';
	while($row = $db->fetch_array($result))
	{
		$row['_n']=$__n+($currenpage*$aset['row'])-$aset['row'];
		$row['title_']=$row['title'];
		$row['title__']=cut_str($row['title'],$aset['titlelen'],0,$aset['dot']);
		$row['title']=htmlspecialchars($row['title__']);
		$row['url'] = url_rewrite($aset['showname'],array('id'=>$row['id']));
		$row['body2'] = string2array($row['body']);
		$row['body']=str_replace('&nbsp;','',$row['body2']['body']);
		$row['briefly_']=strip_tags($row['body']);
		if ($aset['infolen']>0)
		{
			$row['briefly']=cut_str(strip_tags($row['body']),$aset['infolen'],0,$aset['dot']);
		}
		//前台排版功能
		/*
		if ($aset['index']=='1'){
			$row['index'] = '';
			$_time = 0;
			if (!empty($row['wap'])){ //版本要求
				if (!in_array($_GET['vs'],explode(',',$row['wap']))) $_time = 1;
			}
			if ($row['islogin']=='2' && US_USERID > 0) $_time = 1; //要求未登录 但已登录
			if ($row['islogin']=='1' && US_USERID < 1) $_time = 1; //要求登录 但未登录
			if ($row['hide']=='1') $_time = 1; //隐藏显示
			
			if ($_time == 0){
				if ($row['type_en'] == 'head') {
					$__seo_head.= ubb($row['qianmian']);
				}else{
					$row['index'].= ubb($row['qianmian']);
				}
				switch ($row['type_en'])
				{
					case "wenben":
						$row['indextype'] = '文本显示';
						$row['index'].= strip_tags($row['body2']['body']);
						break;
					case "chaolian":
						$row['indextype'] = '超级链接';
						$row['index'].= '<a href="'.ubb_link($row['body2']['link']).'">'.htmlspecialchars($row['body2']['title']).'</a>';
						break;
					case "tupian":
						$row['indextype'] = '图片显示';
						$row['index'].= '<img src="'.ubb_link($row['body2']['picurl']).'" alt="'.htmlspecialchars($row['body2']['title']).'"/>';
						break;
					case "tulian":
						$row['indextype'] = '图片链接';
						$row['index'].= '<a href="'.ubb_link($row['body2']['link']).'"><img src="'.ubb_link($row['body2']['picurl']).'" alt="'.htmlspecialchars($row['body2']['title']).'"/></a>';
						break;
					case "ubb":
						$row['indextype'] = 'UBB标签';
						$row['index'].= ubb($row['body2']['body']);
						break;
					case "wml":
						$row['indextype'] = 'WML标签';
						$row['index'].= wml($row['body2']['body']);
						break;
					case "beta":
						$row['indextype'] = '页面切换';
						$row['index'].= format_beta($row['body2']['title'], $row['body2']['body'], $row['body2']['cut'], $row['body2']['dot']);
						break;
					case "page":
						$row['indextype'] = '新的页面';
						//get_link('vs|sid|m', '', '1').'&amp;id='.$row['id'];
						$row['index'].= '<a href="'.url_rewrite('KF_paibanpage',array('m'=>'index','id'=>$row['id'],'sid'=>$_GET['sid'],'vs'=>$_GET['vs'])).'">'.htmlspecialchars($row['body2']['title']).'</a>';
						break;
					case "head":
						$row['indextype'] = 'head内信息';
						$__seo_head.= wml($row['body2']['body']);
						break;
					case "nrliebiao":
						$row['indextype'] = '内容列表';
						$data = getcache(KF_ROOT_PATH.'caches/column/cache.'.$_CFG['site'].'.php');
						$_body_arr = $data[$row['body2']['title']];
						if ($_body_arr){
							$_temp_arr = $row['body2']['template'];
							$_where_arr = "SELECT * FROM ".table('diy_'.$_body_arr['module'])." WHERE catid=".intval($row['body2']['title'])." ";
							if ($row['body2']['order']=='rand'){
								$_where_arr.= "ORDER BY rand() ";
							}elseif ($row['body2']['order']){
								$_where_arr.= "ORDER BY ".$row['body2']['order']." ";
							}
							$_where_arr.= ($row['body2']['asc']=='asc')?"asc ":"desc ";
							$_where_arr.= (intval($row['body2']['body']))?"LIMIT 0, ".intval($row['body2']['body'])." ":"";
							$_time_arr = $db->getall($_where_arr); $_n = 1;
							foreach ($_time_arr as $_val){
								if (intval($row['body2']['cut'])>0) $_val['title'] = cut_str($_val['title'],intval($row['body2']['cut']),0,$row['body2']['dot']);
								$_val['title'] = htmlspecialchars($_val['title']);
								//$_val['url'] = get_link('vs|sid|m', '', '1').'&amp;c=neirong&amp;showid='.$_val['id'];
								$_urlarr = array(
									'm'=>'neirong',
									'c'=>'show',
									'catid'=>$_val['catid'],
									'id'=>$_val['id'],
									'sid'=>$_GET['sid'],
									'vs'=>$_GET['vs'],
								);
								$_val['url'] = url_rewrite('KF_neirongshow', $_urlarr);
								if ($_temp_arr){
									$thumb_arr = string2array($_val['thumb']);
									$_temp_val = $_temp_arr;
									$_temp_val = str_replace('{n}',$_n,$_temp_val);
									$_temp_val = str_replace('{id}',$_val['id'],$_temp_val);
									$_temp_val = str_replace('{url}',$_val['url'],$_temp_val);
									$_temp_val = str_replace('{title}',$_val['title'],$_temp_val);
									if ($thumb_arr){
										$_temp_val = str_replace('{thumb}',$thumb_arr[0],$_temp_val);
										$_temp_val = str_replace('{thumbsmall}',$thumb_arr[48],$_temp_val);
										$_temp_val = str_replace('{thumbbig}',$thumb_arr[100],$_temp_val);
									}else{
										$_temp_val = str_replace('{thumb}','',$_temp_val);
										$_temp_val = str_replace('{thumbsmall}','',$_temp_val);
										$_temp_val = str_replace('{thumbbig}','',$_temp_val);
									}
									if ($_val['description']) {
										$_temp_val = str_replace('{description}',cut_str(strip_tags($_val['description'],200,0,$aset['dot'])),$_temp_val);
									}else{
										$_temp_val = str_replace('{description}','',$_temp_val);
									}
									$_temp_val = preg_replace("/\{addtime\([\'|\"|](.+?)[\'|\"|]\)\}/e","date('\\1',".$_val['inputtime'].")",$_temp_val); 
									$_temp_val = str_replace('{addtime}',date('Y-m-d H:i:s',$_val['inputtime']),$_temp_val);
									if ($_val['updatetime']){
										$_temp_val = preg_replace("/\{uptime\([\'|\"|](.+?)[\'|\"|]\)\}/e","date('\\1',".$_val['inputtime'].")",$_temp_val); 
										$_temp_val = str_replace('{uptime}',date('Y-m-d H:i:s',$_val['updatetime']),$_temp_val);
									}else{
										$_temp_val = str_replace('{uptime}','',$_temp_val);
									}
									$row['index'].= $_temp_val;
								}else{
									$row['index'].= "<a href=\"{$_val['url']}\">".$_val['title']."</a><br/>";
								}
								$_n++;
							}
						}
						unset($_time_arr);
						break;
					case "nrlanmu":
						$row['indextype'] = '内容栏目';
						$data = getcache(KF_ROOT_PATH.'caches/column/cache.'.$_CFG['site'].'.php');
						$_body_arr = $data[$row['body2']['title']];
						if ($_body_arr){
							$_temp_arr = $row['body2']['template'];
							$_time_arr = array();
							if (!$row['body2']['body'] && $_body_arr['arrchildid']){
								$__arr = explode(',', $_body_arr['arrchildid']);sort($__arr);
								foreach ($__arr as $_k => $_val){
									$_time_arr[$_k] = $data[$_val];
								}
							}else{
								$_time_arr[$row['body2']['title']] = $_body_arr;
								
							}$_n = 1;
							foreach ($_time_arr as $_k => $_val){
								if (intval($row['body2']['cut'])>0) $_val['title'] = cut_str($_val['title'],intval($row['body2']['cut']),0,$row['body2']['dot']);
								$_val['title'] = htmlspecialchars($_val['title']);
								//$_val['url'] = get_link('vs|sid|m', '', '1').'&amp;c=neirong&amp;listid='.$_val['id'];
								$_urlarr = array(
									'm'=>'neirong',
									'c'=>'list',
									'catid'=>$_val['id'],
									'sid'=>$_GET['sid'],
									'vs'=>$_GET['vs'],
								);
								$_val['url'] = url_rewrite('KF_neironglist', $_urlarr);
								if ($_temp_arr){
									$_temp_val = $_temp_arr;
									$_temp_val = str_replace('{n}',$_n,$_temp_val);
									$_temp_val = str_replace('{id}',$_val['id'],$_temp_val);
									$_temp_val = str_replace('{url}',$_val['url'],$_temp_val);
									$_temp_val = str_replace('{title}',$_val['title'],$_temp_val);
									$row['index'].= $_temp_val;
								}else{
									$row['index'].= "<a href=\"{$_val['url']}\">".$_val['title']."</a><br/>";
								}
								$_n++;
							}
						}
						//unset($data);
						break;
				}
				if ($row['type_en'] == 'head') {
					$__seo_head.= ubb($row['houmian']);
				}else{
					$row['index'].= ubb($row['houmian']);
				}
			}
		}*/
		$__n ++;
		$list[] = $row;
	}
	if ($__seo_head) $smarty->assign('__seo_head',$__seo_head);
	$smarty->assign($aset['listname'],$list);
}
?>