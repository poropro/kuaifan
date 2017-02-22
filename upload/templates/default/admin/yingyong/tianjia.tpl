{#include file="common/header.html5.tpl" title_top="1" title="安装本地应用"#}

[<a href="{#$admin_indexurl#}&amp;c=yingyong">返回</a>]<br/>
[<a href="{#$admin_indexurl#}&amp;c=yingyong&amp;a=tianjia2">查找更多应用</a>]<br/>


应用名称(版本)<br/>
----------<br/>
{#foreach from=$apparr item=list name=foo#}
	{#$smarty.foreach.foo.index+1#}.{#$list.appinfo.title#}({#$list.appinfo.identifie#})<br/>
	{#if $list.appinfo.ability#}<small>{#$list.appinfo.ability#}</small><br/>{#/if#}
	<a href="{#$admin_indexurl#}&amp;c=yingyong&amp;a=info&amp;local=1&amp;app={#$list.appinfo.identifie#}">{#if $list.isup#}<span style="color:#ff0000">更新({#$list.oldv#}_{#$list.appinfo.versiondate#})</span>{#else#}安装此应用{#/if#}</a><br/>
	{#if !$smarty.foreach.foo.last#}
		----------<br/>
	{#/if#}
{#foreachelse#}
	没有任何可安装的插件。<br/>
{#/foreach#}


{#include file="admin/footer.html.tpl"#}
{#include file="common/footer.html.tpl"#}
