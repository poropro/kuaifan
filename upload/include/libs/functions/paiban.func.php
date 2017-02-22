<?php
/*
 * 排版相关函数
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
 * 快速选择地址
 * Enter description here ...
 * @param $set
 * @param $dot 地址后面添加，默认&amp;sid=[sid]&amp;vs=[vs]; -1不添加任何
 * @param $amp $amp显示方式，默认&amp;
 */
function mokuai_url($set = '', $dot = '', $amp = '&amp;'){
	$_url = "";
	if (!empty($set)){
		$mokuaiurl = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_mokuai_url.php');
		$_url.= $mokuaiurl[$set]['url'];
	} 
	if (substr($_url, 0, 5) == '&amp;') $_url = substr($_url, 5);
	if (substr($_url, 0, 1) == '&') $_url = substr($_url, 1);
	
	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';	
	$_url = $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$_SERVER['PHP_SELF'].'?'.$_url;
	
	if ($dot != '-1') $_url.= $dot?$dot:'&amp;sid=[sid]&amp;vs=[vs]';
	if ($amp) $_url = str_replace('&amp;', $amp, $_url);
	
	return $_url.$dot;
}

/**
 * 获取排版类型名称
 * Enter description here ...
 * @param $set
 */
function ltype($set){
	switch ($set){
		case "wenben":
			$_type = '文本显示';
			break;
		case "chaolian":
			$_type = '超级链接';
			break;
		case "tupian":
			$_type = '图片显示';
			break;
		case "tulian":
			$_type = '图片链接';
			break;
		case "ubb":
			$_type = 'UBB标签';
			break;
		case "wml":
			$_type = 'WML标签';
			break;
		case "beta":
			$_type = '页面切换';
			break;
		case "page":
			$_type = '新的页面';
			break;
		case "head":
			$_type = 'head内信息';
			break;
		case "nrliebiao":
			$_type = '内容列表';
			break;
		case "nrlanmu":
			$_type = '内容栏目';
			break;
		case "guanggao":
			$_type = '广告投放';
			break;
		case "lianjie":
			$_type = '友链调用';
			break;
		case "dongtai":
			$_type = '会员动态';
			break;
		case "muban":
			$_type = '模板标签';
			break;
	}
	return $_type;
}
?>