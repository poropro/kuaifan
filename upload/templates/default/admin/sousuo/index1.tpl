{#include file="common/header.tpl" title_top="1" title="全站搜索"#}


[全站搜索]<br/>
<a href="{#get_link('a')#}&amp;a=tianjia">添加搜索</a>|<a href="{#get_link('a')#}&amp;a=suoyin">重建索引</a><br/>
-------------<br/>
排序(ID).类别名称/所属模块/所属模型<br/>
{#foreach from=$listarr item=list#}
	{#$list.listorder#}({#$list.id#}).<a href="{#get_link('a|id')#}&amp;a=xiugai&amp;id={#$list.id#}">{#$list.name#}/{#$list.module_cn#}/{#$list.modelid_cn#}</a><br/>
{#foreachelse#}
	没有任何搜索分类。<br/>
{#/foreach#}



{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
