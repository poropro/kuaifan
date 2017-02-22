<?php
if(!defined('IN_KUAIFAN')) exit('Access Denied!');


$GLOBALS['__db'] = $db;
$GLOBALS['__smarty'] = $smarty;
include KF_ROOT_PATH.'kuaifan/addons/'.$_GET['m'].'/site.php';
$classname = "KF_".ucfirst($_GET['m']);
if (!class_exists($classname)) {
    showmsg("系统提醒", "类方法 “{$classname}”不存在！");
}else{
    $kf_site = new $classname();
    if (isset($_GET['allow'])) {
        KC::admin();
        $kf_method = 'doAdmin'.ucfirst($_get_c_val);
    }else{
        $kf_method = 'doWww'.ucfirst($_get_c_val);
    }
    if (!method_exists($kf_site, $kf_method)) {
		if(!defined('ADDONS_NOEXIT')) {
			showmsg("系统提醒", "函数方法 “{$classname}::{$kf_method}”不存在！");
		}
    }else{
		$kf_site->$kf_method($_GET['a']?$_GET['a']:null);
		if(!defined('ADDONS_NOEXIT')) {
			exit();
		}
    }
}


/**
 * Class KC
 */
class KC {
    public $sid,$module,$function;

    function __construct()
    {
        global $_get_c_val;
        $this->sid = SID;
        $this->module = $_GET['m'];
        $this->function = $_get_c_val;
    }

    public function db()
    {
        if (!isset($GLOBALS['__db'])) {
            showmsg("系统提醒", "数据库方法不存在，请联系模块开发商！");
        }
        return $GLOBALS['__db'];
    }

    public function smarty()
    {
        if (!isset($GLOBALS['__smarty'])) {
            showmsg("系统提醒", "机制方法不存在，请联系模块开发商！");
        }
        return $GLOBALS['__smarty'];
    }

    public function admin()
    {
        $admin_wheresql = " WHERE allow ='{$_REQUEST['allow']}'";
        $admin_val = self::db()->getone("select * from ".table('guanliyuan').$admin_wheresql." LIMIT 1");
        if (empty($admin_val)){
            $url = get_link("vs","",1);
            $links[0]['title'] = '登录后台';
            $links[0]['href'] = $url."&amp;m=admin&amp;c=login";
            $links[1]['title'] = '返回网站首页';
            $links[1]['href'] = $url."&amp;m=index&amp;sid={$_GET['sid']}";
            showmsg("系统提醒", "请先登录后台！", $links, $links[0]['href']);
            //header("Location:".get_link("c,allow","&")."&c=login"); exit;
        }
        self::smarty()->assign('admin_val', $admin_val);
    }

    public function user()
    {
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
    }

    public function message($array = array())
    {
        if (isset($_POST['isajax'])) {
            echo json_encode($array);
        }else{
            showmsg("友情提示", $array['message']);
        }
        exit();
    }

    public function v($array = array(), $_template = '')
    {
        if (is_array($array) && $array) {
            foreach($array AS $vk => $vv) {
                $GLOBALS['__smarty']->assign($vk, $vv);
            }
        }
        $_template = $_template?$_template:$this->function;
        $_now_path = WEB_PATH.'kuaifan/addons/'.$this->module.'/';
        self::smarty()->assign('MOD_PATH', $_now_path);
        $_now_path.= 'templates/';
        self::smarty()->assign('TEM_PATH', $_now_path);
        if (isset($_GET['allow'])) {
            $_template = "admin/".$_template;
            $_now_path.= "admin/";
        }
        self::smarty()->assign('_GET', $_GET);
        self::smarty()->assign('_POST', $_POST);
        self::smarty()->assign('_COOKIE', $_COOKIE);
        self::smarty()->assign('_REQUEST', $_REQUEST);
        self::smarty()->assign('NOW_PATH', $_now_path);
        self::smarty()->assign('this', $this);
        self::smarty()->compile_dir.= $this->module.'/';
        self::smarty()->compile_dir.= ($this->function)?substr($this->function,0,1).'/':'';
        self::smarty()->display(get_tpl_addons($this->module, $_template), __smarty_display());
        if (isset($smarty)) unset($smarty);
    }

    public function adminurl($param = '', $allurl = false, $amp = '&amp;')
    {
        $get = array(
            'm'=>$_GET['m'],
            'allow'=>$_GET['allow'],
            'vs'=>$_GET['vs']
        );
        $_param = $param?$param:$this->function;
        if (is_array($_param)) {
            foreach($_param AS $k=>$v) {
                $get[$k] = $v;
            }
        }else{
            $get['c'] = $_param;
        }
        $url = '';
        foreach($get as $k=>$v){
            $url.= "{$k}={$v}{$amp}";
        }
        $url = $url?"?".substr($url,0,-(strlen($amp))):'?index='.generate_password(5);
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        if ($allurl){
            return $_SERVER['PHP_SELF'].$url;
        }else{
            return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$_SERVER['PHP_SELF'].$url;
        }
    }

    public function wwwurl($param = '', $allurl = false, $amp = '&amp;')
    {
        $get = array(
            'm'=>$_GET['m'],
            'sid'=>$_GET['sid'],
            'vs'=>$_GET['vs']
        );
        $_param = $param?$param:$this->function;
        if (is_array($_param)) {
            foreach($_param AS $k=>$v) {
                $get[$k] = $v;
            }
        }else{
            $get['c'] = $_param;
        }
        $url ='';
        foreach($get as $k=>$v){
            $url.="{$k}={$v}{$amp}";
        }
        $url = $url?"?".substr($url,0,-(strlen($amp))):'?index='.generate_password(5);
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        if ($allurl){
            return $_SERVER['PHP_SELF'].$url;
        }else{
            return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$_SERVER['PHP_SELF'].$url;
        }
    }
}
?>