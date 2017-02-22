{#$__seo_head="
	<link type='text/css' rel='stylesheet' href='{#$smarty.const.CSS_PATH#}v2_tongyong.css' />
"#}
{#include file="common/header.tpl" title="会员动态"#}

<div class="daohang">
<a href="{#kf_url('index')#}">首页</a>&gt;会员动态
</div>

<div class="pnpage">
{#kuaifan_tongji set="列表名:lists,ID列表:idlist,会员ID:GET[id],显示数目:15,标题长度:15,填补字符:...,分页显示:1,分页名:pagelist,分页变量名:pp"#}
{#$url = get_link('sid|vs','',1)#}
{#foreach from=$lists item=list#}
	<small>{#$list._n#}.{#if $list.userid>0#}<a href="{#$url#}&amp;m=huiyuan&amp;c=ziliao&amp;userid={#$list.userid#}">{#$list.username_#}</a>{#else#}{#$list.username_#}{#/if#}({#$list.time|date_format:"%m-%d %H:%M:%S"#})</small><br/>
	{#$list.type#}<a href="{#$list.url#}&amp;sid={#$SID#}&amp;vs={#$VS#}">{#$list.title#}</a><br/>-----<br/>
{#foreachelse#}
	没有任何会员动态。<br/>
{#/foreach#}

{#$pagelist#}
</div>


{#include file="common/footerb.html.tpl"#}
{#include file="common/footer.tpl"#}
