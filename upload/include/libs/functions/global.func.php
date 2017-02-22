<?php
/**
 * global.func.php 公共函数库
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
if(!defined('IN_KUAIFAN')) exit('Access Denied!');
function addslashes_deep($value){
	if (empty($value)){
		return $value;
	}else{
		if (!get_magic_quotes_gpc()){
			$value=is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
		}
		$value=is_array($value) ? array_map('addslashes_deep', $value) : mystrip_tags($value);
		return $value;
	}
}
function mystrip_tags($string){
	$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);
	$string = strip_tags($string);
	return $string;
}
function table($table){
 	global $pre;
    return $pre .$table ;
}
function get_header($_vs = '1'){
	if (empty($_vs)) $_vs = $_GET['vs'];
	if ($_vs == 'wml' || $_vs == '1'){
		header("Content-Type:text/vnd.wap.wml;charset=".CHARSET);
	}else{
		header("Content-Type:text/html;charset=".CHARSET);
	}
}
function get_smarty_request($str){
	$str=rawurldecode($str);
	$strtrim=rtrim($str,']');
	if (substr($strtrim,0,4)=='GET['){
		$getkey=substr($strtrim,4);
		return $_GET[$getkey];
	}elseif (substr($strtrim,0,5)=='POST['){
		$getkey=substr($strtrim,5);
		return $_POST[$getkey];
	}elseif (substr($strtrim,0,6)=='PGEST['){
		$getkey=substr($strtrim,6);
		return $_POST[$getkey]?$_POST[$getkey]:$_GET[$getkey];
	}else{
		return $str;
	}
}
function get_cache($cachename){
	$_cache_file =KF_ROOT_PATH. "caches/cache_".$cachename.".php";
	return getcache($_cache_file);
}
/**
 * @param $_file 缓存全路径
 */
function getcache($_file){
	if(file_exists($_file)) {
		@include($_file);
		return $data;
	}else{
		return array();
	}
}
function exectime(){ 
	$time = explode(" ", microtime());
	$usec = (double)$time[0]; 
	$sec = (double)$time[1]; 
	return $sec + $usec; 
}
function check_word($noword,$content){
	$word=explode('|',$noword);
	if (!empty($word) && !empty($content)){
		foreach($word as $str){
			if(!empty($str) && strstr($content,$str)){
				return true;
			}
		}
	}
	return false;
}
function getip(){
	if (getenv('HTTP_CLIENT_IP') and strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown')) {
		$onlineip=getenv('HTTP_CLIENT_IP');
	}elseif (getenv('HTTP_X_FORWARDED_FOR') and strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown')) {
		$onlineip=getenv('HTTP_X_FORWARDED_FOR');
	}elseif (getenv('REMOTE_ADDR') and strcasecmp(getenv('REMOTE_ADDR'),'unknown')) {
		$onlineip=getenv('REMOTE_ADDR');
	}elseif (isset($_SERVER['REMOTE_ADDR']) and $_SERVER['REMOTE_ADDR'] and strcasecmp($_SERVER['REMOTE_ADDR'],'unknown')) {
		$onlineip=$_SERVER['REMOTE_ADDR'];
	}
	preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/",$onlineip,$match);
	return $onlineip = $match[0] ? $match[0] : 'unknown';
}
function inserttable($tablename, $insertsqlarr, $returnid=0, $replace = false, $silent=0) {
	global $db;
	return $db->insert($insertsqlarr, $tablename, $returnid, $replace, $silent);
}
function updatetable($tablename, $setsqlarr, $wheresqlarr, $silent=0) {
	global $db;
	return $db->update($setsqlarr, $tablename, $wheresqlarr, $silent);
}
function wheresql($wherearr='')
{
	$wheresql="";
	if (is_array($wherearr))
		{
		$where_set=' WHERE ';
			foreach ($wherearr as $key => $value)
			{
			$wheresql .=$where_set. $comma.$key.'="'.$value.'"';
			$comma = ' AND ';
			$where_set=' ';
			}
		}
	return $wheresql;
}
function convert_datefm ($date,$format,$separator="-")
{
	 if ($format=="1")
	 {
	 return date("Y-m-d", $date);
	 }
	 else
	 {
		if (!preg_match("/^[0-9]{4}(\\".$separator.")[0-9]{1,2}(\\1)[0-9]{1,2}(|\s+[0-9]{1,2}(|:[0-9]{1,2}(|:[0-9]{1,2})))$/",$date))  return false;
		$date=explode($separator,$date);
		return mktime(0,0,0,$date[1],$date[2],$date[0]);
	 }
}
function sub_day($endday,$staday,$range='')
{
	$value = $endday - $staday;
	if($value < 0)
	{
		return '';
	}
	elseif($value >= 0 && $value < 59)
	{
		return ($value+1)."秒";
	}
	elseif($value >= 60 && $value < 3600)
	{
		$min = intval($value / 60);
		return $min."分钟";
	}
	elseif($value >=3600 && $value < 86400)
	{
		$h = intval($value / 3600);
		return $h."小时";
	}
	elseif($value >= 86400 && $value < 86400*30)
	{
		$d = intval($value / 86400);
		return intval($d)."天";
	}
	elseif($value >= 86400*30 && $value < 86400*30*12)
	{
		$mon  = intval($value / (86400*30));
		return $mon."月";
	}
	else{	
		$y = intval($value / (86400*30*12));
		return $y."年";
	}
}
function daterange($endday,$staday='',$format='Y-m-d',$color='',$range=3)
{
	if (empty($staday)){
		$value = SYS_TIME - $endday;
	}else{
		$value = $endday - $staday;
	}
	if($value < 0)
	{
		return '';
	}
	elseif($value >= 0 && $value < 59)
	{
		$return=($value+1)."秒前";
	}
	elseif($value >= 60 && $value < 3600)
	{
		$min = intval($value / 60);
		$return=$min."分钟前";
	}
	elseif($value >=3600 && $value < 86400)
	{
		$h = intval($value / 3600);
		$return=$h."小时前";
	}
	elseif($value >= 86400)
	{
		$d = intval($value / 86400);
		if ($d>$range)
		{
		return date($format,$staday);
		}
		else
		{
		$return=$d."天前";
		}
	}
	if ($color)
	{
	$return="<span style=\"color:{$color}\">".$return."</span>";
	}
	return $return;	 
}
function dateaway($fortime)
{
	$value = SYS_TIME - $fortime;
	if ($value < 0) {
		return '刚刚';
	} elseif($value >= 0 && $value < 59) {
		$return=($value+1)."秒前";
	} elseif($value >= 60 && $value < 3600) {
		$min = intval($value / 60);
		$return=$min."分钟前";
	} elseif($value >=3600 && $value < 86400) {	
		$h = intval($value / 3600);
		$return=$h."小时前";
	} elseif($value >= 86400) {	
		$d = intval($value / 86400);
		if ($d>3){
			if (date("Y",$fortime) != date("Y",SYS_TIME)){
				$return=date("Y-m-d",$fortime);
			}else{
				$return=date("m-d",$fortime);
			}
		}else{
			$return=$d."天前";
		}
	}
	return $return;	 
}
function __smarty_display(){
	$_arr = array_merge($_GET,$_POST);
	if (empty($_arr)){
		return md5(request_url());
	}else{
		ksort($_arr);
		return md5(implode('', $_arr));
	}
}
/**
 * 页面执行时间
 * @param $str 1返回秒，0返回毫秒；默认0
 */
function run_time($str = 0){
	global $__run_time;
	if ($__run_time){
		$__run_time->stop();
		$_run_timeval = $__run_time->spent() + 20;
		if (!empty($str)){
			$_run_timeval = round($_run_timeval / 1000, 2); //返回秒
		}
		unset($__run_time);
	}else{
		$_run_timeval = 0;
	}
	return $_run_timeval;
}
/**
 * 数据库执行次数
 * @param $str 0返回执行查询次数，1返回执行更新次数，2返回执行插入次数，3返回执行统计次数，4返回执行删除次数，5返回所有次数；默认0
 */
function db_num($str = 0){
	global $db;
	$_arrnum = 0;
	$strarr = explode(',', $str);
	$arr_0 = $arr_1 = $arr_2 = $arr_3 = $arr_4 = array();
	$_arr = $db -> ExecuteArr;
	if (!in_array('5',$strarr)){
		foreach ($_arr as $_val) {
			$_vv = trim($_val);
			$_vv = strtolower($_vv);
			$_v5 = substr($_vv, 0, 5);
			if ($_v5 == 'selec'){
				$_vv = str_replace(' ', '', $_vv);
				if (substr($_vv, 0, 11) == 'selectcount'){
					$arr_3[] = $_val; //统计
				}else{
					$arr_0[] = $_val; //查询
				}
			}elseif ($_v5 == 'updat'){ //更新
				$arr_1[] = $_val;
			}elseif ($_v5 == 'inser'){ //插入
				$arr_2[] = $_val;
			}elseif ($_v5 == 'delet'){ //删除
				$arr_4[] = $_val;
			}
		}
		if (in_array('0',$strarr)) $_arrnum = count($arr_0);
		if (in_array('1',$strarr)) $_arrnum = count($arr_1);
		if (in_array('2',$strarr)) $_arrnum = count($arr_2);
		if (in_array('3',$strarr)) $_arrnum = count($arr_3);
		if (in_array('4',$strarr)) $_arrnum = count($arr_4);
	}else{
		$_arrnum = count($_arr);
	}
	return $_arrnum;
}
/**
 * 
 * 截取字符串
 * @param $string 	字符串
 * @param $length 	截取长度
 * @param $start 	何处开始
 * @param $dot 		超出尾部添加
 * @param $charset 	默认编码
 */
function cut_str($string, $length, $start=0, $dot='', $charset = CHARSET) 
{
	if ($charset == 'utf-8'){
		if(get_strlen($string) <= $length) return $string;
		$strcut = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);
		$strcut = utf8_substr($strcut, $length, $start);
		$strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
		return $strcut.$dot;
	}else{
		$length=$length*2;
		if(strlen($string) <= $length) return $string;
		$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);
		$strcut = '';
		for($i = 0; $i < $length; $i++) {
			$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
		}
		$strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
	}
	return $strcut.$dot;
}

/**
 * PHP获取字符串中英文混合长度
 * @param $str 		字符串
 * @param $charset	编码
 * @return 返回长度，1中文=1位，2英文=1位
 */
function get_strlen($str,$charset = CHARSET){
	if($charset=='utf-8') $str = iconv('utf-8','GBK//IGNORE',$str);
	$num = strlen($str);
	$cnNum = 0;
	for($i=0;$i<$num;$i++){
		if(ord(substr($str,$i+1,1))>127){
			$cnNum++;
			$i++;
		}
	}
	$enNum = $num-($cnNum*2);
	$number = ($enNum/2)+$cnNum;
	return ceil($number);
}
/**
* PHP截取UTF-8字符串，解决半字符问题。
* @return 取出的字符串, 当$len小于等于0时, 会返回整个字符串
* @param $str 		源字符串
* @param $len 		左边的子串的长度
* @param $start 	何处开始
*/
function utf8_substr($str, $len, $start=0)
{
	$len=$len*2;
	for($i=0;$i<$len;$i++)
	{
		$temp_str=substr($str,0,1);
		if(ord($temp_str) > 127){
			$i++;
			if($i<$len){
				$new_str[]=substr($str,0,3);
				$str=substr($str,3);
			}
		}else{
			$new_str[]=substr($str,0,1);
			$str=substr($str,1);
		}
	}
	return join(array_slice($new_str,$start));
}
/**
* 字符串加密、解密函数
*
*
* @param	string	$txt		字符串
* @param	string	$operation	ENCODE为加密，DECODE为解密，可选参数，默认为ENCODE，
* @param	string	$key		密钥：数字、字母、下划线
* @param	string	$expiry		过期时间
* @return	string
*/
function sys_auth($string, $operation = 'ENCODE', $key = '', $expiry = 0) {
	global $_CFG;
	$key_length = 4;
	$key = md5($key != '' ? $key : $_CFG['auth_key']);
	$fixedkey = md5($key);
	$egiskeys = md5(substr($fixedkey, 16, 16));
	$runtokey = $key_length ? ($operation == 'ENCODE' ? substr(md5(microtime(true)), -$key_length) : substr($string, 0, $key_length)) : '';
	$keys = md5(substr($runtokey, 0, 16) . substr($fixedkey, 0, 16) . substr($runtokey, 16) . substr($fixedkey, 16));
	$string = $operation == 'ENCODE' ? sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$egiskeys), 0, 16) . $string : base64_decode(substr($string, $key_length));

	$i = 0; $result = '';
	$string_length = strlen($string);
	for ($i = 0; $i < $string_length; $i++){
		$result .= chr(ord($string{$i}) ^ ord($keys{$i % 32}));
	}
	if($operation == 'ENCODE') {
		return $runtokey . str_replace('=', '', base64_encode($result));
	} else {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$egiskeys), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	}
}
/**
 * 判断是否发送
 * @param $str
 */
function get_mail_yes($str){
	global $db;
	if (empty($str)) return false;
	$val = $db->getone("select * from ".table('youxiang')." where type = 'rule' LIMIT 1");
	$valarr = string2array($val['body']);
	if ($valarr[$str]){
		return true;
	}else{
		return false;
	}
}

/**
 * 获取邮件内容
 * @param $_title 使用的邮件模板
 * @param $_array 替换数组
 */
function get_mail($_title, $_array = array(), $_array2 = array(), $_array3 = array(), $_array4 = array(), $_array5 = array()) {
	global $db;
	if ($_title){
		$wheresql = " WHERE type = 'templates' and title = '{$_title}'";
		$val = $db -> getone("select * from ".table('youxiang').$wheresql." LIMIT 1");
		$val_body = string2array($val['body']);
		$val_body['smtp_title'] = preg_replace("/\{(.+?)\}/es","get_mail_str($1,\$_array,\$_array2,\$_array3,\$_array4,\$_array5)", $val_body['smtp_title']); 
		$val_body['smtp_body'] = preg_replace("/\{(.+?)\}/es","get_mail_str($1,\$_array,\$_array2,\$_array3,\$_array4,\$_array5)", $val_body['smtp_body']); 
		return $val_body;
	}else{
		return array('smtp_title' => '' ,'smtp_body' => '');
	}			
}
function get_mail_str($_str, $_array = array(), $_array2 = array(), $_array3 = array(), $_array4 = array(), $_array5 = array()) {
	$_val = '';
	if ($_array) $_val.=$_array[$_str];
	if ($_array2) $_val.=$_array2[$_str];
	if ($_array3) $_val.=$_array3[$_str];
	if ($_array4) $_val.=$_array4[$_str];
	if ($_array5) $_val.=$_array5[$_str];
	if ($_val){
		return $_val;
	}else{
		return $_str;
	}
}

function smtp_mail($sendto_email,$subject,$body,$From='',$FromName='')
{	
	global $db;
	$mailval = $db->getone("select * from ".table('youxiang')." WHERE type='mail' order by rand() LIMIT 1  ");
	$mailcon = string2array($mailval['body']);
	require(KF_INC_PATH.'mail/class.phpmailer.php');
	$mail = new PHPMailer();
	$From=$From?$From:$mailcon['smtp_from'];
	$FromName=$FromName?$FromName:$_CFG['site_name'];
	if ($mailcon['smtp_method']=="1"){
		if (empty($mailcon['smtp_servers']) || empty($mailcon['smtp_username']) || empty($mailcon['smtp_password']) || empty($mailcon['smtp_from'])){
			write_syslog(2,'MAIL',"邮件配置信息不完整");
			return false;
		}
		$mail->IsSMTP();
		$mail->Host = $mailcon['smtp_servers'];
		$mail->SMTPDebug= 0; 
		$mail->SMTPAuth = true;
		$mail->Username = $mailcon['smtp_username']; 
		$mail->Password = $mailcon['smtp_password']; 
		$mail->Port =$mailcon['smtp_port'];
		$mail->From =$mailcon['smtp_from']; 
		$mail->FromName =$FromName;
	}elseif($mailcon['smtp_method']=="2"){
		$mail->IsSendmail();
	}elseif($mailcon['smtp_method']=="3"){
		$mail->IsMail();
	}
	$mail->CharSet = CHARSET;
	$mail->Encoding = "base64";
	$mail->AddReplyTo($From,$FromName);
	$mail->AddAddress($sendto_email,"");
	$mail->IsHTML(true);
	$mail->Subject = $subject;
	$mail->Body =$body;
	$mail->AltBody ="text/html";
	if($mail->Send()){
		return true;
	}else{
		write_syslog(2,'MAIL',$mail->ErrorInfo);
		return false;
	}
}
function dfopen($url,$limit = 0, $post = '', $cookie = '', $bysocket = FALSE	, $ip = '', $timeout = 15, $block = TRUE, $encodetype  = 'URLENCOD')
{
		$return = '';
		$matches = parse_url($url);
		$host = $matches['host'];
		$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
		$port = !empty($matches['port']) ? $matches['port'] : 80;

		if($post) {
			$out = "POST $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			//$out .= "Referer: $boardurl\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$boundary = $encodetype == 'URLENCODE' ? '' : ';'.substr($post, 0, trim(strpos($post, "\n")));
			$out .= $encodetype == 'URLENCODE' ? "Content-Type: application/x-www-form-urlencoded\r\n" : "Content-Type: multipart/form-data$boundary\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host:$port\r\n";
			$out .= 'Content-Length: '.strlen($post)."\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cache-Control: no-cache\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
			$out .= $post;
		} else {
			$out = "GET $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			//$out .= "Referer: $boardurl\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host:$port\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
		}

		if(function_exists('fsockopen')) {
			$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
		} elseif (function_exists('pfsockopen')) {
			$fp = @pfsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
		} else {
			$fp = false;
		}

		if(!$fp) {
			return '';
		} else {
			stream_set_blocking($fp, $block);
			stream_set_timeout($fp, $timeout);
			@fwrite($fp, $out);
			$status = stream_get_meta_data($fp);
			if(!$status['timed_out']) {
				while (!feof($fp)) {
					if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) {
						break;
					}
				}

				$stop = false;
				while(!feof($fp) && !$stop) {
					$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
					$return .= $data;
					if($limit) {
						$limit -= strlen($data);
						$stop = $limit <= 0;
					}
				}
			}
			@fclose($fp);
			return $return;
		}
}
function send_sms($mobile,$content)
{
	global $db;
	$sms=get_cache('sms_config');
	if ($sms['open']!="1" || empty($sms['sms_name']) || empty($sms['sms_key']) || empty($mobile) || empty($content))
	{
	return false;
	}
	else
	{
	return dfopen("http://www.74cms.com/SMSsend.php?sms_name={$sms['sms_name']}&sms_key={$sms['sms_key']}&mobile={$mobile}&content={$content}");
	}	
}
function execution_crons()
{
	global $db;
	$crons=$db->getone("select * from ".table('crons')." WHERE (nextrun<".time()." OR nextrun=0) AND available=1 LIMIT 1  ");
	if (!empty($crons))
	{
		require(KF_ROOT_PATH."include/crons/".$crons['filename']);
	}
}
function get_tpl($file)
{
	global $backhttp;
	if (empty($file)) return '';
	$__temp_addr = $file;
	$__temp_file = KF_ROOT_PATH.DIR_PATH;
	switch ($_GET['vs']){
		case '2':
			$__temp_addr.='.html';
			break;
		case '3':
			$__temp_addr.='.html5';
			break;
		case '4':
			$__temp_addr.='.pad';
			break;
		case '5':
			$__temp_addr.='.web';
			break;
	}
	if (!file_exists($__temp_file.$__temp_addr.'.tpl')){
		if (file_exists($__temp_file.$file.'.html.tpl')){
			$__temp_addr = $file.'.html';
		}else{
			$__temp_addr = $file;
		}
	}
	if (!file_exists($__temp_file.$__temp_addr.'.tpl')) {
		if ($backhttp[1]){
			$links[-1]['title'] = 'Go to Back';
			$links[-1]['href'] = -1;
		}
		$links[0]['title'] = 'Back to Home';
		$links[0]['href'] = kf_url('index');
		showmsg("System Alert", "Template does not exist！<br/>location: ./".DIR_PATH.$__temp_addr.'.tpl', $links);
	}
	$backhttp['gettpl'] = true;
	return $__temp_addr.'.tpl';
}
function get_tpl_addons($module, $function)
{
    global $backhttp;
    if (empty($module)) return '';
    $__temp_addr = $function;
    $__temp_file = KF_ROOT_PATH.'kuaifan/addons/'.$module.'/templates/';
    switch ($_GET['vs']){
        case '2':
            $__temp_addr.='.html';
            break;
        case '3':
            $__temp_addr.='.html5';
            break;
        case '4':
            $__temp_addr.='.pad';
            break;
        case '5':
            $__temp_addr.='.web';
            break;
    }
    if (!file_exists($__temp_file.$__temp_addr.'.tpl')){
        if (file_exists($__temp_file.$function.'.html.tpl')){
            $__temp_addr = $function.'.html';
        }else{
            $__temp_addr = $function;
        }
    }
    if (!file_exists($__temp_file.$__temp_addr.'.tpl')) {
        if ($backhttp[1]){
            $links[-1]['title'] = 'Go to Back';
            $links[-1]['href'] = -1;
        }
        $links[0]['title'] = 'Back to Home';
        $links[0]['href'] = kf_url('index');
        showmsg("System Alert", "Template does not exist！<br/>location: ./kuaifan/addons/{$module}/templates/{$function}.tpl", $links);
    }
    $backhttp['gettpl'] = true;
    return $__temp_file.$__temp_addr.'.tpl';
}
function kf_url($alias, $get=NULL, $isrewrite=true){
	switch ($alias){
		case 'index': 
			//首页
			$_urlarr = array(
				'm'=>'index',
				'sid'=>$_GET['sid'],
				'vs'=>$_GET['vs'],
			);
			$alias = 'KF_'.$alias;
			break;
		case 'neirongreply':
			//评论列表
			$_urlarr = array(
				'm'=>'neirong',
				'c'=>'pinglun',
				'catid'=>$_GET['catid'],
				'id'=>$_GET['id'],
				'sid'=>$_GET['sid'],
				'vs'=>$_GET['vs'],
			);
			$alias = 'KF_'.$alias;
			break;
	}
	if (!empty($get)) $_urlarr = array_merge($_urlarr, $get);
	return url_rewrite($alias, $_urlarr, $isrewrite);
}
function url_rewrite($alias=NULL,$get=NULL,$rewrite=true,$tget=array())
{
	global $_CFG,$_PAGE;
	if (!empty($tget)) $get = array_merge($get, $tget); 
	$url ='';
	//例外
	if ($alias == 'KF_neironglist'){
		if (!empty($_GET['type'])) $rewrite = false;
	}
	if ($alias == 'KF_neirongreply'){
		if (!empty($_GET['support'])) $rewrite = false;
	}
	//
	if ($_PAGE[$alias]['url-'.$_GET['vs'].'']=='0' || $_PAGE[$alias]['url']=='0' || $rewrite==false){//原始链接
		if (!empty($get)){
			//ksort($get);
			foreach($get as $k=>$v){
				$url .="{$k}={$v}&amp;";
			}
		}
		$url=!empty($url)?"?".rtrim($url,'&amp;'):'';
		return $_CFG['site_dir'].$_PAGE[$alias]['file'].$url;
	} else {
		$url =$_CFG['site_dir'].$_PAGE[$alias]['rewrite'];
		//ksort($get);
		preg_match_all('/\(\$(.+?)\)/', $url, $urlmatch);
		foreach($urlmatch[1] as $urlval){
			$urlarr = explode('|', $urlval);
			$getval = $get[$urlarr[0]];
			if (empty($getval) && !empty($urlarr[1])) $getval = $urlarr[1];
			$url = str_replace('($'.$urlval.')', $getval, $url);
		}
		return $url;
	}
}
function get_member_url($type,$dirname=false)
{
	global $_CFG;
	$type=intval($type);
	if ($type===0) 
	{
	return "";
	}
	elseif ($type===1)
	{
	$return=$_CFG['site_dir']."user/company/company_index.php";
	}
	elseif ($type===2) 
	{
	$return=$_CFG['site_dir']."user/personal/personal_index.php";
	}
	if ($dirname)
	{
	return dirname($return).'/';
	}
	else
	{
	return $return;
	}
}
function subsiteinfo(&$_CFG)
{
	if ($_CFG['subsite']=="0")
	{
	return false;
	}
	else
	{
		$_SUBSITE=get_cache('subsite');
			foreach($_SUBSITE as $key=> $sub)
			{
			$_CFG['district_array'][]=array('subsite'=>$sub['districtname'],'url'=>"http://".$key);
			}
		$host=$_SERVER['HTTP_HOST'];
		if (array_key_exists($host,$_SUBSITE))
		{
			$subsite=$_SUBSITE[$host];
			$_CFG['site_domain']="http://".$host;
			$_CFG['subsite_districtname']=$subsite['districtname'];
			$_CFG['site_name']=$subsite['sitename'];
			if (empty($_GET['district_cn']))
			{
			$_GET['district_cn']=$_CFG['subsite_districtname'];
			}
			$_CFG['subsite_id']=$subsite['id'];
			$subsite['logo']?$_CFG['web_logo']=$subsite['logo']:'';
			$subsite['tpl']?$_CFG['template_dir']=$subsite['tpl'].'/':'';
			$subsite['filter_notice']?$_CFG['subsite_filter_notice']=$subsite['filter_notice']:'';
			$subsite['filter_jobs']?$_CFG['subsite_filter_jobs']=$subsite['filter_jobs']:'';
			$subsite['filter_resume']?$_CFG['subsite_filter_resume']=$subsite['filter_resume']:'';
			$subsite['filter_ad']?$_CFG['subsite_filter_ad']=$subsite['filter_ad']:'';
			$subsite['filter_links']?$_CFG['subsite_filter_links']=$subsite['filter_links']:'';
			$subsite['filter_news']?$_CFG['subsite_filter_news']=$subsite['filter_news']:'';
			$subsite['filter_explain']?$_CFG['subsite_filter_explain']=$subsite['filter_explain']:'';
			$subsite['filter_jobfair']?$_CFG['subsite_filter_jobfair']=$subsite['filter_jobfair']:'';
			$subsite['filter_simple']?$_CFG['subsite_filter_simple']=$subsite['filter_simple']:'';
		}
	}
}
function fulltextpad($str)
{
	if (empty($str))
	{
	return '';
	}
	$leng=strlen($str);
	if ($leng>=8)
		{
		return $str;
	}
	else
	{
		$l=4-($leng/2);
		return str_pad($str,$leng+$l,'0');
	}
}
function asyn_userkey($uid)
{
	global $db;
	$sql = "select * from ".table('members')." where uid = '".intval($uid)."' LIMIT 1";
	$user=$db->getone($sql);
	return md5($user['username'].$user['pwd_hash'].$user['password']);
}
function write_syslog($type,$type_name,$str)
{
 	global $db,$online_ip;
	$l_page = addslashes(request_url());
	$str = addslashes($str);
 	$sql = "INSERT INTO ".table('xitongrizhi')." (l_type, l_type_name, l_time,l_ip,l_page,l_str) VALUES ('{$type}', '{$type_name}', '".time()."','{$online_ip}','{$l_page}','{$str}')"; 
	return $db->query($sql);
}
function write_memberslog($uid,$utype,$type,$username,$str)
{
 	global $db,$online_ip;
 	$sql = "INSERT INTO ".table('members_log')." (log_uid,log_username,log_utype,log_type,log_addtime,log_ip,log_value) VALUES ( '{$uid}','{$username}','{$utype}','{$type}', '".time()."','{$online_ip}','{$str}')";
	return $db->query($sql);
}
function request_url()
{     
  	if (isset($_SERVER['REQUEST_URI']))     
    {        
   	 $url = $_SERVER['REQUEST_URI'];    
    }
	else
	{    
		  if (isset($_SERVER['argv']))        
			{           
			$url = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];      
			}         
		  else        
			{          
			$url = $_SERVER['PHP_SELF'] .'?'.$_SERVER['QUERY_STRING'];
			}  
    }    
    return urlencode($url); 
}
function label_replace($templates)
{
	global $_CFG;
	$templates=str_replace('{sitename}',$_CFG['site_name'],$templates);
	$templates=str_replace('{sitedomain}',$_CFG['site_domain'].$_CFG['site_dir'],$templates);
	$templates=str_replace('{username}',$_GET['sendusername'],$templates);
	$templates=str_replace('{password}',$_GET['sendpassword'],$templates);
	$templates=str_replace('{newpassword}',$_GET['newpassword'],$templates);
	$templates=str_replace('{personalfullname}',$_GET['personal_fullname'],$templates);
	$templates=str_replace('{jobsname}',$_GET['jobs_name'],$templates);
	$templates=str_replace('{companyname}',$_GET['companyname'],$templates);
	$templates=str_replace('{paymenttpye}',$_GET['paymenttpye'],$templates);
	$templates=str_replace('{amount}',$_GET['amount'],$templates);
	$templates=str_replace('{oid}',$_GET['oid'],$templates);
	return $templates;
}
function make_dir($path)
{ 
	if(!file_exists($path))
	{
	make_dir(dirname($path));
	@mkdir($path,0777);
	@chmod($path,0777);
	}
}
/*
 ***********************************
 ***********************************
 ***********************************
*/
function format_form($set = null){
	extract($set);  
	$brr = "\n";
	
	$arr = explode('|',$set);
	$val = format_call_user_func($arr[1]);
	$val = str_replace("值:", "value:", $val);
	$val = str_replace("宽:", "size:", $val);
	$val = str_replace("名称:", "name:", $val);
	$val = str_replace("最多字符:", "maxlength:", $val);
	$val = str_replace("方法:", "method:", $val);
	$val = str_replace("地址:", "action:", $val);
	$val = str_replace("目标:", "target:", $val);
	$val = str_replace("编码类型:", "enctype:", $val);
	$val = str_replace("行数:", "rows:", $val);
	$val = str_replace("\:", "\u003a", $val);
	$val = str_replace(":", " => ", $val);
	$val = str_replace("\u003a", ":", $val);
	$valarr = string2array("array({$val})");$val = "";
	if (!$valarr['value'] && isset($data_value)) $valarr['value']= $htmlspecialchars?$data_value:htmlspecialchars($data_value);
	foreach($valarr as $key=>$value){
		$val.= $key."=\"{$value}\" ";
	}
	
	$_timehide = true;
	if ($vs){
		if (!in_array($_GET['vs'],explode(',',$vs))) $_timehide = false;
	}
	if ($notvs){
		if (in_array($_GET['vs'],explode(',',$notvs))) $_timehide = false;
	}
	if ($_timehide){
		switch ($arr[0])
		{
		case "输入框":
			$val = '<input class="ipt-text" type="text" '.$val.'/>'.$brr ;
			break;
		case "密码框":
			$val = '<input class="ipt-pass" type="password" '.$val.'/>'.$brr ;
			break;
		case "文本框":
			$val = str_replace("size", "cols", $val);
			$val = preg_replace("/value=\"(.+?)\"/is","", $val); 
			$val = str_replace("value=\"\"","", $val); 
			$val = '<textarea '.$val.'>'.$valarr['value'].'</textarea>'.$brr ;
			break;
		case "单选框":
			$val = '<input type="radio" '.$val.'/>'.$brr ;
			break;
		case "复选框":
			$val = '<input type="checkbox" '.$val.'/>'.$brr ;
			break;
		case "按钮":
			if (!$valarr['value'] && $valarr['name']) $val.=' value="'.$valarr['name'].'"';
			$val = '<input class="ipt-btn" type="submit" '.$val.'/>'.$brr ;
			break;
		case "文件":
			$val = '<input type="file" '.$val.'/>'.$brr ;
			break;
		case "隐藏":
			$val = '<input type="hidden" '.$val.'/>'.$brr ;
			break;
		case "自定义":
			$val = '<input '.$val.'/>'.$brr ;
			break;
		case "列表框":
			if (is_array($list)){
				$arra = $list;
			}else{
				$list = str_replace("\:", "\u003a", $list);
				$list = str_replace(":", " => ", $list);
				$list = str_replace("\u003a", ":", $list);
				$arra = string2array("array({$list})");
			}
			if ($boxtype){
				$val = "";
				if ($_GET['vs']!="1" || $__vs=="2"){
					$_time = $val ;
					$_time = str_replace("name=\"{$valarr['name']}\"", "", $_time);
					if (isset($valarr['value']) && !$default){
						$default = $valarr['value'];
					}
					$defaultarr = explode("///", $default);
					$_n = 0;
					foreach($arra as $key=>$value){
						$val.= '<select '.$_time.' name="'.$valarr['name'].'['.$_n.']">'.$brr ;
						if (in_array($value, $defaultarr) && $defaultarr){
							$val.= '<option value="'.$value.'" selected="selected">'.$key.'[选中]</option>'.$brr ;
							$val.= '<option value="">'.$key.'[不选]</option>'.$brr ;
						}else{
							$val.= '<option value="'.$value.'">'.$key.'[选中]</option>'.$brr ;
							$val.= '<option value="" selected="selected">'.$key.'[不选]</option>'.$brr ;
						}
						$val.= '</select>'.$brr ;
						$_n++;
					}
				}else{
					$val = "<u>(此版本不支持多选项目)</u>";
				}
			}else{
				if (!$valarr['value'] && isset($default)) {
					$val = preg_replace("/value=\"(.+?)\"/is","", $val); 
					$val = str_replace("value=\"\"","", $val); 
					$val.=' value="'.$default.'"';
				}elseif (isset($valarr['value'])){
					$default = $valarr['value'];
				}
				$val = '<select '.$val.'>'.$brr ;
				foreach($arra as $key=>$value){
					if ($default==$value && $default!="" && ($_GET['vs']!="1" || $__vs=="2")){
						$val.= '<option value="'.$value.'" selected="selected">'.$key.'</option>'.$brr ;
					}else{
						$val.= '<option value="'.$value.'">'.$key.'</option>'.$brr ;
					}
				}
				$val.= '</select>'.$brr ;
			}
			break;
		case "头":
			if (!$valarr['method']) $val.=' method="post"';
			if (!$valarr['action']) $val.=' action="'.get_link().'"';
			$val = '<form '.$val.'>'.$brr ;
			break;
		case "尾":
			$val = set_csrf_token().$brr ;
			$val.= '</form>'.$brr ;
			break;
		}
	}else{
		$val="";
	}
	
	return $val;
}


function format_kuaifan($_arr = null){
	extract($_arr);
	if ($vs){
		if (!in_array($_GET['vs'],explode(',',$vs))) return '';
	}
	if ($notvs){
		if (in_array($_GET['vs'],explode(',',$notvs))) return '';
	}
	//切换版本
	if ($beta){
		return format_beta($beta, $beta_cn, $beta_cut, $_beta_dot);
	//运行函数
	}elseif ($function){
		return format_call_user_func($function);
	//去除链接变量
	}elseif ($getlink){
		return get_link($getlink);
	//去除链接变量（保留模式）
	}elseif ($getlinks){ 
		return get_link($getlinks,'',1);
	//统计动态
	}elseif ($tongji){ 
		return tianjia_tongji($tongji,$title,$get);
	//获取用户头像
	}elseif (isset($touxiang)){ 
		return get_touxiang($touxiang,$size);
	//头部显示
	}elseif (isset($header)){ 
		return get_header($header);
	//友链调用
	}elseif (isset($lianjie)){ 
		kf_class::run_sys_func('lianjie');
		return ubb_lianjie($lianjie);
	//广告调用
	}elseif (isset($guanggao)){ 
		kf_class::run_sys_func('ubb');
		kf_class::run_sys_func('guanggao');
		return ubb_guanggao($guanggao);
	//页面执行时间
	}elseif (isset($runtime)){
		return run_time(intval($runtime));
	//数据库执行次数
	}elseif (isset($dbnum)){
		return db_num($dbnum);
	//指定页面地址
	}elseif (isset($rewrite)){
		$isrewrite = $isrewrite?$isrewrite:true;
		return kf_url($rewrite, $get, $isrewrite);
	//显示文本
	}else{
		return $set;
	}
}
function format_beta($_beta, $_beta_cn = '', $_beta_cut = '', $_beta_dot = '0'){
	$_time = str_replace("，", ",", $_beta); $_time_arr = explode(',',$_time);
	$_time_cn = str_replace("，", ",", $_beta_cn); $_time_cn_arr = explode(',',$_time_cn);
	$_time_url = get_link('vs');
	$_time_val = ''; $_n = 0;
	foreach ($_time_arr as $_kk => $_val){
		if ($_n > 0)$_time_val.= $_beta_cut;
		switch ($_val){
			case "1":
				$_time_nam = $_time_cn_arr[$_kk]?$_time_cn_arr[$_kk]:'简版';
				if ($_GET['vs']=='1' && !$_beta_dot){
					$_time_val.= $_time_nam;
				}else{
					$_time_val.= '<a href="'.$_time_url.'&amp;vs=1">'.$_time_nam.'</a>';
				}
				break;
			case "2":
				$_time_nam = $_time_cn_arr[$_kk]?$_time_cn_arr[$_kk]:'彩版';
				if ($_GET['vs']=='2' && !$_beta_dot){
					$_time_val.= $_time_nam;
				}else{
					$_time_val.= '<a href="'.$_time_url.'&amp;vs=2">'.$_time_nam.'</a>';
				}
				break;
			case "3":
				$_time_nam = $_time_cn_arr[$_kk]?$_time_cn_arr[$_kk]:'触屏版';
				if ($_GET['vs']=='3' && !$_beta_dot){
					$_time_val.= $_time_nam;
				}else{
					$_time_val.= '<a href="'.$_time_url.'&amp;vs=3">'.$_time_nam.'</a>';
				}
				break;
			case "4":
				$_time_nam = $_time_cn_arr[$_kk]?$_time_cn_arr[$_kk]:'平板';
				if ($_GET['vs']=='4' && !$_beta_dot){
					$_time_val.= $_time_nam;
				}else{
					$_time_val.= '<a href="'.$_time_url.'&amp;vs=4">'.$_time_nam.'</a>';
				}
				break;
			case "5":
				$_time_nam = $_time_cn_arr[$_kk]?$_time_cn_arr[$_kk]:'电脑版';
				if ($_GET['vs']=='5' && !$_beta_dot){
					$_time_val.= $_time_nam;
				}else{
					$_time_val.= '<a href="'.$_time_url.'&amp;vs=5">'.$_time_nam.'</a>';
				}
				break;
		}
		$_n++;
	}
	return $_time_val;
}
/*函数里运行函数*/
function format_call_user_func($str){
	$regex = '/\{#(.+?)\((.+?)\)#\}/is';
	$matches = array();
	$returns = $str;
	if(preg_match_all($regex, $returns, $matches)){
		for($i=0;$i<count($matches[0]);$i++){
			$matches[2][$i] = str_replace("'", "", $matches[2][$i]);
			$matches[2][$i] = str_replace("\"", "", $matches[2][$i]);
			$returns = str_replace($matches[0][$i], call_user_func($matches[1][$i],$matches[2][$i]), $returns);
		}
	}
	$regex = '/\{#(.+?)\(\)#\}/is';
	if(preg_match_all($regex, $returns, $matches)){
		for($i=0;$i<count($matches[0]);$i++){
			$returns = str_replace($matches[0][$i], call_user_func($matches[1][$i]), $returns);
		}
	}
	$regex = '/\{#(.+?)#\}/is';
	if(preg_match_all($regex, $returns, $matches)){
		for($i=0;$i<count($matches[0]);$i++){
			$returns = str_replace($matches[0][$i], call_user_func($matches[1][$i]), $returns);
		}
	}
	return $returns;
}
function format_ubb($param, $content, &$smarty) {
    kf_class::run_sys_func('ubb');
    $content = ubb($content);
    return $content;
}
function format_wml($param, $content, &$smarty) {
    kf_class::run_sys_func('ubb');
    $content = wml($content);
    return $content;
}
/**
 * 不加入缓存
 * @param $param
 * @param $content
 * @param $smarty
 */
function format_block_nocache($param, $content, &$smarty) {
	return $content;
}
/**
 * 去除链接变量
 * @param $str   	变量,用半角逗号隔开
 * @param $amp   	“&”的显示方式
 * @param $baoliu   采用保留方式
 * @param $array   	链接自定变量
 * @param $allurl   留空默认保留全路径
 */
function get_link($str = '', $amp = '', $baoliu = '', $array = array(), $allurl = '') {
	if (!$amp) $amp = '&amp;';
	$str = str_replace("|", ",", $str);
    $arr = explode(',',$str);
	$get = !empty($array)?$array:$_GET;
	if ($baoliu){
		$get = array();
		foreach($arr as $key=>$value){
			$get[$value] = $_GET[$value];
		}
	}else{
		foreach($arr as $key=>$value){
			unset($get[$value]);
		}
	}
	$url ='';
	if (!empty($get)){
		//ksort($get);
		foreach($get as $k=>$v){
			$url .="{$k}={$v}{$amp}";
		}
	}
	$url=!empty($url)?"?".substr($url,0,-(strlen($amp))):'?index='.generate_password(5);
	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	if ($allurl){
		return $_SERVER['PHP_SELF'].$url;
	}else{
		return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$_SERVER['PHP_SELF'].$url;
	}
}
function fenmiao(){
	if ($_GET['vs']=="1"){
		return substr(SYS_TIME, -5);
	}else{
		return "";
	}
}

/**
* 将字符串转换为数组
*
* @param	string	$data	字符串
* @return	array	返回数组格式，如果，data为空，则返回空数组
*/
function string2array($data) {
    if(is_array($data)) return $data;
    if($data == '') return array();
    @eval("\$array = $data;");
    $array = isset($array)?$array:array();
    return is_array($array)?$array:array();
}
/**
* 将数组转换为字符串
*
* @param	array	$data		数组
* @param	bool	$isformdata	如果为0，则不使用new_stripslashes处理，可选参数，默认为1
* @return	string	返回字符串，如果，data为空，则返回空
*/
function array2string($data, $isformdata = 1) {
	if($data == '') return '';
	if($isformdata) $data = new_stripslashes($data);
	return addslashes(var_export($data, TRUE));
}
/**
 * 返回经addslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
function new_addslashes($string){
	if(!is_array($string)) return addslashes($string);
	foreach($string as $key => $val) $string[$key] = new_addslashes($val);
	return $string;
}

/**
 * 返回经stripslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
function new_stripslashes($string) {
	if(!is_array($string)) return stripslashes($string);
	foreach($string as $key => $val) $string[$key] = new_stripslashes($val);
	return $string;
}

/**
 * 返回经htmlspecialchars处理过的字符串或数组
 * @param $obj 需要处理的字符串或数组
 * @return mixed
 */
function new_html_special_chars($string) {
	if(!is_array($string)) return htmlspecialchars($string);
	foreach($string as $key => $val) $string[$key] = new_html_special_chars($val);
	return $string;
}
/**
 * 返回经strip_tags处理过的字符串或数组
 * @param $obj 需要处理的字符串或数组
 * @return mixed
 */
function new_strip_tags($string) {
	if(!is_array($string)) return mystrip_tags($string);
	foreach($string as $key => $val) $string[$key] = mystrip_tags($val);
	return $string;
}

/**
 * @param $obj
 * @param string $key
 * @param bool $null_is_arr
 * @param string $default
 * @return array|bool|string
 */
function value($obj, $key='', $null_is_arr = false, $default = ''){
    if (!empty($key)){
        $arr = explode(".", str_replace("|", ".", $key));
        foreach ($arr as $val){
            if (isset($obj[$val])){
                $obj = $obj[$val];
            }else{
                $obj = "";break;
            }
        }
    }
    if ($default && empty($obj)) $obj = $default;
    if ($null_is_arr===true && empty($obj)) $obj = array();
    if ($null_is_arr==='date' && !empty($obj)) {
        $default = $default?$default:'Y-m-d H:i:s';
        $obj = date($default, $obj);
    }
    return $obj;
}
/**
 * 安全过滤函数
 *
 * @param $string
 * @return string
 */
function safe_replace($string) {
	$string = str_replace('%20','',$string);
	$string = str_replace('%27','',$string);
	$string = str_replace('%2527','',$string);
	$string = str_replace('*','',$string);
	$string = str_replace('"','&quot;',$string);
	$string = str_replace("'",'',$string);
	$string = str_replace('"','',$string);
	$string = str_replace(';','',$string);
	$string = str_replace('<','&lt;',$string);
	$string = str_replace('>','&gt;',$string);
	$string = str_replace("{",'',$string);
	$string = str_replace('}','',$string);
	$string = str_replace('\\','',$string);
	return $string;
}
/**
 * 获取当前页面完整URL地址
 */
function get_url() {
	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	$php_self = $_SERVER['PHP_SELF'] ? safe_replace($_SERVER['PHP_SELF']) : safe_replace($_SERVER['SCRIPT_NAME']);
	$path_info = isset($_SERVER['PATH_INFO']) ? safe_replace($_SERVER['PATH_INFO']) : '';
	$relate_url = isset($_SERVER['REQUEST_URI']) ? safe_replace($_SERVER['REQUEST_URI']) : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.safe_replace($_SERVER['QUERY_STRING']) : $path_info);
	return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
}
/**
 * 随机字符串
 * @param $length 随机字符长度;
 * @param $type   1数字、2大小写字母、21小写字母、22大写字母、默认全部;
 */
function generate_password( $length = 8 ,$type = '') {  
	// 密码字符集，可任意添加你需要的字符  
	switch ($type){
		case '1':
			$chars = '0123456789';
			break;
		case '2':
			$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			break;
		case '21':
			$chars = 'abcdefghijklmnopqrstuvwxyz';
			break;
		case '22':
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			break;
		default:
			$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-';
			break;
	}
	$passwordstr = '';  
	for ( $i = 0; $i < $length; $i++ ){
		$passwordstr .= $chars[ mt_rand(0, strlen($chars) - 1) ];  
	}  
	return $passwordstr;  
}
/**
 * 本站md5加密
 * @param $str 要加密的字符串
 */
function md5s($str){
	return md5(substr(md5($str),5,20));
}
/**
 * 
 * 处理返回地址backhttp
 * @param $str 1返回上一级地址；2返回上两级地址；
 * @return 返回链接地址
 */
function run_backhttp($str = 1){
	global $backhttp;
	$_str = is_numeric($str)?$backhttp[intval($str)]:$str;
	$_str = str_replace('&', '&amp;', $_str);
	$_str.= $_GET['sid']?"&amp;sid={$_GET['sid']}":"";
	$_str.= $_GET['vs']?"&amp;vs={$_GET['vs']}":"";
	return $_str;
}
/**
 * 设置返回地址backhttp
 */
function set_backhttp(){
  $_backhttp = $_COOKIE["backhttp"];
  $_backhttp['url'] = get_link('vs,sid', '&');
  if ($_backhttp['url'] != $_backhttp[0]){
  	setcookie("backhttp[2]", $_backhttp[1]); 
  	setcookie("backhttp[1]", $_backhttp[0]); 
  	setcookie("backhttp[0]", $_backhttp['url']); 
  	$_COOKIE["backhttp"][2] = $_backhttp[1];
  	$_COOKIE["backhttp"][1] = $_backhttp[0];
  	$_COOKIE["backhttp"][0] = $_backhttp['url'];
  }$backhttp = $_COOKIE["backhttp"];
  return array('back'=>$backhttp, '_back'=>$_backhttp);
}
/**
 * 回滚返回地址backhttp
 */
function back_backhttp(){
	global $_backhttp;
	setcookie("backhttp[3]", $_backhttp[3]);
	setcookie("backhttp[2]", $_backhttp[2]);
	setcookie("backhttp[1]", $_backhttp[1]);
	setcookie("backhttp[0]", $_backhttp[0]);
}
/**
 * 
 * 页面提示
 * @param $title 提示标题
 * @param $body 提示内容
 * @param $links 连接组
 * @param $gotolinks 自动跳转链接
 * @param $gototime 自动跳转时间 默认3秒
 */
function showmsg($title = '友情提示！', $body = '', $links = array(), $gotolinks = '', $gototime = '3'){
	global $_CFG,$_GET,$backhttp,$smarty;
	back_backhttp();
	//关闭缓存
	$smarty->caching = false; 
	//if ($_GET['m'] == 'admin') $backhttp = array();
	if ($backhttp[0] == get_link('vs,sid', '&') && $_POST['dosubmit']) $backhttp[1] = $backhttp[0];
	$_datalink = '';
	if (!empty($links) && is_array($links)){
		foreach($links as $_link) {
			if ($_link['href'] == '-1'){
				if ($backhttp[1]){
					$_datalink.= '<a href="'.run_backhttp($backhttp[1]).'">'.$_link['title'].'</a>';
				}else{
					if ($_GET['vs'] == 1) {
						$_datalink.= '<anchor><prev/>'.$_link['title'].'</anchor>';
					}else{
						$_datalink.= '<a href="javascript:onclick=history.go(-1)">'.$_link['title'].'</a>';
					}
				}
			}else{
				$_datalink.= '<a href="'.$_link['href'].'">'.$_link['title'].'</a>';
			}
			if ($_link['cut']){
				$_datalink.= $_link['cut'];
			}else{
				$_datalink.= '<br/>'.chr(13).chr(10);
			}
		}
	}else{
		if ($_POST['go_url']){
			$_POST['go_url'] = str_replace('&amp;', '&', urldecode($_POST['go_url']));
			$_POST['go_url'] = str_replace('&', '&amp;', $_POST['go_url']);
			$_datalink.= '<a href="'.$_POST['go_url'].'">返回来源地址</a>';
		}elseif ($backhttp[1]){
			$_datalink.= '<a href="'.run_backhttp($backhttp[1]).'">返回来源地址</a>';
		}else{
			if ($_GET['vs'] == 1) {
				$_datalink.= '<anchor><prev/>返回来源地址</anchor>';
			}else{
				$_datalink.= '<a href="javascript:onclick=history.go(-1)">返回来源地址</a>';
			}
		}
		$_datalink.= '<br/>'.chr(13).chr(10);
	}
	if ($gotolinks && $_GET['vs'] == 1) {
		$gotolinks = str_replace('&amp;', '&', $gotolinks);
		$gotolinks = str_replace('&', '&amp;', $gotolinks);
	}
	$smarty->assign('showmsg',1);
	$smarty->assign('title',$title); //标题
	$smarty->assign('body',$body); //说明
	$smarty->assign('links',$links); //链接组
	$smarty->assign('gotourl',$gotolinks); //自动跳转地址
	$smarty->assign('gototime',$gototime); //自动跳转时间
	$smarty->assign('datalink', $_datalink);
	if ($backhttp['gettpl']) {
		$smarty->display(get_tpl('warning_nohead'), __smarty_display());
	}else{
		$smarty->display(get_tpl('warning'), __smarty_display());
	}
	unset($smarty);
	exit();
}
/**
  检测字符串是否由纯英文，纯中文，中英文混合组成  
 *@param $str  
 *@return 1:纯英文;2:纯中文;3:中英文混合  
*/   
function check_str($str=''){   
    if(trim($str)==''){   
        return '';   
    }   
    $m=mb_strlen($str,'utf-8');   
    $s=strlen($str);   
    if($s==$m){   
        return 1;   
    }   
    if($s%$m==0&&$s%3==0){   
        return 2;   
    }   
    return 3;   
}
/**
 *@param $t 数字或英文或下划线
 *@param $o 第一个字符是英文
 *@return 1:纯英文
 */
function is_english($s,$t='',$o=''){
	$allen = preg_match("/^[a-z]*$/i", $s);   //判断是否是英文
	if ($t) $allen = preg_match("/^[\-\_a-zA-Z0-9]+$/i", $s);   //只能为数字或英文或下划线
	if($allen){  
		if ($o){
			if (is_english(substr($s,0,1))=='1'){
				return '3';
			}else{
				return '4';
			}
		}else{
			return '1';
		}		
	}else{  
		return '2';  
	} 
}

function sql_execute($sql) {
	global $db;
	$sqls = sql_split($sql);
	if(is_array($sqls)) {
		foreach($sqls as $sql) {
			if(trim($sql) != '') {
				$db->query($sql);
			}
		}
	} else {
		$db->query($sqls);
	}
	return true;
}
function sql_split($sql) {
	global $db, $pre, $dbcharset;
	if($db->dbversion() > '4.1' && $dbcharset) {
		$sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=".$dbcharset,$sql);
	}
	if($pre != "kf_") $sql = str_replace("kf_", $pre, $sql);
	$sql = str_replace("\r", "\n", $sql);
	$ret = array();
	$num = 0;
	$queriesarray = explode(";\n", trim($sql));
	unset($sql);
	foreach($queriesarray as $query) {
		$ret[$num] = '';
		$queries = explode("\n", trim($query));
		$queries = array_filter($queries);
		foreach($queries as $query) {
			$str1 = substr($query, 0, 1);
			if($str1 != '#' && $str1 != '-') $ret[$num] .= $query;
		}
		$num++;
	}
	return($ret);
}
/**
 * 取得文件扩展
 *
 * @param $filename 文件名
 * @return 扩展名
 */
function fileext($filename) {
	return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
}
/**
 * 添加统计
 * @param $_tongji 中间词，留空为“正在浏览”
 * @param $_title  自定义标题，-1为留空
 */
function tianjia_tongji($_tongji='', $_title = '', $_get = array()) {
	global $db,$_CFG,$_SEO,$online_ip;
	if (empty($_CFG['tjopen'])) return;
	if(isset($_SESSION)){
		if (empty($_SESSION['_tjsession'])) $_SESSION['_tjsession'] = generate_password(6,1);
	}
	$_tjsession = $_SESSION['_tjsession']?$_SESSION['_tjsession']:$online_ip;
	$_title = $_title?$_title:$_SEO['title'];
	$_title = ($_title=='-1')?'':$_title;
	$_time_tj_arr = array();
	$_time_tj_arr['userid'] = US_USERID;
	$_time_tj_arr['username'] = US_USERNAME;
	$_time_tj_arr['type'] = $_tongji?$_tongji:'正在浏览';
	$_time_get = !empty($_get)?$_get:$_GET;
	$_time_ip_arr = explode(".", $online_ip);
	unset($_time_get['vs']);
	unset($_time_get['sid']);
	unset($_time_get['index']);
	ksort($_time_get);
	$_time_tj_arr['get'] = array2string($_time_get);
	$_time_tj_arr['get_md5'] = substr(md5($_time_tj_arr['get']),8,16);
	$_time_tj_arr['title'] = $_title;
	$_time_tj_arr['session'] = $_tjsession;
	$_time_tj_arr['ip'] = $online_ip;
	$_time_tj_arr['ip2'] = $_time_ip_arr[0].".".$_time_ip_arr[1].".*.*";
	$_time_tj_arr['time'] = SYS_TIME;
	$_time_tj_arr['site'] = $_CFG['site'];
	if ($_time_tj_arr['userid']){
		$_time_tj_where = ' `userid` = '.$_time_tj_arr['userid'];
	}else{
		$_time_tj_where = '`session` = \''.$_tjsession.'\'';
	}
	$_add_true = true; $_time_n = 1;
	$_tj_arr = $db->getall("select id,get_md5,time from ".table('tongji')." WHERE {$_time_tj_where} ORDER BY id DESC LIMIT 0,3");
	foreach($_tj_arr as $__val) {
		if ($__val['get_md5'] == $_time_tj_arr['get_md5']){
			$_add_true = false;
			$db -> query("update ".table('tongji')." set `time`=".SYS_TIME." WHERE id={$__val['id']}");
			break;
		}
		$_time_n++;
	}
	if ($_add_true == true){
		inserttable(table('tongji'), $_time_tj_arr);
	}
}
/**
 * 获取用户头像
 * @param $_uid 用户ID
 * @param $_size 头像大小，分：大、中、小、微；默认：中
 */
function get_touxiang($_uid, $_size = '中'){
	global $_CFG;
	if ($_size == '大'){
		$_name = '180';
	}elseif ($_size == '中'){
		$_name = '90';
	}elseif ($_size == '小'){
		$_name = '45';
	}elseif ($_size == '最小' || $_size == '微'){
		$_name = '30';
	}else{
		$_name = '90';
	}
	$_namef = $_uid.'_'.$_name.'.gif';
	$_mulu = getavatar($_uid, 1);
	if (file_exists($_mulu.$_namef)){
		$dir1 = ceil($_uid / 10000);
		$dir2 = ceil($_uid % 10000 / 1000);
		return $_CFG['site_domain'].'/uploadfiles/avatar/'.$dir1.'/'.$dir2.'/'.$_namef;
	}else{
		return IMG_PATH.'nophoto_'.$_name.'.gif';
	}
}
/**
 * 根据uid获取头像url
 * @param int $uid 用户id
 * @param $type 赋值获取目录
 * @return array 四个尺寸用户头像数组
 */
function getavatar($uid, $type = '') {
	global $_CFG;
	$dir1 = ceil($uid / 10000);
	$dir2 = ceil($uid % 10000 / 1000);
	if ($type){
		if ($type == 'default'){
			return $_CFG['site_domain'].'/uploadfiles/avatar/'.$dir1.'/'.$dir2.'/'.$uid.'_default.gif';
		}else{
			make_dir(KF_ROOT_PATH.'uploadfiles/avatar/'.$dir1.'/'.$dir2.'/');
			return KF_ROOT_PATH.'uploadfiles/avatar/'.$dir1.'/'.$dir2.'/';
		}
	}
	$url = $_CFG['site_domain'].'/uploadfiles/avatar/'.$dir1.'/'.$dir2.'/';
	$avatar = array('180'=>$url.$uid.'_180.gif', '90'=>$url.$uid.'_90.gif', '45'=>$url.$uid.'_45.gif', '30'=>$url.$uid.'_30.gif');
	return $avatar;
}
/**
 * 删除会员头像文件
 * @param $uid
 */
function del_touxiang($uid){
	global $_CFG,$db;
	$dir1 = ceil($uid / 10000);
	$dir2 = ceil($uid % 10000 / 1000);
	$url = KF_ROOT_PATH.'uploadfiles/avatar/'.$dir1.'/'.$dir2.'/';
	$avatar = array('180'=>$url.$uid.'_180.gif', '90'=>$url.$uid.'_90.gif', '45'=>$url.$uid.'_45.gif', '30'=>$url.$uid.'_30.gif', 'default'=>$url.$uid.'_default.gif');
	foreach ($avatar as $_val) {
		@unlink($_val);
	}
	$db->query("UPDATE ".table('huiyuan')." SET avatar='0' WHERE userid='{$uid}'");
}
/**
 * 写入文本
 * @param $cache_file_path 文件路径
 * @param $config_val 保存的内容
 */
function write_flie_text($cache_file_path, $config_val)
{
	global $_CFG;	
	$content = $config_val;
	if(get_magic_quotes_gpc() || $_CFG['open_post']=='addslashes'){//如果get_magic_quotes_gpc()是打开的
		$content = stripslashes($content);//将字符串进行处理
	}
	if (!file_put_contents($cache_file_path, $content, LOCK_EX))
	{
		$fp = @fopen($cache_file_path, 'wb+');
		if (!$fp)
		{
			showmsg("系统提醒", "写入文本失败！");
		}
		if (!@fwrite($fp, trim($content)))
		{
			showmsg("系统提醒", "写入文本失败！");
		}
		@fclose($fp);
	}
}

/**
 * 来源地址 
 * @param $contentid 内容ID
 */
function from_url(){
	return urlencode(get_link('','&','','',1));
}
/**
 * 
 * 处理(来源)地址 
 * @param $_url 来源地址
 * @param $_str 去除变量
 * @param $_typ 替换模式，留空替换模式、赋值去除模式
 * @param $_amp “&”连接符号的显示方式
 */
function goto_url($_url, $_str = 'vs|sid', $_typ = '', $_amp = ''){
	$_url = urldecode($_url);
	$_url = str_replace('&amp;', '&', $_url);
	$_url = str_replace('&', '&amp;', $_url);
	if (!empty($_str)){
		$_url.= '&amp;';
		$_str = str_replace("|", ",", $_str);
		$_strarr = explode(',',$_str);
		$_val = '';
		foreach($_strarr as $value){
			if (!empty($value)){
				if (empty($_typ)){
					$_url = preg_replace("/\?{$value}\=(.+?)\&amp;/is", "?{$value}={$_GET[$value]}&amp;", $_url);
					$_url = preg_replace("/\&amp;{$value}\=(.+?)\&amp;/is", "&amp;{$value}={$_GET[$value]}&amp;", $_url);
				}else{
					$_url = preg_replace("/\?{$value}\=(.+?)\&amp;/is", "?", $_url);
					$_url = preg_replace("/\&amp;{$value}\=(.+?)\&amp;/is", "&amp;", $_url);
				}
			}
		}
		$_url = rtrim($_url, '&amp;');
	}
	if (!empty($_amp)){
		$_url = str_replace('&amp;', $_amp, $_url);
	}
	return $_url;
}

/**
 * 通过IP地址取验证码标识
 */
function yanzhengmaip(){
	global $online_ip;	
	return 'k'.substr(md5s(str_replace('.', '', $online_ip)), -5);
}

/**
 * 判断email格式是否正确
 * @param $email
 */
function is_email($email) {
	return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}
/**
 * 判断纯数字
 */
function is_num($num){
    $alb= array('0','1','2','3','4','5','6','7','8','9');
    if(strlen($num)<1){
        return false;
    }
    for ($i=0;$i<=strlen($num);$i++){
        if(!in_array(substr($num,$i,1),$alb)){
            return false;
        }
    }
    return true;
}
/**
 * 判断字符串是否为utf8编码，英文和半角字符返回ture
 * @param $string
 * @return bool
 */
function is_utf8($string) {
	return preg_match('%^(?:
					[\x09\x0A\x0D\x20-\x7E] # ASCII
					| [\xC2-\xDF][\x80-\xBF] # non-overlong 2-byte
					| \xE0[\xA0-\xBF][\x80-\xBF] # excluding overlongs
					| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
					| \xED[\x80-\x9F][\x80-\xBF] # excluding surrogates
					| \xF0[\x90-\xBF][\x80-\xBF]{2} # planes 1-3
					| [\xF1-\xF3][\x80-\xBF]{3} # planes 4-15
					| \xF4[\x80-\x8F][\x80-\xBF]{2} # plane 16
					)*$%xs', $string);
}

/**
 * 检查密码长度是否符合规定
 *
 * @param STRING $password
 * @return 	TRUE or FALSE
 */
function is_password($password) {
	$strlen = strlen($password);
	if($strlen >= 6 && $strlen <= 20) return true;
	return false;
}

/**
 * 检测输入中是否含有错误字符
 *
 * @param char $string 要检查的字符串名称
 * @return TRUE or FALSE
 */
function is_badword($string) {
	$badwords = array("\\",'&',' ',"'",'"','/','*',',','<','>',"\r","\t","\n","#");
	foreach($badwords as $value){
		if(strpos($string, $value) !== FALSE) {
			return TRUE;
		}
	}
	return FALSE;
}

/**
 * 检查用户名是否符合规定
 *
 * @param STRING $username 要检查的用户名
 * @return 	TRUE or FALSE
 */
function is_username($username) {
	$strlen = strlen($username);
	if(is_badword($username) || !preg_match("/^[a-zA-Z0-9_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]+$/", $username)){
		return false;
	} elseif ( 20 < $strlen || $strlen < 2 ) {
		return false;
	}
	return true;
}

/**
 * 跳转页面
 * Enter description here ...
 * @param $url
 */
function tiaozhuan($url) {
	global $_CFG;
	if ($_CFG['minrefreshtime']) {
		list($usec, $sec) = explode(' ', microtime());
		$__minrefreshtime = ((float)$usec + (float)$sec);
		$_SESSION['minrefreshtime'] = $__minrefreshtime - $_CFG['minrefreshtime'];
	}
	if (!empty($url)){
		$url = str_replace('&amp;', '&', $url);
		header("Location: ".$url);
		exit();
	}
}
/**
 * 生成缩略图函数
 * @param  $imgurl 图片路径
 * @param  $width  缩略图宽度
 * @param  $height 缩略图高度
 * @param  $autocut 是否自动裁剪 默认裁剪，当高度或宽度有一个数值为0是，自动关闭
 * @param  $smallpic 无图片是默认图片路径
 */
function img_thumb($imgurl, $width = 100, $height = 100 ,$autocut = 1, $smallpic = 'nopic.gif') {
	global $image_t,$_CFG;
	$upload_url = $_CFG['site_domain'].$_CFG['site_dir'].'uploadfiles/';
	$upload_path = KF_ROOT_PATH.'uploadfiles/';
	if(empty($imgurl)) return IMG_PATH.$smallpic;
	$imgurl_replace= str_replace($upload_url, '', $imgurl);
	if(!extension_loaded('gd') || strpos($imgurl_replace, '://')) return $imgurl;
	if(!file_exists($upload_path.$imgurl_replace)) return IMG_PATH.$smallpic;

	list($width_t, $height_t, $type, $attr) = getimagesize($upload_path.$imgurl_replace);
	if($width>=$width_t || $height>=$height_t) return $imgurl;

	$newimgurl = 'thumb/'.dirname($imgurl_replace).'/thumb_'.$width.'_'.$height.'_'.basename($imgurl_replace);

	if(file_exists($upload_path.$newimgurl)) return $upload_url.$newimgurl;
	make_dir(dirname($upload_path.$newimgurl));

	if(!is_object($image_t)) {
		kf_class::run_sys_class('image','','0');
		$image_t = new image(1,0);
	}
	return $image_t->thumb($upload_path.$imgurl_replace, $upload_path.$newimgurl, $width, $height, '', $autocut) ? $upload_url.$newimgurl : $imgurl;
}

/**
 * 水印添加
 * @param $source 原图片路径
 * @param $target 生成水印图片途径，默认为空，覆盖原图
 * @param $siteid 站点id，系统需根据站点id获取水印信息
 */
function img_watermark($source, $target = '',$siteid) {
	global $image_w;
	if(empty($source)) return $source;
	if(!extension_loaded('gd') || strpos($source, '://')) return $source;
	if(!$target) $target = $source;
	if(!is_object($image_w)){
		kf_class::run_sys_class('image','','0');
		$image_w = new image(0,$siteid);
	}
		$image_w->watermark($source, $target);
	return $target;
}

/**
 * 返回货币名称
 * @param $str
 */
function money_type($str){
	global $_CFG;
	if ($str=="amount"){
		return $_CFG['amountname'];
	}
	if ($str=="point"){
		return "积分";
	}
}

/**
 * 会员ID跟会员帐号互转
 * @param $_str 会员ID或会员帐号
 * @param $_type  0为会员ID转会员帐号，1为会员帐号转会员ID
 * @param $_ziduan  读取的字段，$_type=0时默认username、$_type=1时默认userid
 */
function id_name($_str, $_type = 0, $_ziduan = ''){
	global $db;
	if (empty($_type)){
		$_row = $db -> getone("select * from ".table('huiyuan')." WHERE `userid`='{$_str}'");
		if (empty($_row)){
			return "";
		}else{
			$_ziduan = $_ziduan?$_ziduan:'username';
			return $_row[$_ziduan];
		}
	}else{
		$_row = $db -> getone("select * from ".table('huiyuan')." WHERE `username`='{$_str}'");
		if (empty($_row)){
			return 0;	
		}else{
			$_ziduan = $_ziduan?$_ziduan:'userid';
			return $_row[$_ziduan];	
		}
	}
}
/**
 * 
 * 通过会员名获取会员信息
 * @param $username 会员名
 * @param $detail 是否获取详情
 */
function get_to_username($username, $detail = 0){
	global $db;
	if (empty($username)) return array();
	$_sql = "select * from ".table('huiyuan')." WHERE `username`='{$username}'";
	$userdb = $db -> getone($_sql);
	if ($detail && $userdb){
		$modellistarr = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_moxing.php');
		$detaildb = $db -> getone("select * from ".table('huiyuan_diy_'.$modellistarr[$userdb['modelid']]['tablename'])." WHERE userid = {$userdb['userid']} LIMIT 1");
		$userdb = array_merge($userdb, $detaildb);
	}
	return $userdb;
}
function qianming($str){
	global $db;
	if (!is_array($str)){
		$strow = $db -> getone("select userid,username,qianming,groupid from ".table('huiyuan')." WHERE `username`='{$str}'");
		if (empty($strow)) return $str;
		return qianming($strow);
	}
	$grouplist = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_zu.php');
	$groupuser = $grouplist[$str['groupid']];
	if (!$groupuser['qianminghtml']){
		$str['qianming'] = htmlspecialchars($str['qianming']);
	}
	if ($groupuser['qianmingubb']){
		kf_class::run_sys_func('ubb');
		$str['qianming'] = ubb_qianming($str['qianming'], $groupuser['qianmingubbnum']);
	}
	return $str['qianming'];
}
/**
 * 获取颜色用户名
 * @param $str 会员用户名
 * @param $_color 自定义颜色，默认无
 * @param $_xunzhang 取会员勋章，默认取，0不取
 * @param $_noname 只取勋章：0只取勋章，1用户名和取勋章，默认1
 * @param $_inid 用户名含用户ID，留空不包含
*/
function colorname($str, $_color = '', $_xunzhang = 1, $_noname = 1, $_inid = ''){
	global $db;
	if (!is_array($str)){
		$strow = $db -> getone("select userid,username,nickname,colorname,boldname from ".table('huiyuan')." WHERE `username`='{$str}'");
		if (empty($strow)) return $str;
		return colorname($strow, $_color, $_xunzhang, $_noname, $_inid);
	}
	if (empty($str['nickname'])) $str['nickname'] = $str['username'];
	//取勋章
	$_xuntext = "";
	if ($_xunzhang){
		if (file_exists(KF_ROOT_PATH.'caches/caches_xunzhang/'.$str['userid'].'.php')) {
			$xunlist = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_xunzhang'.DIRECTORY_SEPARATOR.$str['userid'].'.php');
			foreach($xunlist as $_v) {
				if ($_v['imgurl']){
					$_xuntext.= '<img src="'.$_v['imgurl'].'" alt="'.$_v['title'].'"/>';
				}else{
					$_xuntext.= $_v['title'];
				}
			}
		}
	}
	if (empty($_noname)){
		return $_xuntext;
	}
	if ($_inid){
		$str['nickname'].= "(".$_inid.$str['userid'].")";
	}
	if (!empty($_color)){
		if ($_GET['vs']==1){
			return '<img src="index.php?m=api&amp;c=yansezi&amp;str='.urlencode($str['nickname']).'&amp;color='.$_color.'" alt="'.$str['nickname'].'"/>'.$_xuntext;
		}else{
			return "<font color='{$_color}'>{$str['nickname']}</font>".$_xuntext;
		}
	}
	if ($str['boldname'] == 1) $str['nickname'] = "<b>".$str['nickname']."</b>";
	if ($_GET['vs'] > 1 && $str['colorname']){
		return "<font color='{$str['colorname']}'>{$str['nickname']}</font>".$_xuntext;
	}else{
		return $str['nickname'].$_xuntext;
	}
}
/**
 * 获取颜色用户名(含用户ID)
 * @param $str 会员用户名
 * @param $_color 自定义颜色，默认无
*/
function colorname2($str, $_color = ''){
	return colorname($str, $_color, 1, 1, 'ID:');
}
/**
 * 获取用户名勋章
 * @param $str 会员用户名
*/
function medalname($str){
	return colorname($str, '', 1, 0);
}
/**
 * 获取颜色用户名(仅颜色不含勋章)
 * @param $str 会员用户名
 * @param $_color 自定义颜色，默认无
*/
function onlycolorname($str, $_color = ''){
	return colorname($str, $_color, 0);
}
/**
 * 获取UCenter用户信息
 * @param $ucuid
 */
function get_uc_database($ucuid) {
	kf_class::ucenter();
	if (UC_USE == '1'){
		kf_class::run_sys_class('mysql','',0);
		$uc_db = new mysql(UC_DBHOST,UC_DBUSER,UC_DBPW,UC_DBNAME);
		$uc_users = $uc_db -> getone("select * from ".UC_DBTABLEPRE."members WHERE `uid`='{$ucuid}'");
		unset($uc_db);
	}else{
		$uc_users = array();
	}
	return $uc_users;
}

/**
 * 未读信息
 */
function weiduxx($_arr, $_str2 = ''){
	global $db,$huiyuan_val;
	$strr = explode(',',$_arr);
	$str = $strr[0];
	$islink = $strr[1];
	if ($_str2 != '') $islink = $_str2;
	if ($str == 1){
		$new_xinxi = $db->get_total("SELECT COUNT(*) AS num FROM ".table('xinxi_xitong')." WHERE typeid='1' AND groupid='{$huiyuan_val['groupid']}' AND status='1' AND id not in (SELECT group_message_id FROM ".table('xinxi_data')." WHERE userid='{$huiyuan_val['userid']}')");
		if ($islink) $new_xinxi = "<a href=\"index.php?m=xinxi&amp;c=xitong&amp;sid={$_GET['sid']}&amp;vs={$_GET['vs']}\">{$new_xinxi}</a>";
	}else{
		$new_xinxi = $db->get_total("SELECT COUNT(*) AS num FROM ".table('xinxi')." WHERE send_to_id='{$huiyuan_val['username']}' AND folder='inbox' AND status='1'");
		if ($islink) $new_xinxi = "<a href=\"index.php?m=xinxi&amp;c=shoujian&amp;sid={$_GET['sid']}&amp;vs={$_GET['vs']}\">{$new_xinxi}</a>";
	}
	return $new_xinxi;
}
/**
 * 在线会员数
 */
function zxhys($str){
	global $db;
	$str = intval($str)?$str:60;
	$wheresql = SYS_TIME - ($str * 60);
	$wheresql = " WHERE `indate`>=".$wheresql."";
	$total_sql="SELECT COUNT(*) AS num FROM ".table('huiyuan').$wheresql;
	$total_count=$db->get_total($total_sql);
	return $total_count;
}
//
function set_csrf_token($type = 0){
	global $_CFG;
	$_hidtoken = "";
	if ($_CFG["open_csrf"]=="1"){
		if (!empty($_SESSION['token'])){
			unset($_SESSION['token']);
		}
		$hash = md5(uniqid(rand(), true));
		$n = mt_rand(1, 24);
		$token = substr($hash, $n, 8);
		$_page=!empty($_SERVER['PHP_SELF'])?md5($_SERVER['PHP_SELF']):'token';
		$_SESSION['token'][$_page] = $token;
		if ($type==1) {
			$_hidtoken.= "<postfield name=\"hidden-csrfpage\" value=\"{$_page}\"/>";
			$_hidtoken.= "<postfield name=\"hidden-csrftoken\" value=\"{$token}\"/>";
		}else{
			$_hidtoken.= "<input type=\"hidden\"  name=\"hidden-csrfpage\" value=\"{$_page}\" />";
			$_hidtoken.= "<input type=\"hidden\"  name=\"hidden-csrftoken\" value=\"{$token}\" />";
		}
	}
	return $_hidtoken;
}
function check_csrf_token(){
	global $_CFG;
	if ($_CFG["open_csrf"]=="1"){
		$_page = $_POST['hidden-csrfpage'];
		if ($_page) {
			$_token = $_POST['hidden-csrftoken'];
			if ($_SESSION['token'][$_page] == "check".$_token) {
				global $smarty;
				$links[0]['title'] = '继续访问';
				$links[0]['href'] = get_link();
				$links[1]['title'] = '返回网站首页';
				$links[1]['href'] = kf_url('index');
				$smarty -> assign('nodb', 1);
				showmsg("系统提醒", "<b>错误，请勿刷新提交页面！</b>", $links);
			}
			$_SESSION['token'][$_page] = "check".$_token;
			unset($_POST['hidden-csrfpage']);
			unset($_POST['hidden-csrftoken']);
		}else{
			if ($_POST['dosubmit']){
				if ($_SESSION['open_csrf'] == md5(array2string($_GET).array2string($_POST))){
					global $smarty;
					$links[0]['title'] = '继续访问';
					$links[0]['href'] = get_link();
					$links[1]['title'] = '返回网站首页';
					$links[1]['href'] = kf_url('index');
					$smarty -> assign('nodb', 1);
					showmsg("系统提醒", "<b>错误，请勿刷新提交页面！</b>", $links);
				}
				$_SESSION['open_csrf'] = md5(array2string($_GET).array2string($_POST));
			}else{
				$_SESSION['open_csrf'] = '0';
			}
		}
	}
}
/**
 * 判断字符串存在(包含)
 * @param string $string
 * @param string $find
 * @return bool
 */
function strexists($string, $find) {
	return !(strpos($string, $find) === FALSE);
}

/**
 * 判断字符串开头包含
 * @param string $string        //原字符串
 * @param string $find          //判断字符串
 * @param bool|false $lower     //是否不区分大小写
 * @return int
 */
function leftexists($string, $find, $lower = false) {
	if ($lower) {
		$string = strtolower($string);
		$find = strtolower($find);
	}
	return (substr($string, 0, strlen($find)) == $find);
}

/**
 * 判断字符串结尾包含
 * @param string $string        //原字符串
 * @param string $find          //判断字符串
 * @param bool|false $lower     //是否不区分大小写
 * @return int
 */
function rightexists($string, $find, $lower = false) {
	if ($lower) {
		$string = strtolower($string);
		$find = strtolower($find);
	}
	return (substr($string, strlen($find)*-1) == $find);
}

/**
 * @param $errno
 * @param string $message
 * @return array
 */
function error($errno, $message = '') {
	return array(
		'errno' => $errno,
		'message' => $message,
	);
}

/**
 * @param $data
 * @return bool
 */
function is_error($data) {
	if (empty($data) || !is_array($data) || !array_key_exists('errno', $data) || (array_key_exists('errno', $data) && $data['errno'] == 0)) {
		return false;
	} else {
		return true;
	}
}

?>