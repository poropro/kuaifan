{#include file="common/header.tpl" title_top="1" title="字段管理"#}

[字段管理-{#$field.title#}]<br/>
<a href="{#kuaifan getlink='fadd'#}&amp;fadd=1">添加字段</a>|<a href="{#kuaifan getlink='field'#}">返回</a><br/>
-------------<br/>
排序|别名|字段名(类型)<br/>
{#foreach from=$ziduan item=list#}
	{#$list.listorder#}.{#$list.name#}|{#$list.field#}({#$list.type#})<br/>
	<a href="{#kuaifan getlink='fedit'#}&amp;fedit={#$list.id#}">修改</a> | 
	{#if $list.status=='2'#}
		禁用
	{#elseif $list.status=='1'#}
		<a href="{#kuaifan getlink='fstart'#}&amp;fstart={#$list.id#}"><b>启用</b></a>
	{#else#}
		<a href="{#kuaifan getlink='fend'#}&amp;fend={#$list.id#}">禁用</a>
	{#/if#} | 
	{#if $list.del=='1'#}
	删除
	{#else#}
	<a href="{#kuaifan getlink='fdel'#}&amp;fdel={#$list.id#}">删除</a>
	{#/if#}<br/><br/>
{#foreachelse#}
	没有任何字段,<a href="{#kuaifan getlink='fadd'#}&amp;fadd=1">点击添加</a><br/>
{#/foreach#}
[<a href="{#kuaifan getlink='paixu'#}&amp;paixu=1">高级排序</a>]<br/>


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
