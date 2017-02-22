{#include file="common/header.tpl" title_top="1" title="会员勋章"#}

[会员勋章]<br/>
<a href="{#$URL#}&amp;z=add">添加勋章</a>|<a href="{#$URL#}&amp;z=fenlei">分类</a><br/>
-------------<br/>
分类|勋章名称<br/>
{#kuaifan_pc set="列表名:lists,显示数目:10,分页显示:1,分页名:pagelist,分页变量名:page,数据表:huiyuan_xunzhang,排序:intime DESC" where="type='xunzhang'"#}
{#foreach from=$lists item=list#}
	{#$setting = $list.setting|string2array#}
	<a href="{#$URL#}&amp;z=add&amp;id={#$list.id#}">{#$setting.catid_cn#}|{#$list.title#}</a><br/>
{#foreachelse#}
	没有任何勋章,<a href="{#$URL#}&amp;z=add">点击添加</a><br/>
{#/foreach#}

{#$pagelist#}<br/>

-------------<br/>
<a href="{#$URL#}&amp;z=list">管理|分配勋章会员</a><br/>


{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
