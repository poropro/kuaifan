{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v2_list.css' />
"#}
{#include file="common/header.tpl" title="评论排行榜"#}

<div class="daohang" id="top">
评论排行榜
</div>

<div class="pnpage">
{#if $smarty.get.total#}
	<a href="{#get_link('total')#}">评论最多</a> | 最新评论
	{#$desc = ''#}
{#else#}
	评论最多 | <a href="{#get_link('total')#}&amp;total=1">最新评论</a>
	{#$desc = 'total DESC'#}
{#/if#}
</div>

<div class="cbnlist">
{#kuaifan_pinglun set="列表名:lists,显示数目:15,最多数目:150,排序:$desc,标题长度:15,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:page"#}
{#foreach from=$lists item=list#}
	<p><span>{#$list._n#}</span><a href="{#$list.url#}">{#$list.title#}</a>(<a href="{#$list.plurl#}">{#$list.total#}</a>)</p>
{#foreachelse#}
	<p>没有任何评论的内容。</p>
{#/foreach#}
</div>

<div class="pnpage">
{#$pagelist#}
</div>


{#kuaifan tongji="查看"#}
{#include file="common/footerb.html.tpl"#}
{#include file="common/footer.tpl"#}