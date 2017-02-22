<?php
/*
 * 应用插件
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');
$yuanchengurl = "http://download.kuaifan.net/develop/getlist/gateway_1.php";
$localpath = KF_ROOT_PATH.'kuaifan'.DIRECTORY_SEPARATOR.'addons'.DIRECTORY_SEPARATOR;

if ($_GET['a'] == 'tianjia'){
    kf_class::run_sys_class('xta','',0);
    $xta = new XmlToArray();
    $_path = $localpath;
    $_applist = glob($_path.'*');
    $_apparr = array();
    foreach ($_applist as $path){
        if (is_dir($path)) {
            if (!file_exists($path.DIRECTORY_SEPARATOR.'manifest.xml')) {
                continue;
            }
            $XML = file_get_contents($path.DIRECTORY_SEPARATOR.'manifest.xml');
            $xta->setXml($XML);
            $_array = $xta->parseXml();
            //
            $aarr = array();
            $aarr['code'] = value($_array, 'manifest|application|code|content');
            $aarr['title'] = value($_array, 'manifest|application|title|content');
            $aarr['identifie'] = value($_array, 'manifest|application|identifie|content');
            $aarr['version'] = value($_array, 'manifest|application|version|content');
            $aarr['versiondate'] = value($_array, 'manifest|application|versiondate|content');
            $continue = false;
            foreach ($aarr AS $key => $val) {
                if (empty($val)) {
                    $continue = true;
                    break;
                }
            }
            if ($continue) continue;
            $aarr['ability'] = value($_array, 'manifest|application|ability|content');
            $aarr['description'] = value($_array, 'manifest|application|description|content');
            $aarr['author'] = value($_array, 'manifest|application|author|content');
            $aarr['url'] = value($_array, 'manifest|application|url|content');
            $arow = $db->getone("select v from ".table('yingyong')." WHERE `name`='".$aarr['identifie']."'");
            if ($arow) {
                if ($arow['v'] >= $aarr['versiondate']) {
                    continue;
                }
            }
            //
            $filename = basename($path);
            if ($aarr['identifie'] != $filename) {
                continue;
            }
            $aarr['setting'] = array();
            $aarr['setting']['install'] = value($_array, 'manifest|install|content');
            $aarr['setting']['uninstall'] = value($_array, 'manifest|uninstall|content');
            $aarr['setting']['upgrade'] = value($_array, 'manifest|upgrade|content');
            $aarr['setting']['undeldata'] = value($_array, 'manifest|undeldata|content'); //要删除的数据表
            $aarr['setting']['undeldir'] = value($_array, 'manifest|undeldir|content'); //要删除的目录
            $aarr['setting']['undelfile'] = value($_array, 'manifest|undelfile|content'); //要删除的文件
            $aarr['setting']['adminurl'] = value($_array, 'manifest|adminurl|content'); //要删除的文件
            $aarr['setting']['isold'] = value($_array, 'manifest|isold|content'); //老式应用
            //
            $filearr = explode('-', $filename);
            $_apparr[$filename]['title'] = $filename;
            $_apparr[$filename]['path'] = $path;
            $_apparr[$filename]['isup'] = ($arow)?1:0;
            $_apparr[$filename]['oldv'] = ($arow)?$arow['v']:'';
            $_apparr[$filename]['appinfo'] = $aarr;
        }
    }
	$smarty->assign('apparr', $_apparr);
}

if ($_GET['a'] == 'tianjia2'){
    if (!varify_url($yuanchengurl)){
        $links[0]['title'] = '返回后台首页';
        $links[0]['href'] = $_admin_indexurl;
        showmsg("系统提醒", "互联网连接失败无法，请确定服务器可以链接互联网后再重试！", $links);
    }
    $get_path = $yuanchengurl."?act=list";
    if ($_GET['type']) {
        $get_path.= "&type=".$_GET['type'];
    }
    if ($_GET['keyw']) {
        $get_path.= "&keyw=".$_GET['keyw'];
    }
    if ($_GET['page']) {
        $get_path.= "&page=".$_GET['page'];
    }
    if (isset($_POST['keyw'])) {
        tiaozhuan(get_link("keyw").'&keyw='.$_POST['keyw']);
    }
    $get_str = @file_get_contents($get_path);
    $getarr = json_decode($get_str, true);
    kf_class::run_sys_class('page','',0);
    $pagelist = new page(array('total'=>$getarr['total'],'perpage'=>$getarr['perpage'],'getarray'=>array_merge($_GET,array('vs'=>2)),'page_name'=>'page'));
    $getarr['pagelist'] = $pagelist->show('wap');
    $smarty->assign('getarr', $getarr);
}

if ($_GET['a'] == 'getup'){
    back_backhttp();
    $row = $db->getall("select * from ".table('yingyong'));
    $getarr = array();
    foreach($row AS $val) {
        $get_path = $yuanchengurl.'?identifie='.$val['name'];
        $get_str = @file_get_contents($get_path);
        if ($get_str) {
            $get_arr = json_decode($get_str, true);
            if ($get_arr['list'][0]['identifie'] == $val['name']) {
                if ($val['v'] < intval($get_arr['list'][0]['versiondate'])){
                    $getarr[$val['name']] = $get_arr['list'][0];
                }
            }
        }
    }
    exit(json_encode($getarr));
}

if ($_GET['a'] == 'info'){
    $getarr = array();
    if ($_GET['local']){
        kf_class::run_sys_class('xta','',0);
        $xta = new XmlToArray();
        $XML = @file_get_contents($localpath.DIRECTORY_SEPARATOR.$_GET['app'].DIRECTORY_SEPARATOR.'manifest.xml');
        $xta->setXml($XML);
        $_array = $xta->parseXml();
        //
        $aarr = array();
        $aarr['code'] = value($_array, 'manifest|application|code|content');
        $aarr['title'] = value($_array, 'manifest|application|title|content');
        $aarr['identifie'] = value($_array, 'manifest|application|identifie|content');
        $aarr['version'] = value($_array, 'manifest|application|version|content');
        $aarr['versiondate'] = value($_array, 'manifest|application|versiondate|content');
        foreach ($aarr AS $key => $val) {
            if (empty($val)) {
                showmsg("系统提醒", "错误的模块！"); break;
            }
        }
        $aarr['ability'] = value($_array, 'manifest|application|ability|content');
        $aarr['description'] = value($_array, 'manifest|application|description|content');
        $aarr['author'] = value($_array, 'manifest|application|author|content');
        $aarr['url'] = value($_array, 'manifest|application|url|content');
        $aarr['setting'] = array();
        $aarr['setting']['install'] = value($_array, 'manifest|install|content');
        $aarr['setting']['uninstall'] = value($_array, 'manifest|uninstall|content');
        $aarr['setting']['upgrade'] = value($_array, 'manifest|upgrade|content');
        $aarr['setting']['undeldata'] = value($_array, 'manifest|undeldata|content'); //要删除的数据表
        $aarr['setting']['undeldir'] = value($_array, 'manifest|undeldir|content'); //要删除的目录
        $aarr['setting']['undelfile'] = value($_array, 'manifest|undelfile|content'); //要删除的文件
        $aarr['setting']['adminurl'] = value($_array, 'manifest|adminurl|content'); //要删除的文件
        $aarr['setting']['isold'] = value($_array, 'manifest|isold|content'); //老式应用
        $getarr = $aarr;
    }else{
        $get_path = $yuanchengurl.'?upview=1&identifie='.$_GET['app'];
        $get_str = @file_get_contents($get_path);
        if ($get_str) {
            $get_arr = json_decode($get_str, true);
            if ($get_arr['list'][0]['identifie'] == $_GET['app']) {
                $getarr = $get_arr['list'][0];
            }
        }
    }
    if (empty($getarr)) {
		if ($_GET['islocal']) {
			tiaozhuan(get_link('islocal|local').'&local=1');
		}
        showmsg("系统提醒", "拉取信息失败，可能模块不存在请稍后再试！");
    }else{
        $getarr['isup'] = 0;
        $row = $db->getone("select * from ".table('yingyong')." WHERE `name`='{$getarr['identifie']}'");
        if ($row) {
            if ($row['v'] < intval($getarr['versiondate'])) {
                $getarr['isup'] = 1;
            }
        }
        $smarty->assign('nowapp', $row);
        $smarty->assign('getarr', $getarr);
        $smarty->assign('local', $_GET['local']);
    }
}

if ($_GET['a'] == 'az'){
	if ($admin_val['id']!='1') showmsg("系统提醒", "只有网站创始人才可以操作！");
    if (!varify_url($yuanchengurl)){
        $links[0]['title'] = '返回后台首页';
        $links[0]['href'] = $_admin_indexurl;
        showmsg("系统提醒", "互联网连接失败无法，请确定服务器可以链接互联网后再重试！", $links);
    }

	$row = $db->getone("select * from ".table('yingyong')." WHERE `name`='{$_GET['app']}'");
	if ($_GET['dosubmit']){
		//创建缓存文件夹
		if(!file_exists(KF_ROOT_PATH.'caches/caches_yingyong')) {
			@mkdir(KF_ROOT_PATH.'caches/caches_yingyong');
		}
        //
        if ($_CFG['inwebway'] == '2'){
            kf_class::run_sys_class('pclzip','',0);
        }else{
            kf_class::run_sys_class('zip','',0);
        }
        //
        if ($_GET['local']) {
            //本地安装包
            $get_path = $yuanchengurl.'?identifie='.$_GET['app'];
            $get_str = @file_get_contents($get_path);
            $getarr = array();
            if ($get_str) {
                $get_arr = json_decode($get_str, true);
                if ($get_arr['list'][0]['identifie'] == $_GET['app']) {
                    $getarr = $get_arr['list'][0];
                }
            }
            if ($getarr) {
                showmsg("系统提醒",
                    "此应用已设置版权保护，您只能通过<a href='".$_admin_indexurl."&amp;c=yingyong&amp;a=info&amp;app={$_GET['app']}'>【云平台】</a>来安装。");
            }
            //文件包路径
            $upgradezip_source_path = KF_ROOT_PATH.'kuaifan/addons'.DIRECTORY_SEPARATOR.$_GET['app'];
        }else{
            //
            $back = $db->getone("select * from ".table('yingyong_back')." WHERE `name`='{$_GET['app']}'");
            if (!$back){
                showmsg("系统提醒", "拉取数据失败，请重试-1！");
            }
            //检查升级
            if ($row && $back['v'] <= $row['v']){
                $links[0]['title'] = '回我的应用';
                $links[0]['href'] = $_admin_indexurl."&amp;c=yingyong";
                $links[1]['title'] = '继续查看更多应用';
                $links[1]['href'] = $_admin_indexurl."&amp;c=yingyong&amp;a=tianjia2";
                showmsg("系统提醒", "已经是最新版本无须更新！", $links);
            }
            //远程压缩包地址
            $upgradezip_url = "";
            $get_arr = array();
            $get_path = $yuanchengurl.'?act=install2&identifie='.$back['name'].'&sn='.$back['sn'].'&urlsn='.$back['urlsn'];
            $get_str = @file_get_contents($get_path);
            if ($get_str) {
                $get_arr = json_decode($get_str, true);
                $upgradezip_url = $get_arr['url'];
            }
            if (!$get_arr || !$upgradezip_url) {
                showmsg("系统提醒", "拉取数据失败，请重试-2！");
            }
            if ($row && $get_arr['identifie'] != $row['name']){
                showmsg("系统提醒", "拉取数据失败，请重试-3！");
            }
            if ($get_arr['identifie'] != $back['name']){
                showmsg("系统提醒", "拉取数据失败，请重试-4！");
            }
            //保存到本地地址
            $upgradezip_path = KF_ROOT_PATH.'caches/caches_yingyong'.DIRECTORY_SEPARATOR.$back['name'].'_'.SYS_TIME.'.zip';
            //解压路径
            $upgradezip_source_path = KF_ROOT_PATH.'caches/caches_yingyong'.DIRECTORY_SEPARATOR.$back['name'].'_'.SYS_TIME;
            make_dir($upgradezip_source_path);
            //下载压缩包
            kf_class::run_sys_func('communication');
            $_html = ihttp_request($upgradezip_url);
            if(!is_error($_html)) {
                $fp2 = @fopen($upgradezip_path,'a');
                fwrite($fp2, $_html['content']);
                fclose($fp2);
                if (!file_exists($upgradezip_path)) {
                    showmsg("系统提醒", "下载应用错误！");
                }
            }
            //解压缩
            if ($_CFG['inwebway'] == '2'){
                $archive = new PclZip($upgradezip_path);
                if($archive->extract(PCLZIP_OPT_PATH, $upgradezip_source_path, PCLZIP_OPT_REPLACE_NEWER) == 0) {
                    showmsg("系统提醒", "升级失败：".$archive->errorInfo(true)."<br/>建议：请稍后再试或到后台“网站配置”更改插件安装方式后再重试。");
                }
            }else{
                $archive = new PHPZip();
                $unevent = $archive -> unZipfun($upgradezip_path, $upgradezip_source_path);
                if (count($unevent[2]) > 0){
                    showmsg("系统提醒", "应用升级包{$_GET['app']}解压有".count($unevent[2])."个文件错误，请检查服务器是否支持zip扩展！<br/>建议：请稍后再试或到后台“网站配置”更改插件安装方式后再重试。");
                }
            }
        }
        //读取XML文件
        if (!file_exists($upgradezip_source_path.DIRECTORY_SEPARATOR.'manifest.xml')) {
            showmsg("系统提醒", "配置文件出错，请多试几次若还是出现此提示请联系应用开发商！");
        }
        kf_class::run_sys_class('xta','',0);
        $xta = new XmlToArray();
        $XML = @file_get_contents($upgradezip_source_path.DIRECTORY_SEPARATOR.'manifest.xml');
        $xta->setXml($XML);
        $_array = $xta->parseXml();
        if (!isset($_array['manifest'])) {
            showmsg("系统提醒", "解析配置文件失败，请稍后再试或到后台“网站配置”更改插件安装方式后再重试。");
        }
        //
        $aarr = array();
        $aarr['code'] = value($_array, 'manifest|application|code|content');
        $aarr['title'] = value($_array, 'manifest|application|title|content');
        $aarr['identifie'] = value($_array, 'manifest|application|identifie|content');
        $aarr['version'] = value($_array, 'manifest|application|version|content');
        $aarr['versiondate'] = value($_array, 'manifest|application|versiondate|content');
        foreach ($aarr AS $key => $val) {
            if (empty($val)) {
                showmsg("系统提醒", "解析配置文件失败，请重试！"); break;
            }
        }
        $aarr['ability'] = value($_array, 'manifest|application|ability|content');
        $aarr['description'] = value($_array, 'manifest|application|description|content');
        $aarr['author'] = value($_array, 'manifest|application|author|content');
        $aarr['url'] = value($_array, 'manifest|application|url|content');
        $aarr['setting'] = array();
        $aarr['setting']['install'] = value($_array, 'manifest|install|content');
        $aarr['setting']['uninstall'] = value($_array, 'manifest|uninstall|content');
        $aarr['setting']['upgrade'] = value($_array, 'manifest|upgrade|content');
        $aarr['setting']['undeldata'] = value($_array, 'manifest|undeldata|content'); //要删除的数据表
        $aarr['setting']['undeldir'] = value($_array, 'manifest|undeldir|content'); //要删除的目录
        $aarr['setting']['undelfile'] = value($_array, 'manifest|undelfile|content'); //要删除的文件
        $aarr['setting']['undelphp'] = value($_array, 'manifest|undelphp|content'); //要删除的执行文件
        $aarr['setting']['adminurl'] = value($_array, 'manifest|adminurl|content'); //要删除的文件
        $aarr['setting']['isold'] = value($_array, 'manifest|isold|content'); //老式应用
        if (!$_GET['local']) {
            @unlink($upgradezip_source_path.DIRECTORY_SEPARATOR.'manifest.xml');
        }
        //
        $get_arr = $aarr;
        $get_arr['isold'] = $aarr['setting']['isold'];
        $get_arr['versiondate'] = $aarr['versiondate'];
        $get_arr['identifie'] = $aarr['identifie'];
        $get_arr['title'] = $aarr['title'];
        $get_arr['ability'] = $aarr['ability'];
        $get_arr['undeldata'] = $aarr['setting']['undeldata'];
        $get_arr['undelfile'] = $aarr['setting']['undelfile'];
        $get_arr['undeldir'] = $aarr['setting']['undeldir'];
        $get_arr['undelphp'] = $aarr['setting']['undelphp'];
        $get_arr['adminurl'] = $aarr['setting']['adminurl'];
        //拷贝文件开始升级
        if ($get_arr['isold']) {
            //老方式应用安装
            //拷贝文件到根目录
            $copy_from = $upgradezip_source_path.DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR;
            $copy_to = KF_ROOT_PATH;
            $copyfailnum = copydir($copy_from, $copy_to, 1); //1覆盖
            //检查文件操作权限，是否复制成功
            if($copyfailnum > 0) {
                showmsg("系统提醒", "安装文件失败，请检查目录权限！");
            }

            //拷贝模板文件
            $copy_from = $upgradezip_source_path.DIRECTORY_SEPARATOR.'template'.DIRECTORY_SEPARATOR;
            $copy_to = KF_ROOT_PATH.'templates'.DIRECTORY_SEPARATOR.$_CFG['template_dir'];
            $copyfailnum = copydir($copy_from, $copy_to, 1); //1覆盖
            //检查文件操作权限，是否复制成功
            if($copyfailnum > 0) {
                showmsg("系统提醒", "安装文件失败，请检查模板目录权限！");
            }
        }else{
            //新方式应用安装
            if (!$_GET['local']) {
                //拷贝文件到根目录
                $copy_from = $upgradezip_source_path.DIRECTORY_SEPARATOR;
                $copy_to = KF_ROOT_PATH.'kuaifan'.DIRECTORY_SEPARATOR.'addons'.DIRECTORY_SEPARATOR.$back['name'].DIRECTORY_SEPARATOR;
                $copyfailnum = copydir($copy_from, $copy_to, 1); //1覆盖
                //检查文件操作权限，是否复制成功
                if($copyfailnum > 0) {
                    showmsg("系统提醒", "安装文件失败，请检查目录权限！");
                }
            }
        }
        //执行sql 、 sql目录地址
        $sql_path = $upgradezip_source_path.DIRECTORY_SEPARATOR.'sql'.DIRECTORY_SEPARATOR;
        $file_list = glob($sql_path.'*');
        if(!empty($file_list)) {
            foreach ($file_list as $fk=>$fv) {
                if(in_array(strtolower(substr($fv, -4, 4)), array('.sql', '.php'))) {
                    if (strtolower(substr($file_list[$fk], -4, 4)) == '.sql' && $data = file_get_contents($file_list[$fk])) {
                        //获取数据库版本和编码
						$db->run($data);
                    }elseif (strtolower(substr($file_list[$fk], -4, 4)) == '.php' && file_exists($file_list[$fk])) {
                        include $file_list[$fk];
                    }
                }
            }
        }
		$install = trim($aarr['setting']['install']);
		if(in_array(strtolower(substr($install, -4, 4)), array('.sql', '.php'))) {
			$install = KF_ROOT_PATH.'kuaifan'.DIRECTORY_SEPARATOR.'addons'.DIRECTORY_SEPARATOR.$aarr['identifie'].DIRECTORY_SEPARATOR.$install;
			if (strtolower(substr($install, -4, 4)) == '.sql' && $data = file_get_contents($install)) {
				//获取数据库版本和编码
				$db->run($data);
			}elseif (strtolower(substr($install, -4, 4)) == '.php' && file_exists($install)) {
				include $install;
			}
		}elseif ($install) {
			$db->run($install);
		}

		if ($row){
            //更新到应用列表
            $_arr = array();
            $_arr['v'] = $get_arr['versiondate']; //版本
            $_arr['uptime'] = SYS_TIME; //最后更新时间
            $_arr['setting'] = string2array($row['setting']);
            if ($get_arr['identifie']) $_arr['name'] = $get_arr['identifie']; //应用名(英文)
            if ($get_arr['title']) $_arr['title'] = $get_arr['title']; //应用名(中文)
            if ($get_arr['ability']) $_arr['content'] = $get_arr['ability']; //介绍
            if ($get_arr['undeldata']) $_arr['setting']['database'] = $get_arr['undeldata']; //所包含的数据表（卸载用）
            if ($get_arr['undelfile']) $_arr['setting']['datafile'] = strinfo($get_arr['undelfile']); //所包含的文件（卸载用）
            if ($get_arr['undeldir']) $_arr['setting']['datamulu'] = strinfo($get_arr['undeldir']); //所包含的目录（卸载用）
            if ($get_arr['undelphp']) $_arr['setting']['dataphp'] = strinfo($get_arr['undelphp']); //所包含的php执行文件（卸载用）
            if ($get_arr['adminurl']) $_arr['setting']['dataurl'] = $get_arr['adminurl']; //链接菜单
            $_arr['setting']['isold'] = $get_arr['isold'];
            $_arr['setting']['local'] = $_GET['local'];
            $_arr['setting']['uplist'][$_arr['v']] = SYS_TIME; //已经升级过的版本
            $_arr['setting'] = array2string($_arr['setting']);
            $_arr['appinfo'] = array2string($get_arr);
            updatetable(table('yingyong'), $_arr, "`id`=".$row['id']);
            //
            if (!$_GET['local']) {
                //删除文件
                @unlink($upgradezip_path);
                //删除文件夹
                deletedir($upgradezip_source_path);
            }
			$links[0]['title'] = '回我的应用';
			$links[0]['href'] = $_admin_indexurl."&amp;c=yingyong";
			$links[1]['title'] = '继续查看更多应用';
			$links[1]['href'] = $_admin_indexurl."&amp;c=yingyong&amp;a=tianjia2";
			showmsg("系统提醒", "应用更新成功！", $links);
		}else{
			//添加到应用列表
			$_arr = array();
			$_arr['name'] = $get_arr['identifie']; //应用名(英文)
			$_arr['title'] = $get_arr['title']; //应用名(中文)
			$_arr['content'] = $get_arr['ability']; //介绍
			$_arr['v'] = $get_arr['versiondate']; //版本
			$_arr['intime'] = SYS_TIME; //安装时间
			$_arr['setting']['database'] = $get_arr['undeldata']; //所包含的数据表（卸载用）
			$_arr['setting']['datafile'] = strinfo($get_arr['undelfile']); //所包含的文件（卸载用）
			$_arr['setting']['datamulu'] = strinfo($get_arr['undeldir']); //所包含的目录（卸载用）
			$_arr['setting']['dataphp'] = strinfo($get_arr['undelphp']); //所包含的php执行文件（卸载用）
			$_arr['setting']['dataurl'] = $get_arr['adminurl']; //链接菜单
            $_arr['setting']['isold'] = $get_arr['isold'];
			$_arr['setting']['local'] = $_GET['local'];
			$_arr['setting']['uplist'][$_arr['v']] = SYS_TIME; //版本安装历史时间
			$_arr['setting'] = array2string($_arr['setting']);
            $_arr['appinfo'] = array2string($get_arr);
			inserttable(table('yingyong'), $_arr);
            //
            if (!$_GET['local']) {
                //删除文件
                @unlink($upgradezip_path);
                //删除文件夹
                deletedir($upgradezip_source_path);
            }
			$links[0]['title'] = '回我的应用';
			$links[0]['href'] = $_admin_indexurl."&amp;c=yingyong";
			$links[1]['title'] = '继续安装更多应用';
			$links[1]['href'] = $_admin_indexurl."&amp;c=yingyong&amp;a=tianjia2";
			showmsg("系统提醒", "应用安装成功！", $links);
		}
	}else{
        $get_path = $yuanchengurl.'?identifie='.$_GET['app'];
        $get_str = @file_get_contents($get_path);
        $getarr = array();
        if ($get_str) {
            $get_arr = json_decode($get_str, true);
            if ($get_arr['list'][0]['identifie'] == $_GET['app']) {
                $getarr = $get_arr['list'][0];
            }
        }
        //
        if ($_GET['local']) {
            if ($getarr) {
                showmsg("系统提醒",
                    "此应用已设置版权保护，您只能通过<a href='".$_admin_indexurl."&amp;c=yingyong&amp;a=info&amp;app={$_GET['app']}'>【云平台】</a>来安装。");
            }
            kf_class::run_sys_class('xta','',0);
            $xta = new XmlToArray();
            $XML = @file_get_contents($localpath.DIRECTORY_SEPARATOR.$_GET['app'].DIRECTORY_SEPARATOR.'manifest.xml');
            $xta->setXml($XML);
            $_array = $xta->parseXml();
            //
            $aarr = array();
            $aarr['code'] = value($_array, 'manifest|application|code|content');
            $aarr['title'] = value($_array, 'manifest|application|title|content');
            $aarr['identifie'] = value($_array, 'manifest|application|identifie|content');
            $aarr['version'] = value($_array, 'manifest|application|version|content');
            $aarr['versiondate'] = value($_array, 'manifest|application|versiondate|content');
            foreach ($aarr AS $key => $val) {
                if (empty($val)) {
                    showmsg("系统提醒", "错误的模块！"); break;
                }
            }
            $aarr['ability'] = value($_array, 'manifest|application|ability|content');
            $aarr['description'] = value($_array, 'manifest|application|description|content');
            $aarr['author'] = value($_array, 'manifest|application|author|content');
            $aarr['url'] = value($_array, 'manifest|application|url|content');
            $aarr['setting'] = array();
            $aarr['setting']['install'] = value($_array, 'manifest|install|content');
            $aarr['setting']['uninstall'] = value($_array, 'manifest|uninstall|content');
            $aarr['setting']['upgrade'] = value($_array, 'manifest|upgrade|content');
            $aarr['setting']['undeldata'] = value($_array, 'manifest|undeldata|content'); //要删除的数据表
            $aarr['setting']['undeldir'] = value($_array, 'manifest|undeldir|content'); //要删除的目录
            $aarr['setting']['undelfile'] = value($_array, 'manifest|undelfile|content'); //要删除的文件
            $aarr['setting']['undelphp'] = value($_array, 'manifest|undelphp|content'); //要删除的执行文件
            $aarr['setting']['adminurl'] = value($_array, 'manifest|adminurl|content'); //要删除的文件
            $aarr['setting']['isold'] = value($_array, 'manifest|isold|content'); //老式应用
            $getarr = $aarr;
        }
        if (empty($getarr)) {
            showmsg("系统提醒", "模块参数错误！");
        }
		if (!empty($row)){
            if ($row['v'] >= intval($getarr['versiondate'])) {
                $links[0]['title'] = '回我的应用';
                $links[0]['href'] = $_admin_indexurl."&amp;c=yingyong";
                $links[1]['title'] = '返回来源地址';
                $links[1]['href'] = -1;
                showmsg("系统提醒", "已经是最新版本无须更新！", $links);
            }
			//
            $links[0]['title'] = '立即升级';
            $links[0]['href'] = get_link()."&amp;dosubmit=1";
            $links[1]['title'] = '返回来源地址';
            $links[1]['href'] = -1;
            $showhtml = "已经准备就绪是否现在升级至版本：{$getarr['identifie']}({$getarr['versiondate']})？";
		}else{
			$links[0]['title'] = '确定安装';
			$links[0]['href'] = get_link()."&amp;dosubmit=1";
			$links[1]['title'] = '返回来源地址';
			$links[1]['href'] = -1;
            $showhtml = "已经准备就绪是否现在安装应用：{$getarr['identifie']}({$getarr['versiondate']})？";
		}
        if (!$_GET['local']) {
			$ip_area = kf_class::run_sys_class('ip_area');
			$ip_city = $ip_area->get($online_ip);
			if ($ip_city == "Invalid IP Address") {
				showmsg("系统提醒", "Invalid IP Address (无效的IP地址)！");
			}
			if ($ip_city == "LAN") {
				showmsg("系统提醒", "非线上网络(局域网内)不允许在线安装应用！");
			}
            $db->query("DELETE FROM ".table('yingyong_back')." WHERE `name`='{$_GET['app']}'");
            $ibarr = array();
            $ibarr['sn'] = generate_password(10);
            $ibarr['name'] = $_GET['app'];
            inserttable(table('yingyong_back'), $ibarr);
            $up_url = $yuanchengurl.'?sn='.$ibarr['sn'].'&act=install&identifie='.$_GET['app'].'&url='.urlencode(get_link('a','&').'&a=back');
            $up_url.= '&server='.$_SERVER['SERVER_NAME'];
            $up_str = @file_get_contents($up_url);
            if (!intval($up_str)) {
                showmsg("系统提醒", "拉取信息失败，可能模块不存在请稍后再试！");
            }
        }
        showmsg("系统提醒", $showhtml, $links);
	}
}

if ($_GET['a'] == 'back'){
    $row = $db->getone("select * from ".table('yingyong_back')." WHERE `sn`='{$_GET['sn']}'");
    if ($row) {
        $iarr = array();
        $iarr['name'] = $_GET['identifie'];
        $iarr['v'] = $_GET['versiondate'];
        $iarr['urlsn'] = $_GET['urlsn'];
        $iarr['indate'] = SYS_TIME;
        updatetable(table('yingyong_back'), $iarr, "`id`=".$row['id']);
        exit("1");
    }else{
        exit("0");
    }
}

if ($_GET['a'] == 'del'){
	if ($admin_val['id']!='1') showmsg("系统提醒", "只有网站创始人才可以操作！");
	$row = $db->getone("select * from ".table('yingyong')." WHERE `name`='{$_GET['app']}'");
	if (empty($row)){
		showmsg("系统提醒", "应用不存在或还没有安装！");
	}
	
	if ($_GET['dosubmit']){
		$_setting = string2array($row['setting']);
		$_appinfo = string2array($row['appinfo']);
        if ($_setting['isold'] || !isset($_setting['isold'])) {
            //删除数据库
            $_setting['database'] = explode(",", $_setting['database']);
            if (!empty($_setting['database'])){
                foreach ($_setting['database'] as $val){
                    $db->query("DROP TABLE IF EXISTS `".table($val)."`");
                }
            }
            //执行php文件
            $_setting['dataphp'] = str_replace("|", DIRECTORY_SEPARATOR, $_setting['dataphp']);
            $_setting['dataphp'] = explode(",", $_setting['dataphp']);
            foreach ($_setting['dataphp'] as $val){
                if (file_exists(KF_ROOT_PATH.$val)) @include KF_ROOT_PATH.$val;
            }
            //删除文件
            $_setting['datafile'] = str_replace("|", DIRECTORY_SEPARATOR, $_setting['datafile']);
            $_setting['datafile'] = explode(",", $_setting['datafile']);
            foreach ($_setting['datafile'] as $val){
                if (file_exists(KF_ROOT_PATH.$val)) @unlink(KF_ROOT_PATH.$val);
            }
            if ($_CFG['inwebway'] == '2'){
                kf_class::run_sys_class('pclzip','',0);
            }else{
                kf_class::run_sys_class('zip','',0);
            }
            //删除文件夹
            $_setting['datamulu'] = str_replace("|", DIRECTORY_SEPARATOR, $_setting['datamulu']);
            $_setting['datamulu'] = explode(",", $_setting['datamulu']);
            foreach ($_setting['datamulu'] as $val){
                if (file_exists(KF_ROOT_PATH.$val) && strlen(trim($val))>3) deletedir(KF_ROOT_PATH.$val);
            }
        }else{
            if ($row['name'] && !$_setting['local']) {
                if ($_CFG['inwebway'] == '2'){
                    kf_class::run_sys_class('pclzip','',0);
                }else{
                    kf_class::run_sys_class('zip','',0);
                }
                $val = 'kuaifan'.DIRECTORY_SEPARATOR.'addons'.DIRECTORY_SEPARATOR.$row['name'].DIRECTORY_SEPARATOR;
                if (file_exists(KF_ROOT_PATH.$val) && strlen(trim($val))>3) deletedir(KF_ROOT_PATH.$val);
            }

			$uninstall = trim($_appinfo['setting']['uninstall']);
			if(in_array(strtolower(substr($uninstall, -4, 4)), array('.sql', '.php'))) {
				$uninstall = KF_ROOT_PATH.'kuaifan'.DIRECTORY_SEPARATOR.'addons'.DIRECTORY_SEPARATOR.$row['name'].DIRECTORY_SEPARATOR.$uninstall;
				if (strtolower(substr($uninstall, -4, 4)) == '.sql' && $data = file_get_contents($uninstall)) {
					//获取数据库版本和编码
					$db->run($data);
				}elseif (strtolower(substr($uninstall, -4, 4)) == '.php' && file_exists($uninstall)) {
					include $uninstall;
				}
			}elseif ($uninstall) {
				$db->run($uninstall);
			}
        }
        //
        @file_get_contents($yuanchengurl.'?act=uninstall&identifie='.$row['name']);
		//删除应用信息
		$db->query("Delete from ".table('yingyong')." WHERE `id`=".$row['id']);
		//
		$links[0]['title'] = '回我的应用';
		$links[0]['href'] = $_admin_indexurl."&amp;c=yingyong";
		showmsg("系统提醒", "删除成功！", $links);
	}else{
		$links[0]['title'] = '确定删除';
		$links[0]['href'] = get_link()."&amp;dosubmit=1";
		$links[1]['title'] = '返回来源地址';
		$links[1]['href'] = -1;
		showmsg("系统提醒", "确定删除此应用和它所包含的所有数据及文件且不可恢复吗？", $links);
	}
}

/*
 ************************************************************************************************
 ************************************************************************************************
 * 函数部分
 */
function strinfo($str){
	global $_CFG;
	$str = str_replace("{t}", 'templates'.DIRECTORY_SEPARATOR.$_CFG['template_dir'], $str);
	$str = str_replace(array(DIRECTORY_SEPARATOR, "\\", "/"), "|", $str);
	return $str;
}
function appurl($str){
	global $_CFG;
	$domainurl = $_CFG['site_domain'].$_CFG['site_dir'];
  if ($_CFG['admin_url']){
    $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
    $domainurl = $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$_CFG['site_dir'];
  }
	$str = str_replace("{url}", $domainurl, $str);
	$str = str_replace("{allow}", $_GET['allow'], $str);
	$str = str_replace("{vs}", $_GET['vs'], $str);
	$str = str_replace("{sid}", $_GET['sid'], $str);
	$str = str_replace("{m}", $_GET['m'], $str);
	$str = str_replace("{c}", $_GET['c'], $str);
	$str = str_replace("{a}", $_GET['a'], $str);
	$str = str_replace("&amp;", "&", $str);
	$str = str_replace("&", "&amp;", $str);
	return $str;
}
?>