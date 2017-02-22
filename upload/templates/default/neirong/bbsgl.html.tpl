{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v2_list.css' />
"#}
{#include file="common/header.tpl" title={#$SEO.title#} seo={#$SEO#}#}

<div class="daohang" id="top">
{#get_pos($M.id)#}
</div>

<div class="pltit"><a href="{#$V.url#}">{#$V.title#}</a></div>


<div class="pnpage">
楼主可选择操作：<br/>
[<a href="{#$URL#}&amp;a=xiugai">修改帖子</a>]
</div>

<div class="pnpage">
版主可选择操作：<br/>
[<a href="{#$URL#}&amp;a=dingzhi">{#if $V.dingzhi#}取消置顶{#else#}置顶贴子{#/if#}</a>][<a href="{#$URL#}&amp;a=jinghua">{#if $V.jinghua#}去除精华{#else#}加入精华{#/if#}</a>]<br/>
[<a href="{#$URL#}&amp;a=bzxiugai">修改帖子</a>][<a href="{#$URL#}&amp;a=shenhe">审核贴子</a>]
</div>


<div class="daohang">
{#get_pos($M.id)#}
</div>

{#include file="common/footerb.html.tpl"#}
{#include file="common/footer.tpl"#}