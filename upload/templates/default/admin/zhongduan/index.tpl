{#include file="common/header.tpl" title_top="1" title="系统信息"#}


	程序系统:{#$system_info.version#} ({#$system_info.release#})<br/>
	<a href="{#$admin_indexurl#}&amp;c=shengji">检查新版本</a><br/>
	-----<br/>
	操作系统:{#$system_info.os#}<br/>
	-----<br/>
	PHP 版本:{#$system_info.php_ver#}<br/>
	-----<br/>
	服务器软件:{#$system_info.web_server#}<br/>
	-----<br/>
	MySQL 版本:{#$system_info.mysql_ver#}<br/>
	-----<br/>
	上传文件限制:{#$system_info.max_filesize#}<br/>


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
