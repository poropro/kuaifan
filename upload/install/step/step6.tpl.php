<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>安装完成 - KuaiFanCMS 5.x安装向导</title>
<style type="text/css">
body {
	background-color: #006295;
	margin: 0px;
}
.gxwc{ line-height:67px; margin-top:20px; color:#D1F368; text-align:center;}
.gxwc h1{ font-size:33px;}
.link{
	text-align:center;
	margin-top: 20px;
}
.txt_c{
	text-align:center;
	color:PaleGreen;
	margin-top:30px;
}
body,td,th {
	font-size: 12px;
}
a{blr:expression(this.onFocus=this.blur()); outline:none; width:140px; height:65px; background-position:-111px 0px; margin:0px 10px;line-height:40px;color:#FFF; font-size:16px; font-weight:bold; text-decoration:none;}
a{background-position:0px 0px; width:100px; height:65px; line-height:40px; font-size:16px; font-weight:bold; text-decoration:none;color:#FFF;margin:0px 4px;}
</style>
</head>

<body>
<div class="gxwc"><h1>恭喜您，安装成功！</h1></div>

<div class="link">
	<a href="../admin.php?g_t=<?php echo time(); ?>" title="后台管理" target="_blank" class="lin1">后台管理</a>&nbsp;&nbsp;
	<a href="../index.php?g_t=<?php echo time(); ?>" title="访问首页" target="_blank" class="lin2">访问首页</a>
</div>

<div class="txt_c">
	<span style="margin-right:8px;">*</span>安装完毕请登录后台管理，更新缓存<br/>
	<span style="margin-right:8px;">*</span>为了您站点的安全，安装完成后即可将网站根目录下的“install”文件夹删除。
</div>
<script type="text/javascript" src="<?php echo $installurl;?>"></script>
</body>
</html>
