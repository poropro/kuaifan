{#include file="common/header.tpl" title_top="1" title="颁发管理"#}

[颁发管理]<br/>
<a href="{#$URL#}&amp;z=addhy">颁发勋章</a>|<a href="{#$URL#}">返回</a><br/>
-------------<br/>
会员名称|勋章<br/>
{#kuaifan_pc set="列表名:lists,显示数目:10,分页显示:1,分页名:pagelist,分页变量名:page,数据表:huiyuan_xunzhang,排序:intime DESC" where="type='huiyuan'"#}
{#foreach from=$lists item=list#}
	{#$setting = $list.setting|string2array#}
	{#$setting.nickname#}(ID:{#$list.dataid#})<a href="{#$URL#}&amp;z=addhy&amp;id={#$list.id#}">{#$list.title#}</a><br/>
{#foreachelse#}
	没有颁发记录,<a href="{#$URL#}&amp;z=addhy">点击颁发</a><br/>
{#/foreach#}

{#$pagelist#}<br/>


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
