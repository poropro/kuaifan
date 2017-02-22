<?php
/*
 * 全站搜索
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');
kf_class::run_sys_func('neirong');

$links[0]['title'] = '返回重建';
$links[0]['href'] = get_link("j|mo|id|pagesize|dosubmit");
$links[1]['title'] = '返回后台首页';
$links[1]['href'] = $_admin_indexurl;
if ($_REQUEST['dosubmit']){
	if ($_POST['dosubmit']) $db->query("Delete from ".table("sousuo"));
	$neirong_moxing = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_neirong_moxing.php');
	$pagesize = intval($_REQUEST['pagesize'])?intval($_REQUEST['pagesize']):100;
	$_moarr = explode('-', $_GET['mo']);
	$_mo = '';
	$_j = intval($_GET['j']);
	foreach ($neirong_moxing as $_k=>$_v){
		if (!in_array($_v['id'], $_moarr)){
			$_where = $_GET['id'] && $_j?" where id>".intval($_GET['id']):"";
			$total_sql="SELECT COUNT(*) AS num FROM ".table('diy_'.$_v['tablename']);
			$total_count=$db->get_total($total_sql);
			$_sql = "SELECT * FROM ".table('diy_'.$_v['tablename']).$_where." ORDER BY id asc LIMIT 0, ".($pagesize+1);
			$_arr = $db->getall($_sql);
			$n=1;
			foreach($_arr as $_val){
				$_val_data = $db->getone("SELECT * FROM ".table('diy_'.$_v['tablename'].'_data')." WHERE id=".$_val['id']);
				$fulltext = $_val['title'];
				$fulltext.= $_val['keywords']?$_val['keywords']:strip_tags($_val_data['content']);
				$fulldescription = $_val['description']?$_val['description']:substr(strip_tags($_val_data['content']),0,500);
				install_search($_val['id'], $_val['catid'], $_v['id'], $_val['title'], $fulltext, $_val['title'], $fulldescription, $_val['inputtime'], $_CFG['site']);
				if ($n >= $pagesize){
					//超过指定数量跳出
					$_j++;
					$_jj = $_j*$pagesize;
					if ($_jj>$total_count) $_jj = $total_count;
					$_mo = rtrim($_mo,'-');
					$_url = get_link('j|mo|id|pagesize|dosubmit')."&amp;j={$_j}&amp;mo={$_mo}&amp;id={$_val['id']}&amp;pagesize={$pagesize}&amp;dosubmit=1";
					showmsg("系统提醒", "正在重建【{$_v['title']}】模型({$_jj}/{$total_count})！", $links, $_url, 1);
					//
				}
				$n++;
			}
			$_j=0;
		}
		$_mo.= $_v['id'].'-';
	}
	showmsg("系统提醒", "建立完所有索引！", $links);
}
?>