{#include file="common/header.tpl" title_top="1" title="会员分组"#}

[会员分组管理]<br/>
<a href="{#kuaifan getlink='add'#}&amp;add=1">添加分组</a><br/>
-------------<br/>
排序.组名|系统组<br/>
{#foreach from=$fenzu item=list#}
	{#$list.sort#}.<a href="{#kuaifan getlink='edit'#}&amp;edit={#$list.groupid#}">{#$list.name#}|{#if $list.issystem#}是{#else#}否{#/if#}</a><br/>
{#foreachelse#}
	没有任何分组,<a href="{#kuaifan getlink='add'#}&amp;add=1">点击添加</a><br/>
{#/foreach#}


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
