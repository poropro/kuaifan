{#include file="common/header.tpl" title_top="1" title="勋章分类"#}

[勋章分类]<br/>
<a href="{#$URL#}&amp;z=addfl">添加分类</a>|<a href="{#$URL#}&amp;z=index">返回</a><br/>
-------------<br/>
分类名称<br/>
{#kuaifan_pc set="列表名:lists,显示数目:10,分页显示:1,分页名:pagelist,分页变量名:page,数据表:huiyuan_xunzhang,排序:intime DESC" where="type='fenlei'"#}
{#foreach from=$lists item=list#}
	<a href="{#$URL#}&amp;z=addfl&amp;id={#$list.id#}">{#$list.title#}</a><br/>
{#foreachelse#}
	没有任何分类,<a href="{#$URL#}&amp;z=addfl">点击添加</a><br/>
{#/foreach#}

{#$pagelist#}<br/>


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
