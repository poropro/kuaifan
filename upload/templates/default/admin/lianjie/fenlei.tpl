{#include file="common/header.tpl" title_top="1" title="友情链接分类"#}

[友情链接分类]<br/>
<a href="{#kuaifan getlink='a'#}">返回友链列表</a><br/>
-------------<br/>
{#$url = get_link('a|catid')#}
<a href="{#$url#}&amp;a=fladd">添加分类</a><br/>
{#kuaifan_pc set="列表名:lists,显示数目:10,分页显示:1,分页名:pagelist,分页变量名:pp,数据表:lianjie_fenlei,排序:listorder DESC"#}

ID.名称(类型)<br/>
{#foreach from=$lists item=list#}
	<a href="{#$url#}&amp;a=fladd&amp;catid={#$list.catid#}">{#$list.catid#}.{#$list.title#}</a>({#fenleixing($list.type)#})<br/>
{#foreachelse#}
	<u>没有任何友情链接分类</u><br/>
{#/foreach#}


{#$pagelist#}

<br/>
{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
