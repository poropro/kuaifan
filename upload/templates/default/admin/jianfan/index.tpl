{#include file="common/header.tpl" title_top="1" title="简繁互换"#}


	{#form set="头" notvs="1"#}

	输入要转换的字符:<br/>
	{#form set="输入框|名称:text_{#$TIME2#}" data_value=$smarty.post.text vs="1"#}
	{#form set="文本框|名称:text_{#$TIME2#}" data_value=$smarty.post.text notvs="1"#}<br/>

	转换方式:{#form set="列表框|名称:type_{#$TIME2#}" list="'简->繁':0,'繁->简':1" default="0"#}<br/>
	显示方式:{#form set="列表框|名称:view_{#$TIME2#}" list="显示在输入框:0,显示在页面:1,两者都显示:2" default="0"#}<br/>


	{#kuaifan vs="1" set="
	  <anchor title='提交'>[提交查看]
	  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	  <postfield name='text' value='$(text_{#$TIME2#})'/>
	  <postfield name='view' value='$(view_{#$TIME2#})'/>
	  <postfield name='type' value='$(type_{#$TIME2#})'/>
	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	"#}
	
	{#form set="按钮|名称:dosubmit,值:提交查看" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/>
	{#if $text#}-------------<br/>
		{#if $smarty.post.view=='0' || $smarty.post.view=='2'#}
			{#form set="输入框|名称:test_{#$TIME2#}" data_value=$text vs="1"#}
			{#form set="文本框|名称:test_{#$TIME2#},行数:10" data_value=$text notvs="1"#}<br/>
		{#/if#}
		{#if $smarty.post.view=='1' || $smarty.post.view=='2'#}
			{#$text|nl2br#}<br/>
		{#/if#}
	{#/if#}

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
