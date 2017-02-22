<?php
/*
 * cms 排版模块相关函数
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
 * 排版
 * @param $_array
 */
function paiban_index($_array = array()){
	global $db,$_CFG,$smarty;
	if (empty($_array)) return ;

	if ($_array['nocache']){
		//首页缓存技术
		$nocache_time = ($_array['nocache'] < 1)?60:$_array['nocache']*60;
		$__LASTTIME = "<!-- KuaiFanHeaderTime:".$_array['lasttime']."; -->";
		$__KFSID = $_GET['sid'];
		$_GET['sid'] = "{:KF:TPL:SID:}";
		$_compile_dir = (US_USERID > 0)?1:0;
		$_compile_dir = $smarty->compile_dir.'_cache/index/'.$_CFG['site'].'-'.$_compile_dir.'-'.$_GET['vs'].'.'.$_array['id'].'.tpl';
		if (file_exists($_compile_dir)) {
			if (SYS_TIME - filemtime($_compile_dir) < $nocache_time) { //更新缓存频率
				$handle = @fopen($_compile_dir, "r");  
				if ($handle) { 
					$_badytxt = "";
					while(!feof($handle)){
						$_badytxt.= fread($handle, 1024);   
					}
					fclose($handle);
					if (strpos($_badytxt, $__LASTTIME)!==false){
						$_badytxt = str_replace($__LASTTIME, "", $_badytxt);
						$_GET['sid'] = $__KFSID;
						$_badytxt = str_replace("{:KF:TPL:SID:}", $_GET['sid'], $_badytxt);
						return $_badytxt;    
					}
				}
			}
		}
	}

	$badytxt = "";
	$bodyarr = $_array['body'];
	
	switch ($_array['type_en'])
	{
		case "wenben":
			//$_array['indextype'] = '文本显示';
			$badytxt.= strip_tags($bodyarr['body']);
			break;
		case "chaolian":
			//$_array['indextype'] = '超级链接';
			$badytxt.= '<a href="'.ubb_link($bodyarr['link']).'">'.htmlspecialchars($bodyarr['title']).'</a>';
			break;
		case "tupian":
			//$_array['indextype'] = '图片显示';
			$badytxt.= '<img src="'.ubb_link($bodyarr['picurl']).'" alt="'.htmlspecialchars($bodyarr['title']).'"/>';
			break;
		case "tulian":
			//$_array['indextype'] = '图片链接';
			$badytxt.= '<a href="'.ubb_link($bodyarr['link']).'"><img src="'.ubb_link($bodyarr['picurl']).'" alt="'.htmlspecialchars($bodyarr['title']).'"/></a>';
			break;
		case "ubb":
			//$_array['indextype'] = 'UBB标签';
			$badytxt.= ubb($bodyarr['body']);
			break;
		case "wml":
			//$_array['indextype'] = 'WML标签';
			$badytxt.= wml($bodyarr['body']);
			break;
		case "beta":
			//$_array['indextype'] = '页面切换';
			$badytxt.= format_beta($bodyarr['title'], $bodyarr['body'], $bodyarr['cut'], $bodyarr['dot']);
			break;
		case "page":
			//$_array['indextype'] = '新的页面';
			//get_link('vs|sid|m', '', '1').'&amp;id='.$_array['id'];
			$badytxt.= '<a href="'.url_rewrite('KF_paibanpage',array('m'=>'index','id'=>$_array['id'],'sid'=>$_GET['sid'],'vs'=>$_GET['vs'])).'">'.htmlspecialchars($bodyarr['title']).'</a>';
			break;
		case "head":
			//$_array['indextype'] = 'head内信息';
			//$__seo_head.= wml($bodyarr['body']);
			break;
		case "nrliebiao":
			//$_array['indextype'] = '内容列表';
			kf_class::run_sys_func('ubbneirong');
			$badytxt.= ubb_liebiao("{$bodyarr['title']}||{$bodyarr['body']}||{$bodyarr['cut']}||{$bodyarr['dot']}||{$bodyarr['order']}||{$bodyarr['asc']}||{$bodyarr['template']}||{$bodyarr['select']}");
			break;
		case "nrlanmu":
			//$_array['indextype'] = '内容栏目';
			kf_class::run_sys_func('ubbneirong');
			$badytxt.= ubb_lanmu("{$bodyarr['title']}||{$bodyarr['body']}||{$bodyarr['cut']}||{$bodyarr['dot']}||{$bodyarr['template']}");
			break;
		case "guanggao":
			//$_array['indextype'] = '广告投放';
			kf_class::run_sys_func('guanggao');
			$badytxt.= ubb_guanggao("{$bodyarr['title']}||{$bodyarr['cut']}||{$bodyarr['order']}||{$bodyarr['dot']}");
			break;
		case "lianjie":
			//$_array['indextype'] = '友链调用';
			kf_class::run_sys_func('lianjie');
			$badytxt.= ubb_lianjie("{$bodyarr['title']}||{$bodyarr['cut']}||{$bodyarr['body']}||{$bodyarr['order']}||{$bodyarr['dot']}||{$bodyarr['link']}");
			break;
		case "dongtai":
			//$_array['indextype'] = '会员动态';
			kf_class::run_sys_func('dongtai');
			$badytxt.= ubb_dongtai("{$bodyarr['body']}||{$bodyarr['cut']}||{$bodyarr['dot']}||{$bodyarr['asc']}||{$bodyarr['template']}");
			break;
		case "muban":
			//$_array['indextype'] = '模板标签';
			$_tmpfile = KF_ROOT_PATH.DIR_PATH.'index/caches_paiban/'.$_array['id'].'/'.$_array['lasttime'].'.caches';
			if (!file_exists($_tmpfile)) {
				kf_class::run_sys_func('admin');
				removeDir(dirname($_tmpfile));
				__paiban_write_static_cache($_tmpfile, $bodyarr['body']);
			}
			break;
		default :
			return ;
			break;
	}
	//
	$_badytxt = ubb($_array['qianmian']).$badytxt.ubb($_array['houmian']);
	if ($_array['nocache']){
		//首页缓存技术
		__paiban_write_static_cache($_compile_dir, $__LASTTIME.$_badytxt);
		$_GET['sid'] = $__KFSID;
		$_badytxt = str_replace("{:KF:TPL:SID:}", $_GET['sid'], $_badytxt);
	}
	return $_badytxt;
}
/**
 *写入文本
 */
function __paiban_write_static_cache($cache_file_path, $content){
	make_dir(dirname($cache_file_path));
	if (!file_put_contents($cache_file_path, $content, LOCK_EX))
	{
		$fp = @fopen($cache_file_path, 'wb+');
		if (!$fp)
		{
			exit('模板标签生成缓存文件失败');
		}
		if (!@fwrite($fp, trim($content)))
		{
			exit('模板标签生成缓存文件失败');
		}
		@fclose($fp);
	}
}
/**
 * 单个排版
 * @param $_id 项目ID
 */
function insert_paiban($_arr){
	global $_CFG;
	$_id = $_arr['id'];
	if (empty($_id)) return ;
	$_data = getcache(KF_ROOT_PATH."caches/caches_paiban/cache.{$_CFG['site']}.php");
	$_arr = $_data[$_id];
	$_arr['body'] = string2array($_arr['body']);
	if (empty($_arr)) return ;
	return paiban_index($_arr);
}
?>