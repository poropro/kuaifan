{#include file="common/header.tpl" title_top="1" title="模型管理"#}

[会员模型管理]<br/>
<a href="{#kuaifan getlink='add'#}&amp;add=1">添加模型</a><br/>
-------------<br/>
ID.名称|数据表<br/>
{#foreach from=$moxing item=list#}
	{#$list.id#}.{#$list.title#}|{#$list.tablename#}<br/>
	<a href="{#kuaifan getlink='field'#}&amp;field={#$list.id#}">字段管理</a> | 
	<a href="{#kuaifan getlink='edit'#}&amp;edit={#$list.id#}">修改</a> | 
	<a href="{#kuaifan getlink='del'#}&amp;del={#$list.id#}">删除</a><br/>
{#foreachelse#}
	没有任何模型,<a href="{#kuaifan getlink='add'#}&amp;add=1">点击添加</a><br/>
{#/foreach#}


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
