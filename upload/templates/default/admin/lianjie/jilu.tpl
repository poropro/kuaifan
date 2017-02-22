{#include file="common/header.tpl" title_top="1" title="访问/来访纪录"#}

[访问/来访纪录]<br/>
<a href="{#kuaifan getlink='a'#}&amp;a=edit">返回链接</a><br/>
-------------<br/>


{#kuaifan_pc set="列表名:lists,ID列表:idlist,显示数目:20,分页显示:1,分页名:pagelist,分页变量名:pp,数据表:lianjie_data,排序:inputtime DESC" where="id={#$smarty.get.id#}" listid="dataid"#}

{#foreach from=$lists item=list#}
	{#if $list.type=='0'#}[访问]{#else#}[来访]{#/if#}{#$list.inputtime|date_format:"%Y-%m-%d %H:%M:%S"#}(IP:{#$list.ip#})<br/>
{#foreachelse#}
	没有任何纪录。<br/>
{#/foreach#}

{#$pagelist#}
{#if $idlist#}
	<br/>*<a href="{#get_link('del')#}&amp;del={#$idlist#}">删除本页纪录</a>
{#/if#}
<br/>*<a href="{#get_link('del')#}&amp;del=all">删除全部纪录</a>

<br/>
{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
