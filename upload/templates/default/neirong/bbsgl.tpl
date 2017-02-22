{#include file="common/header.tpl" title={#$SEO.title#} seo={#$SEO#}#}

{#get_pos($M.id)#}
<br/>

返回:<a href="{#$V.url#}">{#$V.title#}</a>({#nocache#}{#$V.read#}{#/nocache#})
<br/>


楼主可选择操作：<br/>
[<a href="{#$URL#}&amp;a=xiugai">修改帖子</a>]<br/>

.<br/>

版主可选择操作：<br/>
[<a href="{#$URL#}&amp;a=dingzhi">{#if $V.dingzhi#}取消置顶{#else#}置顶贴子{#/if#}</a>][<a href="{#$URL#}&amp;a=jinghua">{#if $V.jinghua#}去除精华{#else#}加入精华{#/if#}</a>]<br/>
[<a href="{#$URL#}&amp;a=bzxiugai">修改帖子</a>][<a href="{#$URL#}&amp;a=shenhe">审核贴子</a>]<br/>

-------------<br/>


{#get_pos($M.id)#}
<br/>
返回:<a href="{#$V.url#}">{#$V.title#}</a>
<br/>

{#include file="common/footerb.tpl"#}
{#include file="common/footer.tpl"#}