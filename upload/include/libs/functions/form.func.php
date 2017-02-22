<?php
/*
 * cms 发布内容函数
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
 * Enter description here ...
 * @param $value 类型
 * @param $array 字段详情数组
 * @param $__line 分割线
 * @param $__vs 版本
 * @param $__edit 编辑模式(编辑的内容ID)
 * @param $__catid 编辑的栏目ID
 */
function kuaifan_nr_form($value, $array, $__line = '', $__vs = 1, $__edit = '', $__catid = '') {
	global $huiyuan_val,$_CFG;
	if (empty($value)) {
		return $value;
	}
	$__setting = string2array($array['setting']);
	if ($__vs == 1) $__fenmiao = fenmiao();
	$__value = ($__vs != 1)?'<div style="border-bottom:1px solid #DAD6FC; margin:3px 0px;"></div>':'';
	$__value.= $array['name'];
	if ($value == 'box' && $__setting['boxtype']){
		$__value.= "[多选]";
	}
	if (intval($array['minlength']) > 0){
		$__value.= ($__vs != 1)?"<span style=\"color:#FF0000;\">*</span>":"*";
	}
	$__value.= ":";
	if ($array['tips']) $__value.= "(".$array['tips'].")";
	$__value.= "<br/>";
	if (trim($array['__defaultvalue']) != '') {
		$__setting['_defaultvalue'] = $__setting['defaultvalue'];
		$__setting['defaultvalue'] = $array['__defaultvalue'];
	}
	switch ($value)
	{
		case "catid": //栏目
			break;
		case "typeid": //类别
			break;
		case "title": //标题、关键词
			$__value.= format_form(array("set" => "输入框|名称:'".$array['field'].$__fenmiao."'","data_value" =>$__setting['defaultvalue']));
			break;
		case "keyword": //关键词
			$__value.= format_form(array("set" => "输入框|名称:'".$array['field'].$__fenmiao."'","data_value" =>$__setting['defaultvalue']));
			break;
		case "textarea": //长文本(通用) 、摘要、内容
			$___time = ($_GET['vs'] == 1 && $__vs == 1)?"输入框":"文本框";
			$kuangao = "";
			if ($___time == '文本框'){
				if (!empty($__setting['input_kuan'])){
					if (is_num($__setting['input_kuan'])){
						$__setting['input_kuan']=$__setting['input_kuan'].'px';
					}
					$kuangao.= 'width\:'.$__setting['input_kuan'].';';
				}
				if (!empty($__setting['input_gao'])){
					if (is_num($__setting['input_gao'])){
						$__setting['input_gao']=$__setting['input_gao'].'px';
					}
					$kuangao.= 'height\:'.$__setting['input_gao'].';';
				}
				//
				if (empty($kuangao)) {
					if ($array['field'] == "content"){
						$kuangao = ",style:'width\:98%;height\:150px;'";
					}else{
						$kuangao = ",style:'height\:38px;'";
					}
				}else{
					$kuangao = ",style:'".$kuangao."'";
				}
				if ($array['field'] == "content"){
					if (empty($__setting['nrbiaoqian'])) $kuangao.= ",'data-web':content";
				}
			}
			$__value.= format_form(array("set" => $___time."|名称:'".$array['field'].$__fenmiao."'".$kuangao, "data_value"=>to_content($__setting['defaultvalue'], '', '', $__setting['newline2br'])));
			if ($array['field'] == "content"){
				$__bbsna = "bbsinfo_".generate_password(3,22)."_";
				$__type  = $__edit?to_content($__setting['defaultvalue'],"type"):$_GET['type'];
				switch ($__type) {
					case 'yin':
						$__value.= "<br/><b>隐藏内容:</b>";
						$__value.= "<br/>".format_form(array("set" => $___time."|名称:'".$__bbsna."yin".$__fenmiao."'", "data_value"=>to_content($__setting['defaultvalue'],"yin")));
						break;
					case 'pai':
						$_paiarr =  explode("|", to_content($__setting['defaultvalue'],"pai"));
						$_paiarr[0] = $_paiarr[0]?$_paiarr[0]:"point";
						$__value.= "<br/><b>派币贴配置:</b>";
						$__value.= $__edit?"(不可修改)":"(发帖后不可修改)";
						$__value.= "<br/>总派发: ".format_form(array("set" => "输入框|名称:'".$__bbsna."pai".$__fenmiao."',宽:5", "data_value"=>$_paiarr[1]));
						$__value.= format_form(array("set" => "列表框|名称:'".$__bbsna."huobi".$__fenmiao."'", "__vs" => ($_GET['vs'] == 1 && $__vs == 1)?"1":"2", "list" => $_CFG['amountname'].':amount,积分:point', "default" => $_paiarr[0]));
						$__value.= "<br/>回复奖励: ".format_form(array("set" => "输入框|名称:'".$__bbsna."hui".$__fenmiao."',宽:5", "data_value"=>$_paiarr[2]));
						if (!$__edit) $__value.= US_USERID?"<br/>账户余额: {$_CFG['amountname']}{$huiyuan_val['amount']},积分".$huiyuan_val['point']:"";
						break;
					case 'xuan':
						$_xuanarr =  explode("|", to_content($__setting['defaultvalue'],"xuan"));
						$_xuanarr[0] = $_xuanarr[0]?$_xuanarr[0]:"point";
						$__value.= "<br/><b>悬赏贴配置:</b>";
						$__value.= $__edit?"(不可修改)":"(发帖后不可修改)";
						$__value.= "<br/>悬赏额度:".format_form(array("set" => "输入框|名称:'".$__bbsna."xuan".$__fenmiao."',宽:5", "data_value"=>$_xuanarr[1]));
						$__value.= format_form(array("set" => "列表框|名称:'".$__bbsna."huobi".$__fenmiao."'", "__vs" => ($_GET['vs'] == 1 && $__vs == 1)?"1":"2", "list" => $_CFG['amountname'].':amount,积分:point', "default" => $_xuanarr[0]));
						$__value.= "<br/>有效时间:".format_form(array("set" => "输入框|名称:'".$__bbsna."tian".$__fenmiao."',宽:5", "data_value"=>$_xuanarr[2]))."天";
						if (!$__edit) $__value.= US_USERID?"<br/>账户余额: {$_CFG['amountname']}{$huiyuan_val['amount']},积分".$huiyuan_val['point']:"";
						break;
					case 'mai':
						$_maiarr =  explode("|", to_content($__setting['defaultvalue'],"mai"));
						$_maiarr[0] = $_maiarr[0]?$_maiarr[0]:"point";
						$__value.= "<br/><b>收费贴配置:</b>";
						$__value.= "<br/>收费额度:".format_form(array("set" => "输入框|名称:'".$__bbsna."mai".$__fenmiao."',宽:5", "data_value"=>$_maiarr[1]));
						$__value.= format_form(array("set" => "列表框|名称:'".$__bbsna."huobi".$__fenmiao."'", "__vs" => ($_GET['vs'] == 1 && $__vs == 1)?"1":"2", "list" => $_CFG['amountname'].':amount,积分:point', "default" => $_maiarr[0]));
						$__value.= "<br/>收费内容:".format_form(array("set" => $___time."|名称:'".$__bbsna."maitxt".$__fenmiao."'", "data_value"=>$_maiarr[2]));
						break;
				}
			}
			break;
		case "datetime": //时间型(通用) 、更新时间、发布时间
			if ($__edit && $array['field']!='updatetime'){
				$___time = date($__setting['_defaultvalue'],$__setting['defaultvalue']);
			}else{
				$___time = date($__setting['defaultvalue'],time());
			}
			//$___time = str_replace(':','\:',$___time);
			$__value.= format_form(array("set" => "输入框|名称:'".$array['field'].$__fenmiao."'","data_value" =>$___time));
			break;
		case "image": //缩略图
			if ($__setting['defaultvalue'] && $__edit) {
				$__array = string2array($__setting['defaultvalue']);
				$__value.= "[<a href=\"".get_link("upfile|edit")."&amp;edit=".$__edit."&amp;upfile=".$array['field']."\">删</a>]";
				$__value.= "<a href=\"{$__array[0]}\" target=\"_blank\">{$__array[0]}</a>";
				$__value.= ($_GET['vs'] == 3)?"<br style='display: block;'/>":"<br/>";
			}
			$__value.= format_form(array("set" => "文件|名称:'".$array['field'].$__fenmiao."',值:'".$___time."'"));
			break;
		case "text": //短文本(通用) 、URL、用户名
			$__value.= format_form(array("set" => "输入框|名称:'".$array['field'].$__fenmiao."'","data_value" =>$__setting['defaultvalue']));
			break;
		case "number": //数字型(通用) 、排序
			$__value.= format_form(array("set" => "输入框|名称:'".$array['field'].$__fenmiao."'","data_value" =>$__setting['defaultvalue']));
			break;
		case "box": //选项型(通用) 、状态、允许评论
			$__time = str_replace('|',':',$__setting['options']);
			$__time = str_replace('\r\n',',',$__time);
			$__time = str_replace(chr(13).chr(10),',',$__time);
			$__time = str_replace(chr(13),',',$__time);
			$__time = str_replace(chr(10),'',$__time);
			if ($__edit) $__setting['defaultvalue'] = trim($array['__defaultvalue']);
			$__value.= format_form(array("set" => "列表框|名称:'".$array['field'].$__fenmiao."'","list" => $__time,"__vs" => ($_GET['vs'] == 1 && $__vs == 1)?"1":"2", "boxtype" =>$__setting['boxtype'], "default" => $__setting['defaultvalue']));
			break;
		case "islink": //转向链接
			$__value.= format_form(array("set" => "输入框|名称:'".$array['field'].$__fenmiao."'","data_value" =>$__setting['defaultvalue']));
			break;
		case "readpoint": //阅读收费
			$__value.= format_form(array("set" => "输入框|名称:'".$array['field'].$__fenmiao."',宽:12","data_value" =>$__setting['defaultvalue']));
			$__value.= format_form(array("set" => "列表框|名称:'paytype".$__fenmiao."'", "list" => '积分:0,"'.$_CFG['amountname'].'":1', "__vs" => ($_GET['vs'] == 1 && $__vs == 1)?"1":"2", "default" => $array['_paytype']?$array['_paytype']:"0"));
			break;
		case "relation": //相关文章
			break;
		case "pages": //分页字数
			$__value.= format_form(array("set" => "输入框|名称:'".$array['field'].$__fenmiao."'","data_value" =>$__setting['defaultvalue']));
			break;
		case "groupid": //阅读权限
			break;
		case "template": //内容页模板
			break;
		case "downfile": //文件上传(通用)
			if ($__edit){
				if ($__setting['pathlist']){
					make_dir(KF_ROOT_PATH."uploadfiles/content/{$__catid}/{$__edit}/");
					$__value.= "(目录储存:  <u>{$_CFG['site_dir']}/uploadfiles/content/{$__catid}/{$__edit}/</u> )";
				}else{
					$__time = string2array($__setting['defaultvalue']);
					$__time = is_array($__time)?count($__time):'0';
					$__value.= "(<a href=\"".get_link("upfile|edit")."&amp;edit=".$__edit."&amp;upfile=".$array['field']."\"><u>进入管理上传文件[{$__time}]</u></a>)";
				}
			}else{
				if ($__vs == 3) {
					if (empty($max_size)) {
						$max_size = intval(ini_get('upload_max_filesize')*1024);
						$upconfig = getcache(KF_ROOT_PATH. "caches/caches_peizhi_mokuai/cache.fujian.php");
						if ($upconfig['upload_maxsize'] > 0 && $upconfig['upload_maxsize'] < $max_size) $max_size = $upconfig['upload_maxsize'];
					}
					$array['upload_allowext'] = $array['upload_allowext']?$array['upload_allowext']:'rar|zip|jar|apk|7z';
					$__value.= __kuaifan_nr_form_affix('js');
					$__value.= "<span class='form-downfile' onClick='formaffix(\"{$array['field']}\",\"file\", \"{$array['name']}\", \"".intval($array['upload_number'])."\")'>添加文件</span>";
					$__value.= "<span id='allowext-{$array['field']}' style='display:none;'>{$array['upload_allowext']}</span>";
					$__value.= "<div class='form-none' id='affix-{$array['field']}'></div>";
				}else{
					$__value.= "<u>(发布内容后上传文件)</u>";
				}
			}
			break;
		case "images": //图片上传(通用)
			if ($__edit){
				if ($__setting['pathlist']){
					make_dir(KF_ROOT_PATH."uploadfiles/content/{$__catid}/{$__edit}/");
					$__value.= "(目录储存:  <u>{$_CFG['site_dir']}/uploadfiles/content/{$__catid}/{$__edit}/</u> )";
				}else{
					$__time = string2array($__setting['defaultvalue']);
					$__time = is_array($__time)?count($__time):'0';
					$__value.= "(<a href=\"".get_link("upfile|edit")."&amp;edit=".$__edit."&amp;upfile=".$array['field']."\"><u>进入管理上传图片[{$__time}]</u></a>)";
				}
			}else{
				if ($__vs == 3) {
					if (empty($max_size)) {
						$max_size = intval(ini_get('upload_max_filesize')*1024);
						$upconfig = getcache(KF_ROOT_PATH. "caches/caches_peizhi_mokuai/cache.fujian.php");
						if ($upconfig['upload_maxsize'] > 0 && $upconfig['upload_maxsize'] < $max_size) $max_size = $upconfig['upload_maxsize'];
					}
					$array['upload_allowext'] = $array['upload_allowext']?$array['upload_allowext']:'gif|jpg|jpeg|png|bmp';
					$__value.= __kuaifan_nr_form_affix('js');
					$__value.= "<span class='form-images' onClick='formaffix(\"{$array['field']}\",\"img\", \"{$array['name']}\", \"".intval($array['upload_number'])."\")'>添加图片</span>";
					$__value.= "<span id='allowext-{$array['field']}' style='display:none;'>{$array['upload_allowext']}</span>";
					$__value.= "<div class='form-none' id='affix-{$array['field']}'></div>";
				}else{
					$__value.= "<u>(发布内容后上传图片)</u>";
				}
			}
			break;
		case "wanneng": //万能字段
			$__value = $__setting['formtext'];
			//版本显示
			if (strpos($__value,"{/wap}")){
				kf_class::run_sys_func('ubb');
				$__value = preg_replace("/\{wap=(.+?)\}(.+?)\{\/wap\}/es", "ubb_wap('\\2','\\1',1)", $__value);
			}
			$__value = str_replace(array("{字段名}","{字段别名}","{字段提示}","{默认值}"), array($array['field'],$array['name'],$array['tips'],$__setting['defaultvalue']), $__value);
			break;
	}
	return $__value.$__line;
}
function __kuaifan_nr_form_affix($str) {
	global $_FORM_AFFIX;
	if ($str == 'js' && empty($_FORM_AFFIX[$str])) {
		$_affix = "<script type='text/javascript' src='".JS_PATH."nr_form.js'></script>";
		$max_size = intval(ini_get('upload_max_filesize')*1024);
		$upconfig = getcache(KF_ROOT_PATH. "caches/caches_peizhi_mokuai/cache.fujian.php");
		if ($upconfig['upload_maxsize'] > 0 && $upconfig['upload_maxsize'] < $max_size) $max_size = $upconfig['upload_maxsize'];
		$_affix.= "<span id='affix_max_size' style='display:none;'>{$max_size}</span>";
		$_FORM_AFFIX[$str] = "1";
	}
	return $_affix;
}
?>