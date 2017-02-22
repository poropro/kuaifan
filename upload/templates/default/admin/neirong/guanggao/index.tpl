{#include file="common/header.tpl" title_top="1" title="文字内嵌广告"#}

{#$url = get_link('l')#}
{#$urladd = get_link('add')#}
[文字内嵌广告]<br/>
<a href="{#$urladd#}&amp;add=true">添加广告</a><br/>
-------------<br/>
{#if $smarty.get.l=='pl'#}
	类型:<a href="{#$url#}">所有</a>|<a href="{#$url#}&amp;l=nr">内容</a>|评论
	{#$wheresql = "type in ('通用','评论')"#}
{#elseif $smarty.get.l=='nr'#}
	类型:<a href="{#$url#}">所有</a>|内容|<a href="{#$url#}&amp;l=pl">评论</a>
	{#$wheresql = "type in ('通用','内容')"#}
{#else#}
	类型:<a href="{#$url#}&amp;l=nr">内容</a>|<a href="{#$url#}&amp;l=pl">评论</a>
{#/if#}<br/>

ID.类型|关键词<br/> 
{#kuaifan_pc set="列表名:lists,显示数目:10,分页显示:1,分页名:pagelist,分页变量名:pp,数据表:neirong_guanggao,排序:`order` DESC>`id` DESC" where=$wheresql#}
{#foreach from=$lists item=list#}
	<a href="{#$urladd#}&amp;add={#$list.id#}">{#$list.id#}.{#$list.type#}|{#$list.title#}</a><br/>
{#foreachelse#}
	<u>没有你要找的！</u><br/>
{#/foreach#}
-------------<br/>
此系统可将文章内容或评论中的指定关键词替换成指定广告内容。<br/>

{#include file="admin/footer.tpl"#}
{#include file="common/footer.tpl"#}
