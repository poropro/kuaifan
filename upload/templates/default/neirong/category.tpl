{#include file="common/header.tpl" title={#$SEO.title#} seo={#$SEO#}#}


{#get_pos($M.id)#}
<br/>

{#foreach from=$ML item=list key=n#}
	{#if $n%5 == 0 && $n > 0#}<br/>{#/if#}
	<a href="{#$list.url#}">{#cut_str($list.title,4)#}</a>
{#/foreach#}

<br/>-------------<br/>


{#kuaifan_neirong catid={#$M.arrchildid#} set="列表名:lists,显示数目:20,模型:{#$M.module#},状态:1,搜索变量名:key,标题长度:15,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:page"#}
{#foreach from=$lists item=list#}
	{#$list._n#}.<a href="{#$list.url#}">{#$list.title#}{#if $list.thumb#}[图]{#/if#}</a><br/>
{#foreachelse#}
	此栏目尚未发布内容。<br/>
{#/foreach#}

{#$pagelist#}
<br/>
{#get_pos($M.id)#}<br/>

{#kuaifan tongji="查看" get=$getarr#}
{#include file="common/footerb.tpl"#}	
{#include file="common/footer.tpl"#}