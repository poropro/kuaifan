<?php
/*
 * 邮箱配置
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');


if ($_GET['thisname']){
	$wheresql = " WHERE type = 'templates' and title = '{$_GET['thisname']}'";
	$val = $db -> getone("select * from ".table('youxiang').$wheresql." LIMIT 1");
	$smarty->assign('muban',string2array($val['body']));
	if (!empty($_POST['dosubmit'])){
		$mailset['smtp_title'] = $_POST['smtp_title'];
		$mailset['smtp_body'] = $_POST['smtp_body'];

		$mailsql['type'] = 'templates';
		$mailsql['title'] = $_GET['thisname'];
		$mailsql['body'] = array2string($mailset);
		if ($val){
			$valok = updatetable(table('youxiang'), $mailsql, " id = {$val['id']}");
		}else{
			$valok = inserttable(table('youxiang'),$mailsql);
			$smarty->assign('muban', $mailset);
		}
		$links[0]['title'] = '继续修改';
		$links[0]['href'] = get_link();
		$links[1]['title'] = '返回配置页';
		$links[1]['href'] = get_link('thisname|smstitle');
		$links[2]['title'] = '返回后台首页';
		$links[2]['href'] = $_admin_indexurl;
		if ($valok){
			admin_log("修改邮箱模板。", $admin_val['name']);
			showmsg("系统提醒", "保存成功！", $links);
		}else{
			showmsg("系统提醒", "保存失败！", $links);
		}
	}
}else{
	$wheresql = " WHERE type = 'mail'";
	$val = $db -> getone("select * from ".table('youxiang').$wheresql." LIMIT 1");
	$smarty->assign('youxiang',string2array($val['body']));

	$wheresql = " WHERE type = 'rule'";
	$val_r = $db -> getone("select * from ".table('youxiang').$wheresql." LIMIT 1");
	$smarty->assign('guize',string2array($val_r['body']));

	if (!empty($_POST['dosubmit'])){
		if ($_POST['subtype'] == '1'){ //通过连接 SMTP 服务器发送邮件
			$mailset['smtp_servers'] = $_POST['smtp_servers'];
			$mailset['smtp_username'] = $_POST['smtp_username'];
			$mailset['smtp_password'] = $_POST['smtp_password'];
			$mailset['smtp_from'] = $_POST['smtp_from'];
			$mailset['smtp_port'] = $_POST['smtp_port'];
			$mailset['smtp_method'] = $_POST['smtp_method'];

			$mailsql['type'] = 'mail';
			$mailsql['body'] = array2string($mailset);
			if ($val){
				$valok = updatetable(table('youxiang'), $mailsql, " id = {$val['id']}");
			}else{
				$valok = inserttable(table('youxiang'),$mailsql);
				$smarty->assign('youxiang', $mailset);
			}
			$links[0]['title'] = '继续配置';
			$links[0]['href'] = get_link();
			$links[1]['title'] = '返回后台首页';
			$links[1]['href'] = $_admin_indexurl;
			if ($valok){
				admin_log("修改邮箱配置。", $admin_val['name']);
				showmsg("系统提醒", "保存成功！", $links);
			}else{
				showmsg("系统提醒", "保存失败！", $links);
			}
		}elseif ($_POST['subtype'] == '2'){ //发送测试邮件
			$links[0]['title'] = '返回继续';
			$links[0]['href'] = get_link();
			$links[1]['title'] = '返回后台首页';
			$links[1]['href'] = $_admin_indexurl;
			$txt="您好！这是一封检测邮件服务器设置的测试邮件。收到此邮件，意味着您的邮件服务器设置正确！您可以进行其它邮件发送的操作了！";
			$check_smtp=trim($_POST['check_smtp'])?trim($_POST['check_smtp']):showmsg("系统提醒", "收件人地址必须填写！", $links);
			if (!preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/",$check_smtp))showmsg("系统提醒", "email格式错误！", $links);
			if (smtp_mail($check_smtp,"快范wapCMS测试邮件",$txt)){
				admin_log("测试发送邮箱给：{$_POST['check_smtp']}。", $admin_val['name']);
				showmsg("系统提醒", "测试邮件发送成功！", $links);
			}else{
				showmsg("系统提醒", "测试邮件发送失败！", $links);
			}
		}elseif ($_POST['subtype'] == '3'){ //发送规则
			$mailset['mail_set_reg'] = $_POST['mail_set_reg'];
			$mailset['mail_set_editpwd'] = $_POST['mail_set_editpwd'];
			$mailset['mail_set_renzheng'] = $_POST['mail_set_renzheng'];
			$mailset['mail_set_zhaohui'] = $_POST['mail_set_zhaohui'];
			$mailset['mail_set_order'] = $_POST['mail_set_order'];
			$mailset['mail_set_payment'] = $_POST['mail_set_payment'];

			$mailsql['type'] = 'rule';
			$mailsql['body'] = array2string($mailset);
			if ($val_r){
				$valok = updatetable(table('youxiang'), $mailsql, " id = {$val_r['id']}");
			}else{
				$valok = inserttable(table('youxiang'),$mailsql);
				$smarty->assign('guize', $mailset);
			}
			$links[0]['title'] = '继续配置';
			$links[0]['href'] = get_link();
			$links[1]['title'] = '返回后台首页';
			$links[1]['href'] = $_admin_indexurl;
			if ($valok){
				write_static_cache( KF_ROOT_PATH. "caches/caches_email/cache_mail_rule.php",$mailset);
				admin_log("修改邮箱发送规则。", $admin_val['name']);
				showmsg("系统提醒", "保存成功！", $links);
			}else{
				showmsg("系统提醒", "保存失败！", $links);
			}
		}
	}
}

?>