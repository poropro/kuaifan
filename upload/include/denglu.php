<?php
/**
 * 会员必须登录判断文件
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');


//会员模块
if (US_USERID < 1){
	$url = get_link("vs|sid","",1);
	$links[0]['title'] = '返回上一页';
	$links[0]['href'] = -1;
		
	$links[1]['title'] = '登录';
	$links[1]['href'] = $url."&amp;m=huiyuan&amp;c=denglu&amp;go_url=".urlencode(get_link('','&','','',1));
	$links[1]['cut'] = "|";
		
	$links[2]['title'] = '注册';
	$links[2]['href'] = $url."&amp;m=huiyuan&amp;c=zhuce&amp;go_url=".urlencode(get_link('','&','','',1));
		
	$links[3]['title'] = '返回网站首页';
	$links[3]['href'] = kf_url('index');
	showmsg("系统提醒", "您访问的页面需要会员身份才可以进入！", $links, $links[1]['href']);
}

?>