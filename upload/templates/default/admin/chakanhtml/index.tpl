{#include file="common/header.tpl" title_top="1" title="查看源码"#}


	{#if $html#}
		<a href="{#kuaifan getlink='dosubmit'#}">重新查询</a><br/>
		{#if $smarty.post.html_view=='0' || $smarty.post.html_view=='2'#}
			{#form set="输入框|名称:test_{#$TIME2#}" data_value=$html vs="1"#}
			{#form set="文本框|名称:test_{#$TIME2#},行数:10" data_value=$html notvs="1"#}<br/>
		{#/if#}
		{#if $smarty.post.html_view=='1' || $smarty.post.html_view=='2'#}
			{#$html|htmlspecialchars|nl2br#}<br/>
		{#/if#}
	{#else#}
		{#form set="头" notvs="1"#}
		
		地址:{#form set="输入框|名称:html_url{#$TIME2#}" data_value="http://"#}<br/>
		
		方式:{#form set="列表框|名称:html_view{#$TIME2#}" list="显示在输入框:0,显示在页面:1,两者都显示:2" default="0"#}<br/>
		带文件头:{#form set="列表框|名称:html_head{#$TIME2#}" list="是:0,否:1" default="0"#}<br/>

		{#kuaifan vs="1" set="
		  <anchor title='提交'>[提交查看]
		  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
		  <postfield name='html_url' value='$(html_url{#$TIME2#})'/>
		  <postfield name='html_view' value='$(html_view{#$TIME2#})'/>
		  <postfield name='html_head' value='$(html_head{#$TIME2#})'/>
		  <postfield name='dosubmit' value='1'/>
		  </go> </anchor>
		"#}
		
		{#form set="按钮|名称:dosubmit,值:提交查看" notvs="1"#}
		{#form set="尾" notvs="1"#}
		<br/>
		注：学习wml好办法！多看多查！
		<br/>
	{#/if#}
{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
