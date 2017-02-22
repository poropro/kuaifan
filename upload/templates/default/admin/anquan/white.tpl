{#include file="common/header.tpl" title_top="1" title="防两次刷新间隔白名单"#}


[刷新白名单]<br/>
<a href="{#get_link('a')#}">返回安全配置</a><br/>
-------------<br/>

{#foreach from=$white item=list key=kkey#}
[<a href="{#kuaifan getlink='a'#}&amp;a=white&amp;delkey={#$kkey#}">删除</a>]<a href="{#$list#}">{#$list#}</a><br/>
{#foreachelse#}
没有添加任何白名单。<br/>
{#/foreach#}


	{#form set="头" notvs="1"#}

	添加白名单地址:{#form set="输入框|名称:addwhite{#$TIME2#}"#}

	{#kuaifan vs="1" set="
	  <anchor title='提交'>[增加]
	  <go href='{#get_link()#}' method='post' accept-charset='utf-8'>
	  <postfield name='addwhite' value='$(addwhite{#$TIME2#})'/>

	  <postfield name='dosubmit' value='1'/>
	  </go> </anchor>
	"#}
	
	{#form set="按钮|名称:dosubmit,值:增加" notvs="1"#}
	{#form set="尾" notvs="1"#}
	<br/>
    <u>*请填写绝对地址, 如:http://kuaifan.net/index.php?m=smscat&amp;c=img</u>
	<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
