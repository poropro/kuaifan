{#include file="common/header.tpl" title="评论排行榜"#}


评论排行榜<br/>
-------------<br/>
{#if $smarty.get.total#}
	<a href="{#get_link('total')#}">评论最多</a> | 最新评论
	{#$desc = ''#}
{#else#}
	评论最多 | <a href="{#get_link('total')#}&amp;total=1">最新评论</a>
	{#$desc = 'total DESC'#}
{#/if#}
<br/>

{#kuaifan_pinglun set="列表名:lists,显示数目:15,最多数目:150,排序:$desc,标题长度:15,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:page"#}
{#foreach from=$lists item=list#}
	{#$list._n#}.<a href="{#$list.url#}">{#$list.title#}</a>(<a href="{#$list.plurl#}">{#$list.total#}</a>)<br/>
{#foreachelse#}
	没有任何评论的内容。<br/>
{#/foreach#}

{#$pagelist#}
<br/>

{#kuaifan tongji="查看"#}
{#include file="common/footerb.tpl"#}
{#include file="common/footer.tpl"#}