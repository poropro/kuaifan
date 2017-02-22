<?php
 /*
 * 神州行充值系统
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
define('SYS_URL', 'http://zf.kuaifan.net/index.php');

//函数
function chongzhi_ubbsys($str){
	return $str?preg_replace("/GET\[(.+?)\]/e","chongzhi_ubbget('\\1')",$str):'';
}
function chongzhi_ubbget($str){
	return $str?$_GET[$str]:'';
}
function chongzhi_open($url ,$start='', $end='') {
	$str = dfopen($url);
	if (!$start || !$end) return $str;
	return chongzhi_cut($str, $start, $end);
}
function chongzhi_cut($str, $start, $end) {
	if (!$start || !$end) return $str;
	$start = "{{$start}-";
	$end = "-{$end}}";
	$content = strstr( $str, $start );
	$content = substr( $content, strlen( $start ), strpos( $content, $end ) - strlen( $start ) );
	return ($content || $content=='0')?$content:$str;
}
//返回后台路径
$_admin_indexurl = get_link("allow|vs","",1).'&amp;m=admin';
$smarty->assign('admin_indexurl', $_admin_indexurl);

//获取系统数据
$row = $db->getone("select * from ".table('zhifu')." WHERE path='shenzhouxing' LIMIT 1");
if (empty($row)) showmsg("系统提醒", "此充值系统未安装完成！");
$row['setting'] = string2array($row['setting']);
$row['setting_field'] = string2array($row['setting_field']);

//未注册，自动注册
if (empty($row['key']) || strlen($row['key']) < 32) {
	$row['key'] = generate_password(32);
	$htmlid = chongzhi_open(SYS_URL."?m=zhuce&keyval={$row['key']}&url=".urlencode($_SERVER['HTTP_HOST']), 'keyidstart', 'keyidend');
	if (intval($htmlid) > 0){
		$db -> query("update ".table('zhifu')." set `key` = '{$row['key']}' WHERE path='shenzhouxing'");
	}else{
		showmsg("系统提醒", "参数错误，请稍后再试！");
	}
}

//写入文本
function write_static_jilu($cache_file_path, $config_arrval)
{
	$content = "<?php\r\n";
	$content .= "\$data = " . $config_arrval . ";\r\n";
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