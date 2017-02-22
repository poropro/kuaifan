{#include file="common/header.tpl" title_top="1" title="网站配置"#}


	{#form set="头" notvs="1"#}


	网站名称:<br/>
	{#form set="输入框|名称:site_name{#$TIME2#}" data_value=$peizhi.site_name#}<br/>

	网站简称:<br/>
	{#form set="输入框|名称:site_namej{#$TIME2#}" data_value=$peizhi.site_namej#}<br/>
	
	首页标题:<br/>
	{#form set="输入框|名称:index_title{#$TIME2#}" data_value=$peizhi.index_title#}<br/>

	首页关键词:<br/>
	{#form set="输入框|名称:index_keywords{#$TIME2#}" data_value=$peizhi.index_keywords#}<br/>

	首页介绍词:<br/>
	{#form set="输入框|名称:index_description{#$TIME2#}" data_value=$peizhi.index_description#}<br/>

	{#*
	网站域名:(结尾不要加 &quot; / &quot;)<br/>
	{#form set="输入框|名称:site_domain{#$TIME2#}" data_value=$peizhi.site_domain#}<br/>
	*#}
	
	安装目录:(以 &quot; / &quot; 开头和结尾, 如果安装在根目录，则为&quot; / &quot;)<br/>
	{#form set="输入框|名称:site_dir{#$TIME2#}" data_value=$peizhi.site_dir#}<br/>
	升级/插件安装方式:(升级或安装失败改选)<br/>
	{#form set="列表框|名称:inwebway{#$TIME2#}" list="'方式一(默认)':1,方式二:2" default=$peizhi.inwebway#}<br/>

	强制版本:(设置用户无法切换)<br/>
	{#form set="列表框|名称:vs{#$TIME2#}" list="不强制:0,简版:1,彩版:2,触屏版:3,平板版:4,电脑版:5,'&gt;&gt;高级':99" default=$peizhi.vs#}
	<a href="{#kuaifan getlink='a'#}&amp;a=banben">高级</a>
	<br/>
	
	模板风格:(请慎重修改此项)<br/>
	{#form set="列表框|名称:template_dir{#$TIME2#}" list=$templetarr default=$peizhi.template_dir#}<br/>
	模板缓存:(秒钟/整数,填0不使用缓存)<br/>
	{#form set="输入框|名称:template_lifetime{#$TIME2#}" data_value=$peizhi.template_lifetime#}<br/>
	
	离线时间:(分钟/整数)<br/>
	{#form set="输入框|名称:lonline{#$TIME2#}" data_value=$peizhi.lonline#}<br/>
	注册方式:{#form set="列表框|名称:regtype{#$TIME2#}" list="两者都开放:0,网页在线:1,短信注册:2" default="0" data_value=$peizhi.regtype#}<br/>
	短信注册设置<a href="{#kuaifan getlink='c|a'#}&amp;c=duanxin&amp;a=peizhi">点此配置</a><br/>
	.<br/>
	充值方式:{#form set="列表框|名称:fullmoney{#$TIME2#}" list="关闭充值系统:0,开启充值系统:1" default="0" data_value=$peizhi.fullmoney#}<br/>
	充值系统设置<a href="{#kuaifan getlink='c|a'#}&amp;c=chongzhi&amp;a=zhifu">点此配置</a><br/>
	.<br/>
	暂时关站:{#form set="列表框|名称:isclose{#$TIME2#}" list="是:1,否:0" default="0" data_value=$peizhi.isclose#}<br/>
	关站原因:(结尾不要加 &quot; / &quot;)<br/>
	{#form set="文本框|名称:close_reason" notvs="1" data_value=$peizhi.close_reason#}
	{#form set="输入框|名称:close_reason{#$TIME2#}" vs="1" data_value=$peizhi.close_reason#}<br/>
	关闭会员注册:{#form set="列表框|名称:closereg{#$TIME2#}" list="是:1,否:0" default="0" data_value=$peizhi.closereg#}<br/>



	{#kuaifan vs="1" set="
	  <anchor title='提交'>[提交修改]
	  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	  <postfield name='site_name' value='$(site_name{#$TIME2#})'/>
	  <postfield name='site_namej' value='$(site_namej{#$TIME2#})'/>
	  <postfield name='index_title' value='$(index_title{#$TIME2#})'/>
	  <postfield name='index_keywords' value='$(index_keywords{#$TIME2#})'/>
	  <postfield name='index_description' value='$(index_description{#$TIME2#})'/>
	  <postfield name='site_domain' value='$(site_domain{#$TIME2#})'/>
	  <postfield name='site_dir' value='$(site_dir{#$TIME2#})'/>
	  <postfield name='inwebway' value='$(inwebway{#$TIME2#})'/>
	  <postfield name='vs' value='$(vs{#$TIME2#})'/>
	  <postfield name='template_dir' value='$(template_dir{#$TIME2#})'/>
	  <postfield name='template_lifetime' value='$(template_lifetime{#$TIME2#})'/>
	  <postfield name='lonline' value='$(lonline{#$TIME2#})'/>
	  <postfield name='regtype' value='$(regtype{#$TIME2#})'/>
	  <postfield name='smsregtxt' value='$(smsregtxt{#$TIME2#})'/>
	  <postfield name='fullmoney' value='$(fullmoney{#$TIME2#})'/>
	  <postfield name='fullmerid' value='$(fullmerid{#$TIME2#})'/>
	  <postfield name='fullkeyvalue' value='$(fullkeyvalue{#$TIME2#})'/>
	  <postfield name='isclose' value='$(isclose{#$TIME2#})'/>
	  <postfield name='close_reason' value='$(close_reason{#$TIME2#})'/>
	  <postfield name='closereg' value='$(closereg{#$TIME2#})'/>
	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	"#}
	
	{#form set="按钮|名称:dosubmit,值:提交修改" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/>
	

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
