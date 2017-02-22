{#include file="common/header.tpl" title_top="1" title="Ucenter配置"#}

	<a href="{#kuaifan getlinks='vs'#}&amp;m=explain&amp;l=ucenter&amp;a=peizhi&amp;go_url={#urlencode(get_link('','&'))#}">Ucenter配置教程</a> 
	<br/>-------------<br/>

	{#form set="头" notvs="1"#}

	是否启用:{#form set="列表框|名称:'UC_USE{#$TIME2#}'" list="是:1,否:0" default="0" data_value=$smarty.const.UC_USE#}<br/>
	.<br/>
	api 地址:<br/>
	{#form set="输入框|名称:'UC_API{#$TIME2#}'" data_value=$smarty.const.UC_API#}<br/>

	api IP:<br/>
	{#form set="输入框|名称:'UC_IP{#$TIME2#}'" data_value=$smarty.const.UC_IP#}<br/>
	
	数据库主机名:<br/>
	{#form set="输入框|名称:'UC_DBHOST{#$TIME2#}'" data_value=$smarty.const.UC_DBHOST#}<br/>
	
	数据库用户名:<br/>
	{#form set="输入框|名称:'UC_DBUSER{#$TIME2#}'" data_value=$smarty.const.UC_DBUSER#}<br/>
	
	数据库密码:<br/>
	{#form set="输入框|名称:'UC_DBPW{#$TIME2#}'" data_value=$smarty.const.UC_DBPW#}<br/>
	
	数据库名:<br/>
	{#form set="输入框|名称:'UC_DBNAME{#$TIME2#}'" data_value=$smarty.const.UC_DBNAME#}<br/>
	
	数据库表前缀:<br/>
	{#form set="输入框|名称:'UC_DBTABLEPRE{#$TIME2#}'" data_value=$smarty.const.UC_DBTABLEPRE#}<br/>
	
	数据库字符集:<br/>
	{#form set="输入框|名称:'UC_DBCHARSET{#$TIME2#}'" data_value=$smarty.const.UC_DBCHARSET#}<br/>
	
	应用id(APP ID):<br/>
	{#form set="输入框|名称:'UC_APPID{#$TIME2#}'" data_value=$smarty.const.UC_APPID#}<br/>
	
	通信密钥:<br/>
	{#form set="输入框|名称:'UC_KEY{#$TIME2#}'" data_value=$smarty.const.UC_KEY#}<br/>
	
	发送信息:<br/>
	{#form set="列表框|名称:'UC_MSG{#$TIME2#}'" list="是:1,否:0" default="0" data_value=$smarty.const.UC_MSG#}(发送短信息时同时发送到ucenter)<br/>


	{#kuaifan vs="1" set="
	  <anchor title='提交'>[提交修改]
	  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	  <postfield name='UC_USE' value='$(UC_USE{#$TIME2#})'/>
	  <postfield name='UC_API' value='$(UC_API{#$TIME2#})'/>
	  <postfield name='UC_IP' value='$(UC_IP{#$TIME2#})'/>
	  <postfield name='UC_DBHOST' value='$(UC_DBHOST{#$TIME2#})'/>
	  <postfield name='UC_DBUSER' value='$(UC_DBUSER{#$TIME2#})'/>
	  <postfield name='UC_DBPW' value='$(UC_DBPW{#$TIME2#})'/>
	  <postfield name='UC_DBNAME' value='$(UC_DBNAME{#$TIME2#})'/>
	  <postfield name='UC_DBTABLEPRE' value='$(UC_DBTABLEPRE{#$TIME2#})'/>
	  <postfield name='UC_DBCHARSET' value='$(UC_DBCHARSET{#$TIME2#})'/>
	  <postfield name='UC_APPID' value='$(UC_APPID{#$TIME2#})'/>
	  <postfield name='UC_KEY' value='$(UC_KEY{#$TIME2#})'/>
	  <postfield name='UC_MSG' value='$(UC_MSG{#$TIME2#})'/>
	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	"#}
	
	{#form set="按钮|名称:dosubmit,值:提交修改" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/>
	

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
