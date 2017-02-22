<?php
/*
 * cms 订单模块相关函数
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

/**
 * 
 * 下订单
 * @param $title 			标题; array('title'=>标题,'titleurl'=>产品链接)
 * @param $price 			单价
 * @param $num 				数量
 * @param $userid 			买家ID
 * @param $touserid 		卖家ID、0为系统，默认0
 * @param $status_type 		0正常、1为付款立即交易成功、2为发货立即交易成功，默认:0
 * @param $paysql 			交易成功执行sql
 * @param $content 			备注说明，默认:为空
 * @param $type 			如:附件购买、积分购买、会员购买，默认:其他
 * @param $status 			0正常、1已付款、2已发货、10已收货、99关闭交易，默认:0
 * @param $price_type 		amount金币、point积分，默认:amount
 * @param $toadmin 			配合touserid=0使用；1为后台手动发货，0为自动，默认:0
 * @param $setting			其他
 */
function tianjia_dingdan($title, $price, $num, $userid, $touserid='', $status_type='', $paysql='', $content='', $type='', $status='', $price_type='', $toadmin='', $setting=''){
	global $db, $_CFG;
	$dingdan = array ();
	$dingdan['userid'] = intval($userid);		//买家
	$dingdan['touserid'] = intval($touserid);	//卖家
	if (is_array($title)){
		$dingdan['title'] = $title['title'];	//标题
		$dingdan['titleurl'] = goto_url($title['titleurl'], 'vs|sid', 1); //产品链接
	}else{
		$dingdan['title'] = $title;				//标题
	}
	$dingdan['content'] = $content;				//备注说明
	$dingdan['type'] = $type?$type:'其他';		//类型， 如: 附件购买、积分购买、会员购买
	$dingdan['status'] = intval($status);		//状态：0正常(下单未付款)，1已付款，2已发货，10已收货(交易成功)，99关闭交易
	$dingdan['status_type'] = intval($status_type)?intval($status_type):'0';	//状态：0默认、1为付款立即交易成功、2为发货立即交易成功。
	$dingdan['price'] = intval($price);			//单价
	$dingdan['price_type'] = $price_type?$price_type:'amount';	//货币类型: amount金币、point积分
	$dingdan['toadmin'] = $toadmin?$toadmin:'0';//配合touseri=0使用；1为后台手动发货，0为自动
	$dingdan['num'] = intval($num);				//购买数量
	$dingdan['inputtime'] = SYS_TIME;			//购买时间
	$dingdan['paytime'] = 0;					//付款时间
	$dingdan['oktime'] = 0;						//交易成功时间
	if (is_array($paysql)){
		$dingdan['paysql'] = mysql_escape_string($paysql['paysql']);	//交易成功后对买家执行sql
		$dingdan['paytosql'] = mysql_escape_string($paysql['paytosql']);	//交易成功后对卖家执行sql
		$dingdan['payfun'] = $paysql['payfun'];	//交易成功后对买家执行function
		$dingdan['paytofun'] = $paysql['paytofun'];	//交易成功后对卖家执行function
	}else{
		$dingdan['paysql'] = mysql_escape_string($paysql);					
	}
	if (is_array($setting)){
		$dingdan['setting'] = array2string($setting);	
	}elseif ($setting){
		$dingdan['setting'] = $setting;	
	}
	$dingdan['site'] = $_CFG['site'];
	$dingdanid = 0;
	if ($dingdan['userid'] && $dingdan['num']){
		$dingdanid = inserttable(table('dingdan'), $dingdan, true);
		if ($dingdanid > 0){
			//下订单发送邮件
			if (get_mail_yes('mail_set_order')){
				$_userinfo = $db->getone("select * from ".table('huiyuan')." WHERE userid='{$dingdan['userid']}' LIMIT 1");
				$dingdan['oid'] = $dingdanid;
				$dingdan['paymenttpye'] = ($dingdan['price_type']=='amount')?$_CFG['amountname']:'积分';
				$dingdan['amount'] = $dingdan['price']*$dingdan['num'];
				$_mail = get_mail('order',$dingdan,$_CFG);
				smtp_mail($_userinfo['email'],$_mail['smtp_title'],$_mail['smtp_body']);
			}
		}
	}
	return $dingdanid;
}

/**
 * 
 * 处理订单
 * @param $_id 订单ID
 * @param $_status 设为1付款，2发货，10收货(交易成功)，99关闭交易
 * @param $_close 关闭付款原因
 * @param $_tocontent 给卖家留言
 */
function dingdan_set($_id, $_status, $_close = '', $_tocontent = ''){
	global $db,$_CFG;
	$_drow = $db->getone("select * from ".table('dingdan')." WHERE id='{$_id}' LIMIT 1");
	if (empty($_drow)) showmsg("系统提醒", "此订单不存在！");
	$_info = array();
	$_info['status'] = $_status;
	switch ($_status) {
		case '1': //欲付款，判断是否在未付款状态
			if ($_drow['status'] != 0) showmsg("系统提醒", "此订单不在可付款状态！");
			$_info['paytime'] = SYS_TIME;
			if ($_drow['status_type'] == 1){ //付款立即交易成功
				$_info['status'] = 10;
			}
			//给卖家的留言
			if ($_tocontent) $_info['tocontent'] = $_tocontent;
			//付款后发送邮件
			if (get_mail_yes('mail_set_payment')){
				$_userinfo = $db->getone("select * from ".table('huiyuan')." WHERE userid='{$_drow['userid']}' LIMIT 1");
				$_drow['oid'] = $_id;
				$_drow['paymenttpye'] = ($_drow['price_type']=='amount')?$_CFG['amountname']:'积分';
				$_drow['amount'] = $_drow['price']*$_drow['num'];
				$_mail = get_mail('payment',$_drow,$_CFG);
				smtp_mail($_userinfo['email'],$_mail['smtp_title'],$_mail['smtp_body']);
			}
			break;
		case '2': //欲发货，判断是否在已付款状态
			if ($_drow['status'] != 1) showmsg("系统提醒", "此订单不在可发货状态！");
			if ($_drow['status_type'] == 2){ //发货立即交易成功
				$_info['status'] = 10;
			}
			break;
		case '10': //欲收货，判断是否在已发货状态
			if ($_drow['status'] != 2) showmsg("系统提醒", "此订单不在可确定收货状态！");
			break;
		case '99': //欲关闭，如果已经交易成功
			if ($_drow['status'] == 10) showmsg("系统提醒", "此订单不在可关闭状态！");
			$_info['oktime'] = SYS_TIME;
			//关闭理由
			if ($_close) $_info['status_close'] = $_close;
			break;
		default:
			showmsg("系统提醒", "参错错误！");
	}
	//交易成功
	if ($_info['status'] == 10){
		//对买家执行sql
		if ($_drow['paysql']) dingdan_sql($_drow['paysql']);
		//对买家执行function函数
		if ($_drow['payfun']) dingdan_fun($_drow['payfun']);
		//卖家增加
		if ($_drow['touserid'] > 0){
			$_price = $_drow['price']*$_drow['num'];
			$_price_type = ($_drow['price_type']=='point')?'0':'1';
			set_jiangfa($_drow['touserid'], $_price, $_price_type, '订单收款-'.$_drow['title']);
			//对卖家执行sql
			if ($_drow['paytosql']) dingdan_sql($_drow['paytosql']);
			//对卖家执行function函数
			if ($_drow['paytofun']) dingdan_fun($_drow['paytofun']);
		}
		$_info['oktime'] = SYS_TIME;
	}
	if (!updatetable(table('dingdan'), $_info, "id={$_drow['id']}")){
		return 0;
	}else{
		return $_info['status'];
	}
}
/**
 * 
 * 处理订单sql
 * @param $_sql
 */
function dingdan_sql($_sql) {
	global $db;
	if (empty($_sql)) return;
	$sql_arr = explode(';', $_sql);
	kf_class::run_sys_func('ubb');
	foreach ($sql_arr as $_val){
		if (strpos($_val,"{/ubb}")){
			$_val = preg_replace("/\{ubb\}(.+?)\{\/ubb\}/es","ubb('\\1')", $_val); 
		}
		if (!empty($_val)) $db -> query($_val);
	}
}
/**
 * 
 * 处理订单fun
 * @param $_fun
 */
function dingdan_fun($_fun){
	global $smarty;
	$_funarr = array();
	if (empty($_fun)) return;
	kf_class::run_sys_func('ubb');
	if (strpos($_val,"{/ubb}")){
		$_val = preg_replace("/\{ubb\}(.+?)\{\/ubb\}/es","ubb('\\1')", $_val);
	}
	$CacheDir = realpath($smarty->getCacheDir());
	$CacheDir.= '^'.strtolower(generate_password(40));
	$CacheDir.= '.dingdan.fun.php';
	dingdan_fun_cache($CacheDir, $_fun);
	if (file_exists($CacheDir)) {
		require_once $CacheDir;
		@unlink($CacheDir);
	}
}
/**
 * 
 * 写入文本
 * @param 文件路径  $cache_file_path
 * @param 写入文本  $config_val
 */
function dingdan_fun_cache($cache_file_path, $config_val)
{
	$content = "<?php\r\n";
	$content .= $config_val . ";\r\n";
	$content .= "?>";
	if (!file_put_contents($cache_file_path, $content, LOCK_EX))
	{
		$fp = @fopen($cache_file_path, 'wb+');
		if (!$fp)
		{
			exit('生成缓存文件失败');
		}
		if (!@fwrite($fp, trim($content)))
		{
			exit('生成缓存文件失败');
		}
		@fclose($fp);
	}
}
?>