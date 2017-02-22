<?php
/**
 * 快范CMS 文件上传
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
 * cms 文件上传
 * @param: $dir      -- 存放目录,最后加"/" [字串] 
 * @param: $file_var -- 表单变量 [字串] 
 * @param: $max_size -- 设定最大上传值,以k为单位. [整数/浮点数] 
 * @param: $type     -- 限定后辍名(小写)，多个用"/"隔开,不限定则留空 [字串] 
 * @param: $name     -- 上传后命名,留空则为原名,true为系统随机定名 [布林值] 
 * @param: $ext      -- 上传 后命名是否后缀名,true是 false保留元后缀名
 * @param: $watermark-- 水印 true按配置自动 false取消水印
 * return: 上传后文件名
*/ 
function _asUpFiles($dir, $file_var, $max_size='', $type='', $name=false, $ext=false, $watermark=true) {
	if (!file_exists($dir)){
		showmsg("系统提醒", "文件上传失败：上传目录 ".$dir." 不存在!");
	}elseif (!is_writable($dir)){
		showmsg("系统提醒", "文件上传失败：上传目录 ".$dir." 无法写入!");
		exit(); 
	}
	$upfile=& $_FILES["$file_var"]; 
	$upfilename =  $upfile['name']; 
	if (!empty($upfilename)) {
		$upconfig = getcache(KF_ROOT_PATH. "caches/caches_peizhi_mokuai/cache.fujian.php");
		if ($upconfig['upload_maxsize'] > 0 && $upconfig['upload_maxsize'] < $max_size) $max_size = $upconfig['upload_maxsize'];
		if (!is_uploaded_file($upfile['tmp_name'])) {
			showmsg("系统提醒", "文件上传失败：你选择的文件无法上传；<br/>可能原因：文件太大, 服务器限制最大上传".$max_size."KB");
			exit(); 
		}elseif ($max_size>0 && $upfile['size']/1024>$max_size){ 
			showmsg("系统提醒", "文件上传失败：文件大小不能超过  ".$max_size."KB");
			exit(); 
		}
		$ext_name = strtolower(str_replace(".","",strrchr($upfilename, ".")));
		if (!empty($type)){
			$arr_type=explode('/',$type);
			$arr_type=array_map("strtolower",$arr_type);
			if (!in_array($ext_name,$arr_type)){
				showmsg("系统提醒", "文件上传失败：只允许上传格式为 ".$type." 的文件！");
				exit(); 
			}
		/* 	$imgtype=array("jpg","gif","jpeg","bmp","png");		
			if (in_array($ext_name,$imgtype))
			{
				$imageinfo = getimagesize($upfile['tmp_name']);
				if (empty($imageinfo[0]) || empty($imageinfo[1]))
				{
				showmsg("系统提醒", "文件上传失败：只允许上传 ".$type." 的文件！");
				exit();
				}
			} */
			$harmtype=array("asp","php","jsp","js","css","php3","php4","ashx","aspx","exe");	
			if (in_array($ext_name,$harmtype)){
				showmsg("系统提醒", "文件上传失败：所上传的文件类型错误！");
				exit(); 
			}
		}
			if (!is_bool($name)){
				if ($ext){
					$uploadname=$name;
				}else{
					$uploadname=$name.".".$ext_name;
				}
			}elseif ($name===true){
				$uploadname=time().mt_rand(100,999).".".$ext_name;
			}
			if (!move_uploaded_file($upfile['tmp_name'], $dir.$uploadname)) { 
				showmsg("系统提醒", "文件上传失败：文件上传出错！");
				exit(); 
			} 
			$uploadname = preg_replace("/(php|phtml|php3|php4|jsp|exe|dll|asp|cer|asa|shtml|shtm|aspx|asax|cgi|fcgi|pl)(\.|$)/i", "_\\1\\2", $uploadname);
			$savefile = realpath($dir.$uploadname);
			@chmod($savefile, 0644);
			$thumb_enable = ($upconfig['thumb_width'] > 0 || $upconfig['thumb_height'] > 0 ) ? 1 : 0;	
			kf_class::run_sys_class('image','','0');
			$image = new image($thumb_enable, $upconfig);
			//缩放
			if($thumb_enable) {
				$image->thumb($savefile,'',$upconfig['thumb_width'],$upconfig['thumb_height']);
			}
			//水印
			if($watermark && $upconfig['watermark_enable']) {
				$image->watermark($savefile, $savefile);
			}
			
			return $uploadname; 
	}
	return ''; 
} 
/**
 * 实现远程下载文件到本地
 *  @param: $url      要下载的文件地址
 *  @param: $folder   保存目录位置
 *  @param: $pic_name 保存文件名
 *  @param: $timeout  超时时间
 */
function get_file($url,$folder,$pic_name,$timeout = 300){
	set_time_limit($timeout); //限制最大的执行时间
	$destination_folder=$folder?$folder.'/':''; //文件下载保存目录
	$newfname=$destination_folder.$pic_name;//文件PATH
	$file=fopen($url,'rb');

	if($file){
		$newf=fopen($newfname,'wb');
		if($newf){
			while(!feof($file)){
				if (!fwrite($newf,fread($file,1024*8),1024*8)) return false;
			}
		}else{
			return false;
		}
		if($file) fclose($file);
		if($newf) fclose($newf);
		return true;
	}else{
		return false;
	}
}

/**
 * php完美实现下载远程图片保存到本地
 * @param $url      要下载的文件地址
 * @param $save_dir 保存目录位置
 * @param $filename 保存文件名
 * @param $timeout  超时时间
 * @param $type 	采集方式
 */
function getImage($url, $save_dir='', $filename='', $timeout = 300, $type=0){
	set_time_limit($timeout); //限制最大的执行时间
	if(trim($url)==''){
		return array('file_name'=>'','save_path'=>'','error'=>1);
	}
	if(trim($save_dir)==''){
		$save_dir='./';
	}
	if(trim($filename)==''){//保存文件名
		$ext=strrchr($url,'.');
		if($ext!='.gif'&&$ext!='.jpg'){
			return array('file_name'=>'','save_path'=>'','error'=>3);
		}
		$filename=time().$ext;
	}
	if(substr($save_dir, -1) != '/'){
		$save_dir.='/';
	}
	//创建保存目录
	if(!file_exists($save_dir)&&!mkdir($save_dir,0777,true)){
		return array('file_name'=>'','save_path'=>'','error'=>5);
	}
	//获取远程文件所采用的方法
	if($type){
		$ch=curl_init();
		$timeout=5;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		$img=curl_exec($ch);
		curl_close($ch);
	}else{
		ob_start();
		readfile($url);
		$img=ob_get_contents();
		ob_end_clean();
	}
	//$size=strlen($img);
	//文件大小
	$fp2=@fopen($save_dir.$filename,'a');
	fwrite($fp2,$img);
	fclose($fp2);
	unset($img,$url);
	return array('file_name'=>$filename,'save_path'=>$save_dir.$filename,'error'=>0);
}

/**
 * 图象缩略函数
 * 参数说明：
 * @param: $srcfile 原图地址； 
 * @param: $dir  新图目录 
 * @param: $thumbwidth 缩小图宽最大尺寸 
 * @param: $thumbheight 缩小图高最大尺寸 
 * @param: $ratio 默认等比例缩放 为1是缩小到固定尺寸。 
 * @param: $dirname 保存到新文件名
*/ 
function makethumb($srcfile,$dir,$thumbwidth,$thumbheight,$ratio=0,$dirname='')
{ 
 //判断文件是否存在 
if (!file_exists($srcfile))return false;
 //生成新的同名文件，但目录不同 
$imgname=explode('/',$srcfile); 
$arrcount=count($imgname); 
if ($dirname){
	$dstfile = $dir.$dirname; 
}else{
	$dstfile = $dir.$imgname[$arrcount-1]; 
}
//缩略图大小 
$tow = $thumbwidth; 
$toh = $thumbheight; 
if($tow < 40) $tow = 40; 
if($toh < 45) $toh = 45;    
 //获取图片信息 
    $im =''; 
    if($data = getimagesize($srcfile)) { 
        if($data[2] == 1) { 
            $make_max = 0;//gif不处理 
            if(function_exists("imagecreatefromgif")) { 
                $im = imagecreatefromgif($srcfile); 
            } 
        } elseif($data[2] == 2) { 
            if(function_exists("imagecreatefromjpeg")) { 
                $im = imagecreatefromjpeg($srcfile); 
            } 
        } elseif($data[2] == 3) { 
            if(function_exists("imagecreatefrompng")) { 
                $im = imagecreatefrompng($srcfile); 
            } 
        } 
    } 
    if(!$im) return ''; 
    $srcw = imagesx($im); 
    $srch = imagesy($im); 
    if (empty($thumbwidth)) $tow = $srcw;
    if (empty($thumbheight)) $toh = $srch;
    $towh = $tow/$toh; 
    $srcwh = $srcw/$srch; 
    if($towh <= $srcwh){ 
        $ftow = $tow; 
        $ftoh = $ftow*($srch/$srcw); 
    } else { 
        $ftoh = $toh; 
        $ftow = $ftoh*($srcw/$srch); 
    } 
    if($ratio){ 
        $ftow = $tow; 
        $ftoh = $toh; 
    } 
    //缩小图片 
    if($srcw > $tow || $srch > $toh || $ratio) { 
        if(function_exists("imagecreatetruecolor") && function_exists("imagecopyresampled") && @$ni = imagecreatetruecolor($ftow, $ftoh)) { 
            imagecopyresampled($ni, $im, 0, 0, 0, 0, $ftow, $ftoh, $srcw, $srch); 
        } elseif(function_exists("imagecreate") && function_exists("imagecopyresized") && @$ni = imagecreate($ftow, $ftoh)) { 
            imagecopyresized($ni, $im, 0, 0, 0, 0, $ftow, $ftoh, $srcw, $srch); 
        } else { 
            return ''; 
        } 
        if(function_exists('imagejpeg')) { 
            imagejpeg($ni, $dstfile); 
        } elseif(function_exists('imagepng')) { 
            imagepng($ni, $dstfile); 
        } 
    }else { 
        //小于尺寸直接复制 
    copy($srcfile,$dstfile); 
    } 
    imagedestroy($im); 
    if(!file_exists($dstfile)) { 
        return ''; 
    } else { 
        return $dstfile; 
    } 
}
/**
 * 附件上传修改
 */
function _db_fujian($str, $modelid, $_url = array(), $_type = 0){
 	global $db,$_CFG,$online_ip,$admin_val;

	$_timesql = array();
	$_timesql['commentid'] = $str;
	$_timesql['modelid'] = $modelid;
	$_timesql['title'] = $_url['title'];
	$_timesql['name'] = $_url['name'];
	$_timesql['body'] = $_url['body'];
	$_timesql['url'] = $_url['url'];
	$_timesql['allurl'] = $_url['allurl'];
	$_timesql['field'] = $_url['field'];
	$_timesql['size'] = $_url['url']?abs(filesize($_url['url'])):get_filesize($_url['allurl']);
	$_timesql['format'] = get_extension($_url['allurl']);
	$_timesql['down'] = 0;
	$_timesql['addtime'] = SYS_TIME;
	$_timesql['addip'] = $online_ip;
	$_timesql['site'] = $_CFG['site'];
	
	if($_timesql['field'] == 'thumb'){
		$db -> query("update ".table('neirong_fujian')." set of='0' WHERE commentid='{$str}' AND field='thumb'");
	}
	if ($_type == 1){
		if ($admin_val['name']) admin_log("上传文件“{$_timesql['name']}”", $admin_val['name']);	
		return inserttable(table('neirong_fujian'),$_timesql, true);
	}else{
		$__timewh = "commentid='{$str}'";
		$__timedb = $db -> getone("select * from ".table('neirong_fujian')." WHERE {$__timewh} LIMIT 1");
		if (empty($__timedb)){
			if ($admin_val['name']) admin_log("上传文件“{$_timesql['name']}”", $admin_val['name']);	
			inserttable(table('neirong_fujian'),$_timesql, true);
		}else{
			if ($admin_val['name']) admin_log("重新上传文件“{$_timesql['name']}”", $admin_val['name']);	
			updatetable(table('neirong_fujian'), $_timesql, "{$__timewh}");
		}
	}
}
/**
 * 获取文件扩展名
 */
function get_extension($file){
	$info = pathinfo($file);
	return $info['extension'];
}
/**
 * 获取远程文件大小
 */
function get_filesize($url){ 
	$url = parse_url($url); 
	if($fp = @fsockopen($url['host'],empty($url['port'])?80:$url['port'],$error)){ 
	fputs($fp,"GET ".(empty($url['path'])?'/':$url['path'])." HTTP/1.1\r\n"); 
	fputs($fp,"Host:$url[host]\r\n\r\n"); 
	while(!feof($fp)){ 
		$tmp = fgets($fp); 
		if(trim($tmp) == ''){ 
			break; 
		}else if(preg_match('/Content-Length:(.*)/si',$tmp,$arr)){ 
			return trim($arr[1]); 
		} 
	} 
		return null; 
	}else{ 
		return null; 
	} 
}
/**
 * 获取文件夹大小
 */ 
function getDirSize($dir){ 
	$handle = opendir($dir);
	while (false!==($FolderOrFile = readdir($handle)))
	{ 
		if($FolderOrFile != "." && $FolderOrFile != "..") 
		{ 
			if(is_dir("$dir/$FolderOrFile"))
			{ 
				$sizeResult += getDirSize("$dir/$FolderOrFile"); 
			}
			else
			{ 
				$sizeResult += filesize("$dir/$FolderOrFile"); 
			}
		}    
	}
	closedir($handle);
	return $sizeResult;
}
/**
 * 文件大小单位转换GB MB KB
 */ 
function formatBytes($size, $type = 0) {
	if ($type) return _formatSize($size);
	$units = array(' B', ' KB', ' MB', ' GB', ' TB');
	for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
	return round($size, 2).$units[$i];
}
function _formatSize($filesize) {
	if($filesize >= 1073741824) {
		$filesize = round($filesize / 1073741824 * 100) / 100 . ' GB';
	} elseif($filesize >= 1048576) {
		$filesize = round($filesize / 1048576 * 100) / 100 . ' MB';
	} elseif($filesize >= 1024) {
		$filesize = round($filesize / 1024 * 100) / 100 . ' KB';
	} else {
		$filesize = $filesize . ' Bytes';
	}
	return $filesize;
}

/**
 * 上传权限检测
 * @param $groupid 用户组ID
 * @param $file_var 表单变量 
 * @param $msg 设置true时返回判断结果，默认直接提示判断结果
 * @return 如果有返回true为允许，false无权限上传
 */
function shangchuanquanxian($groupid, $file_var, $msg = false){
	$upfile=& $_FILES["$file_var"]; 
	$upfilename =  $upfile['name']; 
	if (!empty($upfilename)){
		$grouplist = getcache(KF_ROOT_PATH.'caches'.DIRECTORY_SEPARATOR.'cache_huiyuan_zu.php');
		$group_id = $grouplist[$groupid];
		if (empty($group_id['allowattachment'])){
			if ($msg){
				return false;
			}else{
				showmsg("系统提醒", "您所在的用户组不允许上传文件！");
			}
		}
	}
	return true;
}
?>