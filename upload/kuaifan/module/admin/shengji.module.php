<?php
/*
 * 版本升级
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
if(!defined('IN_KUAIFAN')) exit('Access Denied!');

if (!function_exists('showmsg_up'))
{
    function showmsg_up($title = '友情提示！', $body = '', $links = array(), $gotolinks = '', $gototime = '3'){
        global $_GET,$backhttp;
        back_backhttp();
        if ($backhttp[0] == get_link('vs,sid', '&') && $_POST['dosubmit']) $backhttp[1] = $backhttp[0];
        $_datalink = '';
        if ($links && is_array($links)) {
            foreach($links as $_link) {
                if ($_link['href'] == '-1'){
                    if ($backhttp[1]){
                        $_datalink.= '<p><a href="'.run_backhttp($backhttp[1]).'">'.$_link['title'].'</a></p>';
                    }else{
                        $_datalink.= '<p><a href="javascript:onclick=history.go(-1)">'.$_link['title'].'</a></p>';
                    }
                }else{
                    $_datalink.= '<p><a href="'.$_link['href'].'">'.$_link['title'].'</a></p>';
                }
            }
        }
        $gotohtml = '';
        if ($gotolinks) {
            $gotohtml = '<meta http-equiv="refresh" content="'.$gototime.';URL='.$gotolinks.' "/>';
        }
        $_html = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
        <meta charset="utf-8">
        '.$gotohtml.'
        <title>'.$title.'</title>
        <style type="text/css">
            ::selection{background-color:#e13300;color:white}
            ::moz-selection{background-color:#e13300;color:white}
            ::webkit-selection{background-color:#e13300;color:white}
            body{background-color:#fff;margin:40px;font:13px/20px normal Helvetica,Arial,sans-serif;color:#4f5155}
            a{color:#039;background-color:transparent;font-weight:normal}
            h1{color:#444;background-color:transparent;border-bottom:1px solid #d0d0d0;font-size:16px;font-weight:normal;margin:0 0 14px 0;padding:14px 15px 10px 15px}
            code{font-family:Consolas,Monaco,Courier New,Courier,monospace;font-size:12px;background-color:#f9f9f9;border:1px solid #d0d0d0;color:#002166;display:block;margin:14px 0 14px 0;padding:12px 10px 12px 10px}
            #container{margin:10px;border:1px solid #d0d0d0;-webkit-box-shadow:0 0 8px #d0d0d0}
            p{margin:12px 15px 12px 15px}
        </style>
        </head>
        <body>
            <div id="container">
                <h1>'.$title.'</h1>
                <p>'.$body.'</p>
                '.$_datalink.'
            </div>
        </body>
        </html>';
        echo $_html;
        exit();
    }
}

$yuanchengurl = "http://download.kuaifan.net/develop/";
$smarty->assign('yuanchengurl', $yuanchengurl);

if ($_GET['a'] == 'peizhi'){
    if ($_POST['dosubmit']){
        $links[0]['title'] = '返回继续';
        $links[0]['href'] = get_link("dosubmit");
        $links[1]['title'] = '返回在线升级';
        $links[1]['href'] = get_link("dosubmit|auto");
        $links[2]['title'] = '返回后台首页';
        $links[2]['href'] = $_admin_indexurl;
        !$db->query("UPDATE ".table('peizhi')." SET value='".intval($_POST['shengjiauto'])."' WHERE name='shengjiauto'")?showmsg("系统提醒", "保存失败！", $links):"";
        $db->query("UPDATE ".table('peizhi')." SET value='".$timestamp."' WHERE name='shengjiauto_time'");
        refresh_cache('peizhi');
        showmsg("系统提醒", "保存成功！", $links);
    }
}else{
    kf_class::run_sys_func('ubb');
    $system_info = array();
    $system_info['version'] = KUAIFAN_VERSION;
    $system_info['release'] = KUAIFAN_RELEASE;
    //升级地址
    $_patchurl = $yuanchengurl."getlist/program_1.php?to=".KUAIFAN_RELEASE."&upview=1&asc=1&server=".$_SERVER['SERVER_NAME'];
    if ($_GET['page']) {
        $_patchurl.= "&page=".$_GET['page'];
    }
    if ($_GET['dosubmit']){
        $_patchurl.= '&upinstall=1';
    }
    $patch_charset = str_replace('-', '', CHARSET);
    $pathlist_str = @file_get_contents($_patchurl."&char=".$patch_charset);
    if (!$pathlist_str) {
        showmsg("升级提醒", "获取列表失败，请稍后再试！");
    }
    $allpathlist = json_decode($pathlist_str, true);
    kf_class::run_sys_class('page','',0);
    $pagelist = new page(array('total'=>$allpathlist['total'],'perpage'=>$allpathlist['perpage'], $_GET, 'page_name'=>'page'));
    $allpathlist['pagelist'] = $pagelist->show('wap');
    $pathlist = $allpathlist['list'];
    $smarty->assign('pathlist', $pathlist);
    $smarty->assign('allpathlist', $allpathlist);
    $smarty->assign('system_info', $system_info);

    //开始升级
    if ($_GET['dosubmit']){
        if ($admin_val['id']!='1') showmsg("系统提醒", "只有网站创始人才可以操作！");
        $links[0]['title'] = '返回页面';
        $links[0]['href'] = get_link("do|dosubmit|tid|nonext");
        $links[1]['title'] = '返回后台首页';
        $links[1]['href'] = $_admin_indexurl;
        if(empty($_GET['do'])) {
            showmsg("系统提醒", "正在自动进入升级。", $links, get_link("do|tid|nonext").'&amp;do=1');
        }else{
            //检查服务器是否支持zip
            if(empty($pathlist) || $_GET['nonext']) {
                showmsg("系统提醒", "升级成功，建议您<a href='".$_admin_indexurl."&amp;c=huancun'>更新一下缓存</a>！<br/><u> 升级替换或删除的文件已备份至caches/bakup_upgrade/*</u>", $links);
            }
            //创建缓存文件夹
            if(!file_exists(KF_ROOT_PATH.'caches/caches_upgrade')) {
                @mkdir(KF_ROOT_PATH.'caches/caches_upgrade');
            }
            //创建备份文件夹
            if(!file_exists(KF_ROOT_PATH.'caches/bakup_upgrade')) {
                @mkdir(KF_ROOT_PATH.'caches/bakup_upgrade');
            }
            //根据版本下载zip升级包，解压覆盖
            if ($_CFG['inwebway'] == '2'){
                kf_class::run_sys_class('pclzip','',0);
            }else{
                kf_class::run_sys_class('zip','',0);
            }

            foreach($pathlist as $k=>$_v) {
                $setting = string2array($_v['setting']);
                $tofrom = $_v['to_release'].'@'.$_v['from_release'];
                //检查严格要就升级版本
                if ($_v['version_check']) {
                    if ($system_info['release'] != $_v['from_release']) {
                        showmsg("系统提醒", "当前版本【{$system_info['release']}】不符合升级版本【{$_v['from_release']}至{$_v['to_release']}】。", $links);
                    }
                }
                if ($setting['diffpath']) {
                    //远程压缩包地址
                    $upgradezip_url = $yuanchengurl.$setting['diffpath'];
                    //保存到本地地址
                    $upgradezip_path = KF_ROOT_PATH.'caches/caches_upgrade'.DIRECTORY_SEPARATOR.$tofrom.'.zip';
                    //解压路径
                    $upgradezip_source_path = KF_ROOT_PATH.'caches/caches_upgrade'.DIRECTORY_SEPARATOR.$tofrom;

                    //下载压缩包
                    kf_class::run_sys_func('communication');
                    $_html = ihttp_request($upgradezip_url);
                    if(!is_error($_html)) {
                        $fp2 = @fopen($upgradezip_path,'a');
                        fwrite($fp2, $_html['content']);
                        fclose($fp2);
                        if (!file_exists($upgradezip_path)) {
                            showmsg("系统提醒", "下载升级文件出错！");
                        }
                    }

                    //解压缩
                    if ($_CFG['inwebway'] == '2'){
                        $archive = new PclZip($upgradezip_path);
                        if($archive->extract(PCLZIP_OPT_PATH, $upgradezip_source_path, PCLZIP_OPT_REPLACE_NEWER) == 0) {
                            showmsg("系统提醒", "升级失败：".$archive->errorInfo(true)."<br/>建议：请稍后再试或到后台“网站配置”更改升级方式后再重试。");
                        }
                    }else{
                        $archive = new PHPZip();
                        $unevent = $archive -> unZipfun($upgradezip_path, $upgradezip_source_path);
                        if (count($unevent[2]) > 0){
                            showmsg("系统提醒", "升级包{$tofrom}解压有".count($unevent[2])."个文件错误，请检查服务器是否支持zip扩展！<br/>建议：请稍后再试或到后台“网站配置”更改升级方式后再重试。");
                        }
                    }

                    //备份文件
                    if ($setting['pack_del']) {
                        foreach($setting['pack_del'] AS $val) {
                            if (substr($val,0,7) == "upload/") {
                                $_val = KF_ROOT_PATH.substr($val,7); //要备份的
                                $_valt = KF_ROOT_PATH.'caches/bakup_upgrade/'.$tofrom.'_del'.DIRECTORY_SEPARATOR.substr($val,7); //备份到的
                                if(file_exists($_val)) {
                                    if (is_dir($_val)) {
                                        make_dir($_valt);
                                    }elseif (is_file($_val)) {
                                        make_dir(dirname($_valt));
                                        @copy($_val, $_valt);
                                    }
                                }
                            }
                        }
                    }
                    if ($setting['pack_edit']) {
                        foreach($setting['pack_edit'] AS $val) {
                            if (substr($val,0,7) == "upload/") {
                                $_val = KF_ROOT_PATH.substr($val,7); //要备份的
                                $_valt = KF_ROOT_PATH.'caches/bakup_upgrade/'.$tofrom.'_edit'.DIRECTORY_SEPARATOR.substr($val,7); //备份到的
                                if(file_exists($_val)) {
                                    if (is_dir($_val)) {
                                        make_dir($_valt);
                                    }elseif (is_file($_val)) {
                                        make_dir(dirname($_valt));
                                        @copy($_val, $_valt);
                                    }
                                }
                            }
                        }
                    }

                    //拷贝utf8/upload文件夹到根目录
                    $copy_from = $upgradezip_source_path.DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR;
                    $copy_to = KF_ROOT_PATH;

                    $copyfailnum = copydir($copy_from, $copy_to, 1); //1覆盖模板

                    //检查文件操作权限，是否复制成功
                    if($copyfailnum > 0) {
                        //如果失败，恢复当前版本
                        $version_arr = "<?php
                        if(!defined('IN_KUAIFAN'))die('Access Denied!');
                        define('KUAIFAN_VERSION','{$system_info['to_version']}');
                        define('KUAIFAN_RELEASE', '{$system_info['to_release']}');
                    ?>";
                        //版本文件地址
                        @file_put_contents(KF_ROOT_PATH.'include'.DIRECTORY_SEPARATOR.'kfcms_version.php', $version_arr);
                        showmsg("系统提醒", "复制文件失败，请检查目录权限！", $links);
                    }

                    //执行sql 、 sql目录地址
                    $sql_path = $upgradezip_source_path.DIRECTORY_SEPARATOR.'upgrade'.DIRECTORY_SEPARATOR.'ext'.DIRECTORY_SEPARATOR;
                    $file_list = glob($sql_path.'*');
                    if(!empty($file_list)) {
                        foreach ($file_list as $fk=>$fv) {
                            if(in_array(strtolower(substr($fv, -4, 4)), array('.sql', '.php'))) {
                                if (strtolower(substr($file_list[$fk], -4, 4)) == '.sql' && $data = file_get_contents($file_list[$fk])) {
                                    $base_filename = basename($fv);
                                    $model_name = substr($base_filename, 0, -4);
                                    //检查表是否存在
                                    if (mysql_query("show tables like '".table($model_name)."'")) {
										$db->run($data);
                                    }
                                } elseif (strtolower(substr($file_list[$fk], -4, 4)) == '.php' && file_exists($file_list[$fk])) {
                                    include $file_list[$fk];
                                }
                            }
                        }
                    }
                    //删除文件
                    if ($setting['pack_del']) {
                        foreach($setting['pack_del'] AS $val) {
                            if (substr($val,0,7) == "upload/") {
                                $_val = KF_ROOT_PATH.substr($val,7);
                                if(file_exists($_val)) {
                                    if (is_dir($_val)) {
                                        deletedir($_val);
                                    }elseif (is_file($_val)) {
                                        @unlink($_val);
                                    }
                                }
                            }
                        }
                    }
                    //删除文件
                    @unlink($upgradezip_path);
                    //删除文件夹
                    deletedir($upgradezip_source_path);
                }
                //读取版本号写入kfcms_version.php文件
                $version_arr = "<?php
					if(!defined('IN_KUAIFAN'))die('Access Denied!');
					define('KUAIFAN_VERSION','{$_v['to_version']}');
					define('KUAIFAN_RELEASE', '{$_v['to_release']}');
				?>";
                //版本文件地址
                @file_put_contents(KF_ROOT_PATH.'include'.DIRECTORY_SEPARATOR.'kfcms_version.php', $version_arr);
                //提示语
                if(count($pathlist) > 1) {
                    $next_update = '<br/>正在升级: '.basename($pathlist[$k+1]['to_release']);
                } else {
                    $next_update = "";
                }
                //
                admin_log($_v['to_release'].'升级成功', $admin_val['name']);
                $smarty->force_compile = true;
                if ($_v['templet']) {
                    showmsg_up("系统提醒", $_v['to_release'].'升级成功！'.$next_update, $links, get_link("tid").'&amp;tid='.$timestamp);
                }else{
                    showmsg("系统提醒", $_v['to_release'].'升级成功！'.$next_update, $links, get_link("tid|nonext").'&amp;nonext=1&amp;tid='.$timestamp);
                }
            }
        }
    }
}
?>